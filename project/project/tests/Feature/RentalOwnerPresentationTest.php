<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RentalOwnerPresentationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_owner_preview_screens_share_navigation_and_keep_writes_disabled(): void
    {
        foreach (['control', 'landing', 'content', 'support', 'promotions'] as $screen) {
            $this->get(route('rental.preview.alias', ['screen' => $screen]))
                ->assertOk()
                ->assertSee('aria-label="Tenant workspace"', false)
                ->assertSee('id="tenant-control"', false)
                ->assertSee('class="control-group-label">Setup', false)
                ->assertSee('class="control-group-label">Create', false)
                ->assertSee('class="control-group-label">Other', false)
                ->assertSee('reference-icon', false)
                ->assertSee('<fieldset disabled', false);
        }
    }

    public function test_editor_navigation_points_back_to_owner_routes_not_editor_hashes(): void
    {
        $this->get(route('rental.preview.alias', ['screen' => 'landing']))
            ->assertOk()
            ->assertSee(route('rental.preview.alias').'#services', false)
            ->assertSee(route('rental.preview.alias').'#general', false)
            ->assertSee('Landing design preview');
    }
}
