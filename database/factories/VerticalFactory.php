<?php

namespace Database\Factories;

use App\Models\Vertical;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerticalFactory extends Factory
{
    protected $model = Vertical::class;

    public function definition(): array
    {
        $code = fake()->unique()->slug(1);

        return ['code' => $code, 'name' => ucfirst($code), 'status' => 'active'];
    }
}
