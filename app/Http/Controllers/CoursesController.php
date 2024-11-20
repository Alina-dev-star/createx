<?php

namespace App\Http\Controllers;

use App\Models\Course; //модель, которая используется для взаимодействия с таблицей courses в базе данных
use Illuminate\Http\Request; //класс для работы с HTTP-запросами
use Inertia\Inertia; //фасад для работы с Inertia.js


class CoursesController extends Controller
{
    public function index()
    {
        $courses = Course::all();

        return Inertia::render('Coursess', [
            'canLogin' => true,
            'canRegister' => true,
            'courses' => $courses,
        ]);

        $courses = Course::with('modules')->get();
    }
}
