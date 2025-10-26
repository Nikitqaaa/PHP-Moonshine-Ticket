<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Support\Str;
use App\Models\ProblemCategory;
use Illuminate\Database\Seeder;

class ProblemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProblemCategory::insert([
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Технический отдел')->first()->ulid,
                'name'      => 'Интернет',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Технический отдел')->first()->ulid,
                'name'      => 'Кабельное',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Технический отдел')->first()->ulid,
                'name'      => 'Телефония',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Технический отдел')->first()->ulid,
                'name'      => 'Прочее',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Монтажный отдел')->first()->ulid,
                'name'      => 'Монтажные работы',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Домофонный отдел')->first()->ulid,
                'name'      => 'Домофон',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Отдел видеонаблюдения')->first()->ulid,
                'name'      => 'Видеонаблюдение',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Отдел ВОЛС')->first()->ulid,
                'name'      => 'Оптика',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Строительный отдел')->first()->ulid,
                'name'      => 'Стройка',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Отдел отключений')->first()->ulid,
                'name'      => 'Отключить КТВ',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Отдел отключений')->first()->ulid,
                'name'      => 'Переезд',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Отдел менеджеров')->first()->ulid,
                'name'      => 'Учетка',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Отдел менеджеров')->first()->ulid,
                'name'      => 'Скидка',
            ],
            [
                'ulid'      => Str::ulid(),
                'unit_ulid' => Unit::where('name', 'Отдел операторов')->first()->ulid,
                'name'      => 'Прозвонить',
            ],
        ]);
    }
}
