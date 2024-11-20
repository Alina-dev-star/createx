<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Lesson;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function show(Request $request)
    {
        $timeIntervals = [
            '08:00-10:00',
            '10:00-12:00',
            '12:00-14:00',
            '14:00-16:00',
            '16:00-18:00',
            '18:00-20:00',
        ];

        $user = Auth::user();
        $role = $user->role->name;

        // Получение расписания на неделю
        $schedules = Schedule::where('user_id', $user->id)
            ->get()
            ->groupBy('date')
            ->map(function ($schedules) use ($timeIntervals) {
                $daySchedule = array_fill_keys($timeIntervals, null);
                foreach ($schedules as $schedule) {
                    $daySchedule[$schedule->time] = 'Запланировано';
                }
                return $daySchedule;
            })
            ->toArray();

        // Добавьте это для отладки
        if (empty($schedules)) {
            Log::info('No schedules found for user ID: ' . $user->id);
        } else {
            Log::info('Schedules found: ', $schedules);
        }

        return Inertia::render('Schedule', [
            'userRole' => $role,
            'timeIntervals' => $timeIntervals,
            'schedules' => $schedules,
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Received request to store schedule:', $request->all());

        $validated = $request->validate([
            'date' => 'required|date',
            'time_interval' => 'required|string|in:08:00-10:00,10:00-12:00,12:00-14:00,14:00-16:00,16:00-18:00,18:00-20:00',
            'course_id' => 'required|integer',
            'module_id' => 'required|integer',
            'lesson_id' => 'required|integer',
        ]);

        // Получаем текущего пользователя
        $user = Auth::user();

        // Добавляем user_id и time в валидированные данные
        $validated['user_id'] = Auth::id();
        $validated['time'] = $validated['time_interval'];

        // Создаем запись в базе данных
        Schedule::create($validated);

        return response()->json(['message' => 'Расписание успешно добавлено'], 201);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $schedule->update([
            'date' => $validated['date'],
            'time' => $validated['time'],
        ]);

        return response()->json(['message' => 'Расписание успешно обновлено'], 200);
    }

    public function markCompleted(Schedule $schedule)
    {
        $schedule->update(['completed' => true]);

        // Отметить посещение
        Attendance::updateOrCreate(
            ['user_id' => $schedule->user_id, 'lesson_id' => $schedule->lesson_id],
            ['attended' => true]
        );

        return response()->json(['message' => 'Урок отмечен как пройденный'], 200);
    }

    public function index()
    {
        $schedules = Schedule::with('user', 'lesson')->get();
        return response()->json(['schedules' => $schedules], 200);
    }
}
