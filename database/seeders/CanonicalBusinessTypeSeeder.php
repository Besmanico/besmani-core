<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use App\Models\Vertical;
use Illuminate\Database\Seeder;

class CanonicalBusinessTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['beauty_salon' => 'beauty', 'barbershop' => 'beauty', 'beauty_clinic' => 'beauty', 'medical_clinic' => 'medical', 'academy' => 'beauty', 'store' => 'beauty', 'independent_provider' => 'beauty'];
        foreach ($types as $code => $vertical) {
            BusinessType::updateOrCreate(['code' => $code], ['vertical_id' => Vertical::where('code', $vertical)->value('id'), 'name' => ucwords(str_replace('_', ' ', $code)), 'status' => 'active']);
        }
    }
}
