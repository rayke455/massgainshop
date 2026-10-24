<?php

namespace Tests\Feature;

use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\SupportTicketController;
use App\Models\{Deposit, SupportTicket, Transaction, User};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class PortalCorrectionsTest extends TestCase
{
    use DatabaseTransactions;

    private function customer(): User
    {
        $name = 'audit_'.Str::random(12);
        return User::create(['username' => $name, 'email' => $name.'@example.test',
            'password' => bcrypt(Str::random(32)), 'balance' => 0, 'currency' => 'KES', 'language_id' => 1]);
    }

    public function test_dashboard_counts_only_successful_deposits_and_shows_five_newest_transactions(): void
    {
        $user = $this->customer();
        $this->actingAs($user);
        session(['theme' => 'light_green']);
        foreach ([0, 1, 2, 3] as $status) {
            Deposit::create(['user_id' => $user->id, 'status' => $status,
                'amount' => 50, 'payable_amount' => 50, 'payable_amount_in_base_currency' => 50,
                'trx_id' => 'audit-'.Str::uuid()]);
        }
        $expected = [];
        for ($i = 0; $i < 7; $i++) {
            $transaction = new Transaction(['user_id' => $user->id, 'amount' => 1, 'balance' => 0,
                'charge' => 0, 'trx_type' => '+', 'remarks' => 'Regression fixture', 'trx_id' => 'audit-'.Str::uuid()]);
            $transaction->created_at = now()->subMinutes(7 - $i);
            $transaction->save();
            $expected[] = $transaction->id;
        }
        $controller = new HomeController();
        $property = new \ReflectionProperty($controller, 'user');
        $property->setAccessible(true);
        $property->setValue($controller, $user);
        $view = $controller->index();
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
        $data = $view->getData();
        $this->assertEquals(50, $data['totalYearDeposits']);
        $this->assertSame(array_slice(array_reverse($expected), 0, 5), $data['transactions']->pluck('id')->all());
    }

    public function test_open_ticket_filter_excludes_closed_tickets_and_other_customers(): void
    {
        $user = $this->customer();
        $other = $this->customer();
        $this->actingAs($user);
        session(['theme' => 'light_green']);
        $open = SupportTicket::create(['user_id' => $user->id, 'ticket' => Str::random(10), 'subject' => 'Open fixture', 'status' => 0]);
        SupportTicket::create(['user_id' => $user->id, 'ticket' => Str::random(10), 'subject' => 'Closed fixture', 'status' => 3]);
        SupportTicket::create(['user_id' => $other->id, 'ticket' => Str::random(10), 'subject' => 'Other account', 'status' => 0]);
        $request = Request::create('/user/ticket', 'GET', ['status' => '0']);
        $view = (new SupportTicketController())->index($request);
        $this->assertSame([$open->id], $view->getData()['tickets']->pluck('id')->all());
        $this->app->instance('request', $request);
        $html = view('themes.light_green.user.support_ticket.components.ticket_filter')->render();
        $this->assertMatchesRegularExpression('/value="0"\s+selected/', $html);
    }

    public function test_homepage_keeps_original_content_and_three_account_actions(): void
    {
        session(['theme' => 'light_green']);
        $this->get('/')->assertOk()->assertSee('Best SMM in Africa.')
            ->assertSee(route('login'), false)->assertSee(route('register'), false)
            ->assertSee(route('guest.order'), false)->assertSee('Order without account')
            ->assertSee('Rent your own panel')->assertSee('zk-hero');
    }
}
