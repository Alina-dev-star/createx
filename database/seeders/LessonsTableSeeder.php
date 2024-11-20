<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Module;
use Carbon\Carbon;

class LessonsTableSeeder extends Seeder
{
    public function run()
    {
        // Получите все модули
        $modules = Module::all();

        // Для каждого модуля создайте несколько занятий
        foreach ($modules as $module) {
            Lesson::create([
                'module_id' => $module->id,
                'title' => 'Введение в мир лепки',
                'description' => 'На первом уроке мы познакомимся с историей лепки, рассмотрим различные материалы и инструменты, а также научимся основам работы с глиной. Вы создадите свою первую лепную работу - простую фигуру.',
                'order' => 1,
            ]);

            Lesson::create([
                'module_id' => $module->id,
                'title' => 'Формы и объемы',
                'description' => 'Научитесь создавать объемные формы, работать с пластичностью глины и придавать вашим работам динамику. Вы сделаете лепную скульптуру, которая будет передавать движение.',
                'order' => 2,
            ]);

            Lesson::create([
                'module_id' => $module->id,
                'title' => 'Детали и текстуры',
                'description' => 'Изучите техники детализации поверхностей, создания текстур и добавления деталей. Вы создадите лепную работу с реалистичными деталями, например, листьями или цветами.',
               'order' => 3,
            ]);

            Lesson::create([
                'module_id' => $module->id,
                'title' => 'Индивидуальность и выразительность',
                'description' => 'Освойте техники придания индивидуальности вашим работам, используя цвет, форму и детали. Вы создадите лепную скульптуру, которая будет отражать вашу уникальность.',
                'order' => 4,
            ]);

        }
    }
}