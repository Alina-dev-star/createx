<?php

namespace Database\Factories;

use App\Models\Course; //модель, для которой создается фабрика
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory; //базовый класс для фабрик моделей

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            //объект $this->faker, который предоставляет методы для генерации случайных данных
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'what_they_will_learn' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 1000, 20000),
            'duration' => $this->faker->numberBetween(1, 12) . ' недель',
            'category' => $this->faker->word,
            'image' => $this->faker->imageUrl(),
        ];
    }
}
