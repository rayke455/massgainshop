<?php
namespace Tests\Feature;
use App\Models\{RentalPanel, User};
use App\Services\RentalPromotions;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RentalPromotionTest extends TestCase
{
    use DatabaseTransactions;
    private function setupPromotion(): array {
        $name = 'promo_'.Str::lower(Str::random(12));
        $owner = User::create(['username'=>$name, 'email'=>$name.'@example.test', 'password'=>'test', 'balance'=>100, 'status'=>1]);
        $panel = RentalPanel::create(['owner_id'=>$owner->id, 'name'=>'Promo panel', 'slug'=>$name, 'status'=>'active', 'expires_at'=>now()->addDay(), 'domain_token'=>Str::random(48), 'features'=>['accounts']]);
        $customer = User::create(['username'=>$name.'_c', 'email'=>$name.'_c@example.test', 'password'=>'test', 'balance'=>0, 'status'=>1, 'rental_panel_id'=>$panel->id]);
        $id = DB::table('rental_promotions')->insertGetId(['panel_id'=>$panel->id, 'code'=>'WELCOME', 'amount'=>10, 'max_uses'=>2, 'created_at'=>now(), 'updated_at'=>now()]);
        return [$owner, $panel, $customer, $id];
    }
    public function test_redemption_transfers_existing_funds_with_two_ledger_entries(): void {
        [$owner,$panel,$customer,$id] = $this->setupPromotion();
        RentalPromotions::redeem($panel,$customer,'welcome');
        $this->assertEquals(90,$owner->fresh()->balance);
        $this->assertEquals(10,$customer->fresh()->balance);
        $this->assertDatabaseHas('transactions',['user_id'=>$owner->id,'amount'=>10,'trx_type'=>'-']);
        $this->assertDatabaseHas('transactions',['user_id'=>$customer->id,'amount'=>10,'trx_type'=>'+']);
        $this->assertDatabaseHas('rental_promotions',['id'=>$id,'uses'=>1]);
    }
    public function test_repeated_redemption_does_not_credit_twice(): void {
        [$owner,$panel,$customer,$id] = $this->setupPromotion();
        RentalPromotions::redeem($panel,$customer,'WELCOME');
        try { RentalPromotions::redeem($panel,$customer,'WELCOME'); $this->fail('Repeat accepted'); } catch (ValidationException $e) {}
        $this->assertEquals(10,$customer->fresh()->balance);
        $this->assertEquals(90,$owner->fresh()->balance);
        $this->assertSame(1,DB::table('rental_promotion_redemptions')->where('promotion_id',$id)->count());
    }
    public function test_insufficient_owner_funds_leave_no_redemption_or_credit(): void {
        [$owner,$panel,$customer,$id] = $this->setupPromotion(); $owner->update(['balance'=>5]);
        try { RentalPromotions::redeem($panel,$customer,'WELCOME'); $this->fail('Unfunded credit'); } catch (ValidationException $e) {}
        $this->assertEquals(0,$customer->fresh()->balance);
        $this->assertDatabaseHas('rental_promotions',['id'=>$id,'uses'=>0]);
        $this->assertDatabaseMissing('rental_promotion_redemptions',['promotion_id'=>$id]);
    }
    public function test_expired_disabled_and_exhausted_codes_fail(): void {
        [$owner,$panel,$customer,$id] = $this->setupPromotion();
        foreach ([['expires_at'=>now()->subMinute()], ['active'=>false], ['uses'=>2]] as $invalid) {
            DB::table('rental_promotions')->where('id',$id)->update(array_replace(['expires_at'=>null,'active'=>true,'uses'=>0], $invalid));
            try { RentalPromotions::redeem($panel,$customer,'WELCOME'); $this->fail('Unavailable code accepted'); } catch (ValidationException $e) {}
        }
        $this->assertEquals(0,$customer->fresh()->balance);
    }
    public function test_other_panel_customers_cannot_redeem(): void {
        [$owner,$panel,$customer,$id] = $this->setupPromotion(); $customer->update(['rental_panel_id'=>null]);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        RentalPromotions::redeem($panel,$customer,'WELCOME');
    }
}
