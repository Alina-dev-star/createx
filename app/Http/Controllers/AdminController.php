<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Application;
use App\Models\Schedule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Исправленное пространство имен
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{

    public function index()
    {
        $teachers = User::whereHas('role', function ($query) {
            $query->where('name', 'teacher');
        })->get();

        $schedule = Schedule::all();

        return Inertia::render('Admin/AdminSchedule', [
            'teachers' => $teachers,
            'schedule' => $schedule,
        ]);
    }

    // Методы для работы с курсами
    public function coursestore(Request $request)
    {
        // Логирование входящих данных запроса
        Log::info($request->all());

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'what_they_will_learn' => 'required|string', // Добавляем новое поле
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        } else {
            return response()->json(['error' => 'Image not found'], 400);
        }

        try {
            $course = Course::create([
                'title' => $request->title,
                'description' => $request->description,
                'what_they_will_learn' => $request->what_they_will_learn, // Добавляем новое поле
                'price' => $request->price,
                'category' => $request->category,
                'duration' => $request->duration,
                'image' => $imagePath,
            ]);

            return response()->json($course, 201);
        } catch (\Exception $e) {
            Log::error('Error creating course: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course);
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course);
    }

public function update(Request $request, $id)
{
try {
$course = Course::findOrFail($id);

Log::info('Request data:', $request->all()); // Логирование данных запроса

$validatedData = $request->validate([
'title' => 'required|string|max:255',
'description' => 'required|string',
'what_they_will_learn' => 'required|string', // Добавляем новое поле
'price' => 'required|numeric',
'category' => 'required|string|max:255',
'duration' => 'required|string|max:255',
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
]);

// Обработка загрузки файла, если необходимо
if ($request->hasFile('image')) {
if ($course->image) {
Storage::disk('public')->delete($course->image);
}
$imagePath = $request->file('image')->store('courses', 'public');
$validatedData['image'] = $imagePath;
}

$course->update($validatedData);

return response()->json($course, 200); // Или редирект, в зависимости от вашего приложения

} catch (ModelNotFoundException $e) {
Log::error("Model not found: {$e->getMessage()}");
return response()->json(['error' => 'Model not found'], 404);
} catch (ValidationException $e) {
return response()->json(['errors' => $e->errors()], 422);
} catch (\Exception $e) {
Log::error("Error updating model: {$e->getMessage()}");
return response()->json(['error' => 'Error updating model'], 500);
}
}
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }

    // Методы для работы со студентами
    public function studentsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => 3,
        ]);

        return response()->json($user, 201);
    }

    public function studentsIndex()
    {
        $students = User::all();

        $students->each(function ($student) {
            $student->password = '********';
        });

        return Inertia::render('Admin/Students', ['students' => $students]);
    }

    public function studentsShow($id)
    {
        $student = User::findOrFail($id);
        return response()->json($student);
    }

    public function studentsEdit($id)
    {
        $student = User::findOrFail($id);
        return response()->json($student);
    }

    public function studentsUpdate(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'password' => 'nullable|string|min:6',
        ]);

        $studentData = $request->except('password');

        if ($request->filled('password')) {
            $studentData['password'] = bcrypt($request->input('password'));
        }

        $student->update($studentData);

        return response()->json($student);
    }

    public function studentsDestroy($id)
    {
        $student = User::findOrFail($id);
        $student->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }

    // Методы для работы с преподавателями
    public function teachersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => 2,
            'course_id' => $request->course_id,
        ]);

        return response()->json($user, 201);
    }

    public function teachersIndex()
    {
        $teachers = User::with('course')->whereHas('role', function ($query) {
            $query->where('name', 'teacher');
        })->get();

        $teachers->each(function ($teacher) {
            $teacher->password = '********';
        });

        return Inertia::render('Admin/Teachers', ['teachers' => $teachers]);
    }

    public function teachersShow($id)
    {
        $teacher = User::findOrFail($id);
        return response()->json($teacher);
    }

    public function teachersEdit($id)
    {
        $teacher = User::findOrFail($id);
        return response()->json($teacher);
    }

    public function teachersUpdate(Request $request, $id)
    {
        $teacher = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'password' => 'nullable|string|min:6',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $teacher->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $teacher->password,
            'course_id' => $request->course_id,
        ]);

        return response()->json($teacher);
    }

    public function teachersDestroy($id)
    {
        $teacher = User::findOrFail($id);
        $teacher->delete();
        return response()->json(['message' => 'Teacher deleted successfully']);
    }

    // Методы для работы с заявками
    public function applicationsIndex()
    {
        $applications = Application::all();
        return Inertia::render('Admin/Applications', ['applications' => $applications]);
    }

    public function applicationsShow($id)
    {
        $application = Application::findOrFail($id);
        return response()->json($application);
    }

    public function applicationsEdit($id)
    {
        $application = Application::findOrFail($id);
        return response()->json($application);
    }

    public function applicationsUpdate(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->update($request->all());
        return response()->json($application);
    }

    public function applicationsDestroy($id)
    {
        $application = Application::findOrFail($id);
        $application->delete();
        return response()->json(['message' => 'Application deleted successfully']);
    }
}
