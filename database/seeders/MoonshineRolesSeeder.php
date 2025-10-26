<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use MoonShine\Laravel\Models\MoonshineUserRole;

class MoonshineRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MoonshineUserRole::updateOrCreate(
            ['name' => 'Admin'],
            ['updated_at' => now()]
        );

        MoonshineUserRole::updateOrCreate(
            ['name' => 'User'],
            ['updated_at' => now()]
        );
    }
}
