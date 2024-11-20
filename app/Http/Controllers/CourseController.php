<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function show($id)
    {
        $course = Course::with('modules')->findOrFail($id);
        $user = Auth::user();

        return Inertia::render('CourseDetails', [
            'course' => $course,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'user' => $user,
        ]);
    }

    public function index()
    {
        $courses = Course::with('modules')->get();

        return response()->json($courses);
    }
}