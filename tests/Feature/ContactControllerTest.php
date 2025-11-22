<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create verified user
        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Sanctum::actingAs($this->user);
    }

    public function test_user_can_list_their_contacts()
    {
        Contact::factory()->count(3)->for($this->user, 'owner')->create();

        $response = $this->getJson('/api/v1/contacts');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['data']
            ]);
    }

    public function test_user_can_create_a_contact()
    {
        $payload = [
            'name' => 'John Doe',
            'phone_number' => '+2348011122233',
        ];

        $response = $this->postJson('/api/v1/contacts', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['status', 'message', 'data']);

        $this->assertDatabaseHas('contacts', [
            'name' => 'John Doe',
            'phone_number' => '+2348011122233',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_view_a_single_contact()
    {
        $contact = Contact::factory()->for($this->user, 'owner')->create();

        $response = $this->getJson("/api/v1/contacts/{$contact->id}");

        $response->assertOk()
            ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_user_cannot_view_contact_belonging_to_other_user()
    {
        $otherUser = User::factory()->create();
        $contact = Contact::factory()->for($otherUser, 'owner')->create();

        $response = $this->getJson("/api/v1/contacts/{$contact->id}");

        $response->assertForbidden();
    }

    public function test_user_can_update_their_contact()
    {
        $contact = Contact::factory()->for($this->user, 'owner')->create([
            'name' => 'Old Name'
        ]);

        $payload = ['name' => 'New Name'];

        $response = $this->putJson("/api/v1/contacts/{$contact->id}", $payload);

        $response->assertOk()
            ->assertJsonStructure(['status', 'message', 'data']);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'name' => 'New Name',
        ]);
    }

    public function test_user_cannot_update_contact_of_another_user()
    {
        $otherUser = User::factory()->create();
        $contact = Contact::factory()->for($otherUser, 'owner')->create();

        $response = $this->putJson("/api/v1/contacts/{$contact->id}", [
            'name' => 'Should Fail'
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_delete_their_contact()
    {
        $contact = Contact::factory()->for($this->user, 'owner')->create();

        $response = $this->deleteJson("/api/v1/contacts/{$contact->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id,
        ]);
    }

    public function test_user_cannot_delete_contact_of_another_user()
    {
        $otherUser = User::factory()->create();
        $contact = Contact::factory()->for($otherUser, 'owner')->create();

        $response = $this->deleteJson("/api/v1/contacts/{$contact->id}");

        $response->assertForbidden();
    }
}
