<?php

namespace Database\Seeders;

use App\Models\MembershipRole;
use Illuminate\Database\Seeder;

class MembershipRoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['owner', 'business_admin', 'manager', 'provider', 'staff', 'front_desk', 'instructor'] as $i => $code) {
            MembershipRole::updateOrCreate(['code' => $code], ['name' => ucwords(str_replace('_', ' ', $code)), 'status' => 'active', 'sort_order' => $i]);
        }
    }
}
