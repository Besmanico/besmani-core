<?php

namespace Database\Factories;

use App\Models\BusinessType;
use App\Models\CanonicalBusiness;
use Illuminate\Database\Eloquent\Factories\Factory;

class CanonicalBusinessFactory extends Factory
{
    protected $model = CanonicalBusiness::class;

    public function definition(): array
    {
        return ['business_type_id' => BusinessType::factory(), 'display_name' => fake()->company(), 'slug' => fake()->unique()->slug(), 'status' => 'active'];
    }
}
