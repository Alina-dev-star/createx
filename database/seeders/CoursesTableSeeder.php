<?php

namespace Database\Seeders;

use App\Models\Course; //модель, для которой создается сидер
use Illuminate\Database\Seeder; //базовый класс для сидеров

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        Course::factory()->count(5)->create();
        // Course::factory() — создает экземпляр фабрики для модели Course
        // count(10) — указывает, что нужно создать 10 записей
        // create() — создает и сохраняет записи в базе данных
    }
}
