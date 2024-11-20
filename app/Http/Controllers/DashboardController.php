<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Module;
use App\Models\Application;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function showDashboard()
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $role = $user->role;
    if (!$role) {
        return response()->json(['error' => 'Role not found'], 404);
    }

    $roleName = $role->name;

    // Загружаем модули и их занятия
    $modules = Module::with('lessons')->get();

    $applications = Application::all();
    $students = User::where('role_id', 3)->get(); // Фильтруем пользователей по роли студента
    $teachers = User::where('role_id', 2)->get(); // Фильтруем пользователей по роли преподавателя
    $courses = Course::with('modules.lessons')->get(); // Получаем все курсы с их модулями и уроками

    // Получаем курсы, на которые зарегистрирован текущий пользователь
    $userApplications = Application::where('user_id', $user->id)->with('course.modules.lessons')->get(); 
    $userCourses = $userApplications->pluck('course')->unique();

    // Добавляем статусы курсов в данные
    $userCoursesWithStatus = $userApplications->map(function ($application) {
        return [
            'course' => $application->course,
            'status' => $application->status,
        ];
    });

    // Вывод данных для отладки
    Log::info('User Courses:', $userCourses ? $userCourses->toArray() : []);

    // Получаем все уроки
    $lessons = Lesson::all();

    return Inertia::render('Dashboard', [
        'userRole' => $roleName,
        'modules' => $modules,
        'applications' => $applications,
        'students' => $students,
        'teachers' => $teachers,
        'courses' => $courses,
        'userCourses' => $userCoursesWithStatus,
        'lessons' => $lessons, // Передаем уроки
    ]);
}
}
