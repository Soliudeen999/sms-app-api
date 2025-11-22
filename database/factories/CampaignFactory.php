<?php

namespace Database\Factories;

use App\Enums\Campaign\CampaignRecipientType;
use App\Enums\Message\MessageType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'body' => fake()->paragraph(),
            'recipients' => ['+10000000'],
            'recipient_type' => CampaignRecipientType::PHONE_NUMBERS,
            'type' => MessageType::SCHEDULED,
            'user_id' => User::inRandomOrder()->first() ?? User::factory(),
            'provider' => 't2frocoms',
        ];
    }
}
