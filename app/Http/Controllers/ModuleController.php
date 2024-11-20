<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::with('course')->get();

        // Логирование данных для отладки
        Log::info('Modules with courses:', $modules->toArray());

        return Inertia::render('Admin/Modules', [
            'modules' => $modules,
        ]);
    }

    public function show($id)
    {
        $module = Module::with('course')->findOrFail($id);

        return response()->json($module);
    }

    public function edit($id)
    {
        $module = Module::with('course')->findOrFail($id);

        return response()->json($module);
    }

    public function store(Request $request)
    {
        // Логирование данных для отладки
        Log::info('Создание модуля с данными:', $request->all());

        // Валидация данных
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        // Логирование валидированных данных
        Log::info('Валидированные данные:', $validatedData);

        // Создание нового модуля
        $module = Module::create($validatedData);

        // Логирование созданного модуля
        Log::info('Модуль создан:', $module->toArray());

        // Возвращение ответа
        return response()->json($module, 201);
    }

    public function update(Request $request, $id)
    {
        // Логирование данных для отладки
        Log::info('Обновление модуля с данными:', $request->all());

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        $module = Module::findOrFail($id);
        $module->update($validatedData);

        return response()->json($module);
    }

    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        return response()->json(null, 204);
    }
}
