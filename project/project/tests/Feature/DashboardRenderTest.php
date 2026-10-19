<?php

namespace Tests\Feature;

use App\Http\Controllers\User\HomeController;
use App\Models\User;
use Tests\TestCase;

class DashboardRenderTest extends TestCase
{
    public function test_dashboard_renders_on_repeated_requests_for_an_empty_account(): void
    {
        $user = new User();
        $user->forceFill(['id' => 0, 'username' => 'preview_customer', 'balance' => 0, 'currency' => 'USD', 'language_id' => 1]);
        $this->actingAs($user);
        session(['theme' => 'light_green', 'user_dashboard' => 'user_dashboard_style_one']);
        for ($refresh = 0; $refresh < 2; $refresh++) {
            $controller = new HomeController();
            $property = new \ReflectionProperty($controller, 'user');
            $property->setAccessible(true);
            $property->setValue($controller, $user);
            $view = $controller->index();
            $this->assertInstanceOf(\Illuminate\View\View::class, $view, 'Dashboard unexpectedly redirected.');
            $html = $view->render();
            $this->assertStringContainsString('Welcome back', $html);
            $this->assertStringNotContainsString('Deposit Statistics', $html);
            $this->assertStringNotContainsString('Transaction Statistics', $html);
        }
    }

    public function test_failed_dashboard_returns_retry_page_instead_of_redirecting_back(): void
    {
        $user = new User();
        $user->forceFill(['id' => 0, 'currency' => 'USD', 'balance' => 0]);
        $controller = new HomeController();
        $property = new \ReflectionProperty($controller, 'user');
        $property->setAccessible(true);
        $property->setValue($controller, $user);
        \Illuminate\Support\Facades\DB::shouldReceive('raw')->andThrow(new \RuntimeException('Simulated dashboard query failure'));
        $handler = \Mockery::mock(\Illuminate\Contracts\Debug\ExceptionHandler::class);
        $handler->shouldReceive('report')->once()->with(\Mockery::type(\RuntimeException::class));
        $this->app->instance(\Illuminate\Contracts\Debug\ExceptionHandler::class, $handler);
        $response = $controller->index();
        $this->assertSame(503, $response->getStatusCode());
        $this->assertStringContainsString('Try again', $response->getContent());
        $this->assertFalse($response->headers->has('Location'));
    }
}
