<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Default route
Route::get('/', function () {
    return redirect('/tasks');
});

// Auth routes (login, register, etc)
Auth::routes();

// Task routes dengan middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function() {
        return redirect('/tasks');
    });
    
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('tasks.toggle-complete');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
