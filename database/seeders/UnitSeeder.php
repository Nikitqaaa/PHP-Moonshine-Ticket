<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::insert([
            ['ulid' => (string)Str::ulid(), 'name' => 'Технический отдел'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Монтажный отдел'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Домофонный отдел'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Отдел видеонаблюдения'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Отдел ВОЛС'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Строительный отдел'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Отдел отключений'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Отдел разработки'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Отдел менеджеров'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Отдел операторов'],
        ]);
    }
}
