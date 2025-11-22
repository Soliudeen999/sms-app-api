<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CampaignControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setup(): void
    {
        parent::setup();

        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Sanctum::actingAs($this->user);
    }

    public function test_user_can_list_their_campaigns()
    {
        Campaign::factory()->count(3)->for($this->user, 'owner')->create();

        $response = $this->getJson('/api/v1/campaigns');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['data'],
            ]);
    }

    public function test_user_can_create_campaign()
    {
        $payload = [
            'title' => 'Promo',
            'body' => 'Testing SMS',
            'type' => 'instant',
            'recipient_type' => 'phone_numbers',
            'recipients' => ['+2348011122233'],
        ];

        $response = $this->postJson('/api/v1/campaigns', $payload);

        $response->assertCreated()
            ->assertJsonStructure(['status', 'message', 'data']);

        $this->assertDatabaseHas('campaigns', [
            'title' => 'Promo',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_view_a_campaign()
    {
        $campaign = Campaign::factory()->for($this->user, 'owner')->create();

        $response = $this->getJson("/api/v1/campaigns/{$campaign->id}");

        $response->assertOk()
            ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_user_can_update_a_campaign()
    {
        $campaign = Campaign::factory()->for($this->user, 'owner')->create([
            'title' => 'Old',
        ]);

        $payload = ['title' => 'Updated'];

        $response = $this->putJson("/api/v1/campaigns/{$campaign->id}", $payload);

        $response->assertOk();

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'title' => 'Updated',
        ]);
    }

    public function test_user_can_delete_their_campaign()
    {
        $campaign = Campaign::factory()->for($this->user, 'owner')->create();

        $response = $this->deleteJson("/api/v1/campaigns/{$campaign->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('campaigns', [
            'id' => $campaign->id,
        ]);
    }

    public function test_user_cannot_access_others_campaign()
    {
        $other = User::factory()->create();
        $campaign = Campaign::factory()->for($other, 'owner')->create();

        $response = $this->getJson("/api/v1/campaigns/{$campaign->id}");

        $response->assertForbidden();
    }

    public function test_can_fetch_campaign_messages()
    {
        $campaign = Campaign::factory()->for($this->user, 'owner')->create();

        $response = $this->getJson("/api/v1/campaigns/{$campaign->id}/messages");

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['data']
            ]);
    }
}
