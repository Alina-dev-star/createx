<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use Illuminate\Foundation\Application;
use App\Models\Course;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

//----------------------------------------------------------------------------------------------------------------------------------------------- Главная страница
Route::get('/', function () {
    $courses = Course::limit(6)->get();
    $teachers = User::whereHas('role', function ($query) {
        $query->where('name', 'teacher');
    })->get();

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'courses' => $courses,
        'teachers' => $teachers,
    ]);
});

//----------------------------------------------------------------------------------------------------------------------------------------------- Панель управления
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->middleware(['auth', 'verified'])->name('dashboard');

//----------------------------------------------------------------------------------------------------------------------------------------------- Профиль пользователя
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//----------------------------------------------------------------------------------------------------------------------------------------------- Курсы
Route::get('/coursess', [CoursesController::class, 'index'])->name('coursess');
Route::get('/coursess/{id}', [CourseController::class, 'show'])->name('coursess.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{module}/lessons', [LessonController::class, 'index'])->name('lessons.index');
});

//----------------------------------------------------------------------------------------------------------------------------------------------- Контакты и О нас
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/about', [AboutController::class, 'index'])->name('about');

//----------------------------------------------------------------------------------------------------------------------------------------------- Расписание
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/schedule', [ScheduleController::class, 'show'])->name('schedule');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::put('/schedule/{schedule}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::put('/schedule/{schedule}/mark-completed', [ScheduleController::class, 'markCompleted'])->name('schedule.markCompleted');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
});

//----------------------------------------------------------------------------------------------------------------------------------------------- Преподаватели
Route::get('/teachers', [TeacherController::class, 'index']);

//----------------------------------------------------------------------------------------------------------------------------------------------- Административная панель
Route::middleware(['auth', 'verified'])->group(function () {
    // Курсы
    Route::get('/admin/courses', [AdminController::class, 'index'])->name('admin.courses.index');
    Route::get('/admin/courses/{id}', [AdminController::class, 'show'])->name('admin.courses.show');
    Route::get('/admin/courses/{id}/edit', [AdminController::class, 'edit'])->name('admin.courses.edit');
    Route::put('/admin/courses/{id}', [AdminController::class, 'update'])->name('admin.courses.update');
    Route::delete('/admin/courses/{id}', [AdminController::class, 'destroy'])->name('admin.courses.destroy');
    Route::post('/admin/courses', [AdminController::class, 'coursestore'])->name('admin.courses.store');

    // Студенты
    Route::get('/admin/students', [AdminController::class, 'studentsIndex'])->name('admin.students.index');
    Route::get('/admin/students/{id}', [AdminController::class, 'studentsShow'])->name('admin.students.show');
    Route::get('/admin/students/{id}/edit', [AdminController::class, 'studentsEdit'])->name('admin.students.edit');
    Route::put('/admin/students/{id}', [AdminController::class, 'studentsUpdate'])->name('admin.students.update');
    Route::post('/admin/students', [AdminController::class, 'studentsStore'])->name('admin.students.store');
    Route::delete('/admin/students/{id}', [AdminController::class, 'studentsDestroy'])->name('admin.students.destroy');

    // Преподаватели
    Route::get('/admin/teachers', [AdminController::class, 'teachersIndex'])->name('admin.teachers.index');
    Route::post('/admin/teachers', [AdminController::class, 'teachersStore'])->name('admin.teachers.store');
    Route::get('/admin/teachers/{id}', [AdminController::class, 'teachersShow'])->name('admin.teachers.show');
    Route::get('/admin/teachers/{id}/edit', [AdminController::class, 'teachersEdit'])->name('admin.teachers.edit');
    Route::put('/admin/teachers/{id}', [AdminController::class, 'teachersUpdate'])->name('admin.teachers.update');
    Route::delete('/admin/teachers/{id}', [AdminController::class, 'teachersDestroy'])->name('admin.teachers.destroy');

    // Заявки
    Route::get('/admin/applications', [AdminController::class, 'applicationsIndex'])->name('admin.applications.index');
    Route::get('/admin/applications/{id}', [AdminController::class, 'applicationsShow'])->name('admin.applications.show');
    Route::get('/admin/applications/{id}/edit', [AdminController::class, 'applicationsEdit'])->name('admin.applications.edit');
    Route::put('/admin/applications/{id}', [AdminController::class, 'applicationsUpdate'])->name('admin.applications.update');
    Route::delete('/admin/applications/{id}', [AdminController::class, 'applicationsDestroy'])->name('admin.applications.destroy');

    // Модули
    Route::get('/admin/modules', [ModuleController::class, 'index'])->name('admin.modules.index');
    Route::get('/admin/modules/{id}', [ModuleController::class, 'show'])->name('admin.modules.show');
    Route::get('/admin/modules/{id}/edit', [ModuleController::class, 'edit'])->name('admin.modules.edit');
    Route::post('/admin/modules', [ModuleController::class, 'store'])->name('admin.modules.store');
    Route::put('/admin/modules/{id}', [ModuleController::class, 'update'])->name('admin.modules.update');
    Route::delete('/admin/modules/{id}', [ModuleController::class, 'destroy'])->name('admin.modules.destroy');

    // Уроки
    Route::get('/modules/{module}/lessons', [LessonController::class, 'index'])->name('admin.lessons.index');
    Route::post('/modules/{module}/lessons', [LessonController::class, 'store'])->name('admin.lessons.store');
    Route::get('/modules/{module}/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('admin.lessons.edit');
    Route::put('/modules/{module}/lessons/{lesson}', [LessonController::class, 'update'])->name('admin.lessons.update');
    Route::delete('/modules/{module}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('admin.lessons.destroy');
});

//----------------------------------------------------------------------------------------------------------------------------------------------- Заявки
Route::post('/applications/{application}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
Route::post('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
Route::get('/admin/applications', [ApplicationController::class, 'index'])->name('admin.applications.index');
Route::post('/applications', [ApplicationController::class, 'store']);
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/applications/check', function () {
        $user = Auth::user();
        Log::info('User:', ['user' => $user]);
        return response()->json(['user' => $user]);
    });
});
Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');

require __DIR__ . '/auth.php';