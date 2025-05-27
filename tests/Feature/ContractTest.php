<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Contact;

class ContractTest extends TestCase
{
    use RefreshDatabase;

    protected string $prefix = '/api/v1/';
    protected $user;
    protected $token;
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('api-token')->plainTextToken;
        $this->client = Client::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_list_contacts_of_a_client()
    {
        Contact::factory()->count(3)->create(['client_id' => $this->client->id]);

        $response = $this->withToken($this->token)
            ->getJson($this->prefix . "clients/{$this->client->id}/contacts");

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_contact_for_a_client()
    {
        $payload = [
            'contact_type' => 'email',
            'content' => '寄送報價單',
            'contacted_at' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->withToken($this->token)
            ->postJson($this->prefix . "clients/{$this->client->id}/contacts", $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['contact_type' => 'email']);

        $this->assertDatabaseHas('contacts', ['content' => '寄送報價單']);
    }

    /** @test */
    public function it_can_show_a_contact()
    {
        $contact = Contact::factory()->create(['client_id' => $this->client->id]);

        $response = $this->withToken($this->token)
            ->getJson($this->prefix . "clients/{$this->client->id}/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $contact->id]);
    }

    /** @test */
    public function it_can_update_a_contact()
    {
        $contact = Contact::factory()->create(['client_id' => $this->client->id]);

        $payload = [
            'contact_type' => 'phone',
            'content' => '改約面談時間',
            'contacted_at' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->withToken($this->token)
            ->putJson($this->prefix . "clients/{$this->client->id}/contacts/{$contact->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['content' => '改約面談時間']);
    }

    /** @test */
    public function it_can_delete_a_contact()
    {
        $contact = Contact::factory()->create(['client_id' => $this->client->id]);

        $response = $this->withToken($this->token)
            ->deleteJson($this->prefix . "clients/{$this->client->id}/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => '聯絡紀錄已刪除']);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}
