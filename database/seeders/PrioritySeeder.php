<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            ['name' => 'Критический', 'slug' => 'critical'],
            ['name' => 'Высокий', 'slug' => 'high'],
            ['name' => 'Средний', 'slug' => 'medium'],
            ['name' => 'Низкий', 'slug' => 'low'],
            ['name' => 'Улучшение', 'slug' => 'enhancement'],
        ];

        foreach ($priorities as $priority) {
            Priority::create([
                'ulid' => (string)Str::ulid(),
                'slug' => $priority['slug'],
                'name' => $priority['name'],
            ]);
        }
    }
}
