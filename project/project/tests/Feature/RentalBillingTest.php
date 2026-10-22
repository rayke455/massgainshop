<?php
namespace Tests\Feature;

use App\Models\{Order, RentalPanel, User};
use App\Services\RentalBilling;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class RentalBillingTest extends TestCase
{
    use DatabaseTransactions;

    private function fixture(): array
    {
        $name = Str::lower(Str::random(16));
        $owner = User::create(['username'=>$name,'email'=>$name.'@example.test','password'=>'test-only','balance'=>100]);
        $panel = RentalPanel::create(['owner_id'=>$owner->id,'name'=>'Billing test','slug'=>$name,'status'=>'active','features'=>['orders'],'expires_at'=>now()->addDays(30)]);
        RentalBilling::configure($panel, true, 24);
        $order = Order::create(['user_id'=>$owner->id,'service_id'=>0,'category_id'=>0,'quantity'=>100,'price'=>50,'status'=>'processing']);
        $order->forceFill(['rental_panel_id'=>$panel->id])->save();
        return [$owner,$panel,$order];
    }

    private function balance(RentalPanel $panel): float
    {
        return (float) DB::table('rental_billing_accounts')->where('panel_id',$panel->id)->value('balance');
    }

    public function test_commission_uses_profit_snapshot_and_completion_is_idempotent(): void
    {
        [$owner,$panel,$order] = $this->fixture();
        RentalBilling::snapshot($order,$panel,20,5);
        RentalBilling::snapshot($order,$panel,100,99);
        RentalBilling::settle($order->id);
        $this->assertSame(0.0,$this->balance($panel));
        $order->update(['status'=>'completed']);
        RentalBilling::settle($order->id); RentalBilling::settle($order->id);
        $this->assertSame(-1.0,$this->balance($panel));
        $this->assertSame(1,DB::table('rental_billing_ledger')->where('panel_id',$panel->id)->count());
        $this->assertEquals(100,$owner->fresh()->balance);
    }

    public function test_full_refund_restores_fee_only_once(): void
    {
        [, $panel,$order] = $this->fixture();
        RentalBilling::snapshot($order,$panel,20,5);
        $order->update(['status'=>'completed']); RentalBilling::settle($order->id);
        $order->update(['status'=>'refunded']); RentalBilling::settle($order->id); RentalBilling::settle($order->id);
        $this->assertSame(0.0,$this->balance($panel));
        $this->assertNull(DB::table('rental_billing_accounts')->where('panel_id',$panel->id)->value('grace_ends_at'));
        $this->assertSame(2,DB::table('rental_billing_ledger')->where('panel_id',$panel->id)->count());
    }

    public function test_partial_delivery_charges_only_delivered_profit(): void
    {
        [, $panel,$order] = $this->fixture();
        RentalBilling::snapshot($order,$panel,20,5);
        $order->update(['status'=>'partial','remains'=>40]);
        RentalBilling::settle($order->id); RentalBilling::settle($order->id);
        $this->assertSame(-0.6,$this->balance($panel));
    }

    public function test_topup_is_idempotent_and_clears_expired_debt(): void
    {
        [$owner,$panel,$order] = $this->fixture();
        RentalBilling::snapshot($order,$panel,20,5);
        $order->update(['status'=>'completed']); RentalBilling::settle($order->id);
        $this->assertFalse(RentalBilling::blocked($panel->id));
        $this->travel(25)->hours();
        $this->assertTrue(RentalBilling::blocked($panel->id));
        RentalBilling::transfer($owner,$panel,10,'test-transfer-token');
        RentalBilling::transfer($owner,$panel,10,'test-transfer-token');
        $this->assertSame(9.0,$this->balance($panel));
        $this->assertEquals(90,$owner->fresh()->balance);
        $this->assertFalse(RentalBilling::blocked($panel->id));
        $this->assertSame(1,DB::table('transactions')->where('user_id',$owner->id)->count());
        $this->travelBack();
    }

    public function test_insufficient_wallet_transfer_has_no_side_effect(): void
    {
        [$owner,$panel] = $this->fixture();
        try { RentalBilling::transfer($owner,$panel,101,'test-transfer-token'); $this->fail('Must reject insufficient funds'); }
        catch (\Illuminate\Validation\ValidationException $exception) {
            $this->assertEquals(100,$owner->fresh()->balance);
            $this->assertSame(0.0,$this->balance($panel));
            $this->assertSame(0,DB::table('rental_billing_ledger')->where('panel_id',$panel->id)->count());
        }
    }

    public function test_old_orders_and_nonpositive_profit_do_not_generate_fees(): void
    {
        [, $panel,$order] = $this->fixture();
        $order->update(['status'=>'completed']); RentalBilling::settle($order->id);
        $this->assertSame(0.0,$this->balance($panel));
        RentalBilling::snapshot($order,$panel,-10,5); RentalBilling::settle($order->id);
        $this->assertSame(0.0,$this->balance($panel));
    }

    public function test_owner_cannot_topup_someone_elses_panel(): void
    {
        [$owner] = $this->fixture();
        [, $other] = $this->fixture();
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        RentalBilling::transfer($owner,$other,10,'test-transfer-token');
    }

    public function test_partial_topup_and_settings_change_do_not_restart_grace(): void
    {
        [$owner,$panel,$order] = $this->fixture();
        RentalBilling::snapshot($order,$panel,100,10);
        $order->update(['status'=>'completed']); RentalBilling::settle($order->id);
        $deadline = DB::table('rental_billing_accounts')->where('panel_id',$panel->id)->value('grace_ends_at');
        $this->travel(2)->hours();
        RentalBilling::transfer($owner,$panel,1,'partial-topup-token');
        RentalBilling::configure($panel,true,72);
        $this->assertSame($deadline,DB::table('rental_billing_accounts')->where('panel_id',$panel->id)->value('grace_ends_at'));
        $this->travelBack();
    }

    public function test_disabled_billing_never_snapshots_a_new_fee(): void
    {
        [, $panel,$order] = $this->fixture();
        RentalBilling::configure($panel,false,24);
        $this->assertFalse(RentalBilling::snapshot($order,$panel,100,5));
        $this->assertDatabaseMissing('rental_billing_charges',['order_id'=>$order->id]);
    }
}
