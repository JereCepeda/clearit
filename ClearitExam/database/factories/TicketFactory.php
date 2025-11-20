<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement([1, 2, 3]),
            'transport_mode' => $this->faker->randomElement(['air', 'sea', 'land']),
            'country' => $this->faker->country(),
            'status' => $this->faker->randomElement(['new', 'in_progress', 'completed']),
            'transported_product' => $this->faker->word(),
            'comments' => $this->faker->optional()->sentence(),
            'created_by' => \App\Models\User::factory(),
            'assigned_agent_id' => null,
            'pending_documents' => null,
            'last_updated_by' => null,
            'documents_requested_at' => null,
        ];
    }
}
