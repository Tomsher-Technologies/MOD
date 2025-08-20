<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Escort;
use App\Models\Delegation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EscortControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_the_escorts_index_page_is_rendered_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('escorts.index'));

        $response->assertStatus(200);
    }

    public function test_an_escort_can_be_assigned_to_a_delegation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $escort = Escort::factory()->create();
        $delegation = Delegation::factory()->create();

        $response = $this->post(route('escorts.assign', $escort->id), [
            'delegation_id' => $delegation->id,
        ]);

        $response->assertRedirect(route('escorts.index'));
        $this->assertDatabaseHas('escorts', [
            'id' => $escort->id,
            'delegation_id' => $delegation->id,
        ]);
    }

    public function test_an_escort_can_be_unassigned_from_a_delegation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $delegation = Delegation::factory()->create();
        $escort = Escort::factory()->create(['delegation_id' => $delegation->id]);

        $response = $this->get(route('escorts.unassign', $escort->id));

        $response->assertRedirect(route('escorts.index'));
        $this->assertDatabaseHas('escorts', [
            'id' => $escort->id,
            'delegation_id' => null,
        ]);
    }
}
