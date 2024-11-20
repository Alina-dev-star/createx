<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'agree' => 'required|boolean',
            'course_id' => 'required|integer|exists:courses,id', // Corrected 'courses' to 'courses'
        ]);

        $user = Auth::user();

        // Debugging: Log the user and course_id
        Log::info('User:', ['user' => $user]);
        Log::info('Course ID:', ['course_id' => $validatedData['course_id']]);

        // Check if user is authenticated and has an approved application on another course
        if ($user) {
            $hasApprovedApplication = Application::where('user_id', $user->id)
                ->where('status', 'approved')
                ->where('course_id', '!=', $validatedData['course_id'])
                ->exists();

            if ($hasApprovedApplication) {
                $validatedData['status'] = 'rejected';
            }
        }

        // Debugging: Log the validated data
        Log::info('Validated Data:', $validatedData);

        $application = Application::create([
            'user_id' => $user ? $user->id : null,
            'course_id' => $validatedData['course_id'],
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'agree' => $validatedData['agree'],
            'status' => $validatedData['status'] ?? 'pending',
        ]);

        return response()->json($application, 201);
    }

    public function check(Request $request)
    {
        $user = Auth::user();

        $hasApprovedApplication = Application::where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();

        return response()->json(['hasApprovedApplication' => $hasApprovedApplication]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'agree' => 'required|boolean',
            'status' => 'required|string|max:255',
            'course_id' => 'required|integer',
        ]);

        $application = Application::findOrFail($id);
        $application->update($validatedData);

        return response()->json($application);
    }

    public function edit($id)
    {
        $application = Application::with('course')->findOrFail($id);
        return response()->json($application);
    }

    public function destroy($id)
    {
        $application = Application::findOrFail($id);
        $application->delete();

        return response()->json(null, 204);
    }

    public function show($id)
    {
        $application = Application::with('course')->findOrFail($id);
        return response()->json($application);
    }

    public function index()
    {
        $applications = Application::with('course')->get();
        return response()->json($applications);
    }

    public function approve(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        // Проверка, что заявка существует и её статус не "Одобрено"
        if ($application->status === 'approved') {
            return response()->json(['message' => 'Application is already approved'], 400);
        }

        // Обновление статуса заявки на "Одобрено"
        $application->status = 'approved';
        $application->save();

        return response()->json(['message' => 'Application approved'], 200);
    }
}