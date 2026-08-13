<?php

namespace Database\Seeders;

use App\Models\Vertical;
use Illuminate\Database\Seeder;

class CanonicalVerticalSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['beauty' => 'Beauty', 'medical' => 'Medical', 'travel' => 'Travel', 'pet' => 'Pet'] as $code => $name) {
            Vertical::updateOrCreate(['code' => $code], ['name' => $name, 'status' => 'active']);
        }
    }
}
