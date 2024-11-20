<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModulesTableSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            [
                'course_id' => 1,
                'title' => 'Модуль 1',
                'description' => 'Описание модуля 1',
                'order' => 1,
            ],
            [
                'course_id' => 1,
                'title' => 'Модуль 2',
                'description' => 'Описание модуля 2',
                'order' => 2,
            ],
            [
                'course_id' => 2,
                'title' => 'Модуль 3',
                'description' => 'Описание модуля 1',
                'order' => 3,
            ],
            [
                'course_id' => 2,
                'title' => 'Модуль 4',
                'description' => 'Описание модуля 2',
                'order' => 4,
            ],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}