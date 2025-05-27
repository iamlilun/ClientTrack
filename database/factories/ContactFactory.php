<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(), // 關聯客戶
            'contact_type' => $this->faker->randomElement(['email', 'phone', 'visit']),
            'content' => $this->faker->sentence,
            'contacted_at' => now()->subDays(rand(0, 30)),
        ];
    }
}
