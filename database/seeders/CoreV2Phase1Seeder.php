<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CoreV2Phase1Seeder extends Seeder
{
    public function run(): void
    {
        $this->call([CanonicalVerticalSeeder::class, CanonicalBusinessTypeSeeder::class, MembershipRoleSeeder::class]);
    }
}
