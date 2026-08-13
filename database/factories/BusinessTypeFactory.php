<?php

namespace Database\Factories;

use App\Models\BusinessType;
use App\Models\Vertical;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessTypeFactory extends Factory
{
    protected $model = BusinessType::class;

    public function definition(): array
    {
        $code = fake()->unique()->slug(2);

        return ['vertical_id' => Vertical::factory(), 'code' => $code, 'name' => ucwords(str_replace('-', ' ', $code)), 'status' => 'active'];
    }
}
