<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function index(Module $module)
    {
        $lessons = $module->lessons()->with('module.course')->get();
        return response()->json($lessons);
    }

    public function store(Request $request, Module $module)
    {
        Log::info('Creating lesson for module:', ['module_id' => $module->id, 'request' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'module_id' => 'required|exists:modules,id', // Добавляем валидацию для module_id
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $lesson = $module->lessons()->create($request->all());

        return response()->json($lesson, 201);
    }

    public function edit(Module $module, Lesson $lesson)
    {
        $module->load('course'); // Загружаем связанный курс
        return response()->json([
            'lesson' => $lesson,
            'module' => $module,
            'course' => $module->course,
        ]);
    }

    public function update(Request $request, Module $module, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'module_id' => 'required|exists:modules,id', // Добавляем валидацию для module_id
        ]);

        $lesson->update($request->all());

        return response()->json($lesson);
    }

    public function destroy(Module $module, Lesson $lesson)
    {
        $lesson->delete();

        return response()->json(null, 204);
    }
}
