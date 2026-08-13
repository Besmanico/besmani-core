<?php

namespace Database\Factories;

use App\Models\CanonicalUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class CanonicalUserFactory extends Factory
{
    protected $model = CanonicalUser::class;

    public function definition(): array
    {
        return ['first_name' => fake()->firstName(), 'last_name' => fake()->lastName(), 'display_name' => fake()->name(), 'email' => fake()->unique()->safeEmail(), 'email_normalized' => fake()->unique()->safeEmail(), 'status' => 'active'];
    }
}
