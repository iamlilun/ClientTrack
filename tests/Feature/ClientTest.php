<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    protected string $prefix = '/api/v1/';
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and generate an API token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('api-token')->plainTextToken;
    }


    /** @test */
    public function it_can_list_clients()
    {
        Client::factory()->count(2)->create(['user_id' => $this->user->id]);

        $response = $this->withToken($this->token)
            ->getJson($this->prefix . 'clients');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    /** @test */
    public function it_can_create_a_client()
    {
        $payload = [
            'name' => 'Acme Corp',
            'email' => 'acme@example.com',
            'phone' => '0912345678',
            'notes' => '重要客戶',
        ];

        $response = $this->withToken($this->token)
            ->postJson($this->prefix . 'clients', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Acme Corp']);

        $this->assertDatabaseHas('clients', ['email' => 'acme@example.com']);
    }

    /** @test */
    public function it_can_show_a_client()
    {
        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withToken($this->token)
            ->getJson($this->prefix . "clients/{$client->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $client->id]);
    }

    /** @test */
    public function it_can_update_a_client()
    {
        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withToken($this->token)
            ->putJson($this->prefix . "clients/{$client->id}", [
                'name' => 'Updated Corp',
                'email' => 'new@example.com',
                'phone' => '0988777666',
                'notes' => '已更新',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Corp']);

        $this->assertDatabaseHas('clients', ['email' => 'new@example.com']);
    }

    /** @test */
    public function it_can_delete_a_client()
    {
        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withToken($this->token)
            ->deleteJson($this->prefix . "clients/{$client->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => '客戶已刪除']);

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}
