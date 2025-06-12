<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $query = Task::with('user');

        if (!$user->can('view all tasks')) {
            $query->where('user_id', $user->id);
        }

        $tasks = $query->get();

        $canCreateTasks = $user->can('create tasks');
        $canUpdateTasks = $user->can('update tasks');
        $canDeleteTasks = $user->can('delete tasks');

        // dd($tasks->toArray()[0]);
        // dd($canCreateTasks);

        return Inertia::render('tasks/Index', [
            'tasks' => $tasks->toArray(),
            'can' => [
                'createTasks' => $canCreateTasks,
                'updateTasks' => $canUpdateTasks,
                'deleteTasks' => $canDeleteTasks,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user->can('create tasks')) {
            abort(403, 'No tienes permiso para crear tareas.');
        }

        $users = User::select('id', 'name')->get();

        return Inertia::render('tasks/CreateTask', [
            'users' => $users->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = Auth::user();
        if (!$user->can('create tasks')) {
            abort(403, 'No tienes permiso para crear tareas.');
        }

        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'user_id' => ['required', Rule::exists('users', 'id')],
        ]);
        try {

            Task::create($validatedData);

            return redirect()->route('tasks.index')
                ->with('success', 'Tarea creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear una tarea. Intenta de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user = Auth::user();
        $task = Task::findOrFail($id);

        if (!$user->can('update tasks') || (!$user->can('view all tasks') && $task->user_id !== $user->id)) {
            abort(403, 'No tienes permiso para editar esta tarea.');
        }

        $users = User::select('id', 'name')->get();

        return Inertia::render('tasks/UpdateTask', [
            'task' => $task->toArray(),
            'users' => $users->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $task = Task::findOrFail($id);

        $user = Auth::user();

        if (!$user->can('update tasks') || (!$user->can('view all tasks') && $task->user_id !== $user->id)) {
            abort(403, 'No tienes permiso para actualizar esta tarea.');
        }

        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'user_id' => ['required', Rule::exists('users', 'id')],
        ]);

        try {

            $task->update($validatedData);

            return redirect()->route('tasks.index')
                ->with('success', 'Tarea actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar una tarea. Intenta de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $task = Task::findOrFail($id);

        $user = Auth::user();

        if (!$user->can('delete tasks') || (!$user->can('view all tasks') && $task->user_id !== $user->id)) {
            abort(403, 'No tienes permiso para eliminar esta tarea.');
        }
        try {

            $task->delete();
            return redirect()->route('tasks.index')->with('success', 'Tarea eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo eliminar la tarea.');
        }
    }
}
