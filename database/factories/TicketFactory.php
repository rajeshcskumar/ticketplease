<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = \App\Models\Ticket::class; // Specify the model

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // This will create a new User each time
            'title' => fake()->words(3, true), // Generates a title with 3 words
            'description' => fake()->paragraph(), // Get a single paragraph as a string
            'status' => fake()->randomElement(['A', 'B', 'C']), // Random status
        ];
    }
}
