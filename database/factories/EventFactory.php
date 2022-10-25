<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'start' => $this->faker->date,
            'end' => $this->faker->date,
            'subject' => $this->faker->sentence,
            'message' => $this->faker->sentence,
            'room' => $this->faker->numberBetween(1,100),
            'class' => 'free'
        ];
    }
}
