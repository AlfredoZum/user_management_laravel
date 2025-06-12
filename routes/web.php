<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

// Route::get('user', function () {
//     return Inertia::render('user/Index');
// })->middleware(['auth', 'verified'])->name('user');


Route::get('/users', [UsersController::class, 'index'])->middleware(['auth'])->name('users.index');
Route::get('/users/create', [UsersController::class, 'create'])->middleware(['auth'])->name('users.create');
Route::post('/users', [UsersController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UsersController::class, 'show'])->middleware(['auth'])->name('users.edit');
Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

Route::get('/tasks', [TaskController::class, 'index'])->middleware(['auth'])->name('tasks.index');
Route::get('/tasks/create', [TaskController::class, 'create'])->middleware(['auth'])->name('tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->middleware(['auth'])->name('tasks.edit');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->middleware(['auth'])->name('tasks.update');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->middleware(['auth'])->name('tasks.destroy');

// Route::resource('tasks', TaskController::class)->middleware(['auth']);

// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);

// Route::get('/register', [AuthController::class, 'showRegister']);
// Route::post('/register', [AuthController::class, 'register']);

// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/dashboard', fn () => view('dashboard'))->middleware('auth');