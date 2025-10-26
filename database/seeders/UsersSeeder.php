<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unit = Unit::first();

        User::create([
            'name'                   => 'Администратор',
            'email'                  => 'admin@mail.com',
            'password'               => Hash::make('12345678'),
            'phone'                  => '+79995554433',
            'is_active'              => true,
            'unit_ulid'              => $unit->ulid,
            'position'               => 'Разработчик',
            'moonshine_user_role_id' => 1,
            'remember_token'         => null,
        ]);

        User::create([
            'name'                   => 'Пользователь',
            'email'                  => 'user@mail.com',
            'password'               => Hash::make('123123123'),
            'phone'                  => '+79998887766',
            'is_active'              => true,
            'unit_ulid'              => $unit->ulid,
            'position'               => 'Технический специалист',
            'moonshine_user_role_id' => 2,
            'remember_token'         => null,
        ]);
    }
}
