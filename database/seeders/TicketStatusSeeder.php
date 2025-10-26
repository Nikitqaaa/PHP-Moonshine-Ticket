<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\TicketStatus;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketStatus::insert([
            ['ulid' => (string)Str::ulid(), 'name' => 'Новая', 'slug' => 'new'],
            ['ulid' => (string)Str::ulid(), 'name' => 'В работе', 'slug' => 'in_progress'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Выполнена', 'slug' => 'completed'],
            ['ulid' => (string)Str::ulid(), 'name' => 'Закрыта', 'slug' => 'closed'],
        ]);
    }
}
