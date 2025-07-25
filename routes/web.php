<?php

use App\Http\Requests\TaskRequest;
use \App\Models\Task;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//redirect function
Route::get('/', function() {
    return redirect()->route('tasks.index');
});

//main page
Route::get('/tasks', function () {
    return view('index',  [
        'tasks' => Task::latest()->paginate(10)
    ]);
})->name('tasks.index');

// create forms
Route::view('/tasks/create', 'create')
    ->name('tasks.create');

// edit form
Route::get('/tasks/{task}/edit', function (Task $task) {
    return view('edit', [
        'task' => $task
    ]);
})->name('tasks.edit');

// single task page

Route::get('/tasks/{task}', function (Task $task) {
    return view('show', [
        'task' => $task
    ]);
})->name('tasks.show');

Route::post('/tasks', function (TaskRequest $request) {   
    $task = Task::create($request ->validated());

    return redirect()->route('tasks.show', ['task' => $task->id])
    ->with('success', 'Task created successfully!');
})->name('tasks.store');

//edit form
Route::put('/tasks/{task}', function (Task $task, TaskRequest $request) {
    $task->update($request ->validated());

    return redirect()->route('tasks.show', ['task' => $task->id])
        ->with('success', 'Task updated successfully!');
})->name('tasks.update');

Route::delete('/tasks/{task}', function (Task $task) {
    $task->delete();

    return redirect()->route('tasks.index')
        ->with('success', 'Task deleted successfully');
})->name('tasks.destroy');

// toggle complete button
Route::put('tasks/{task}/toggle-complete', function(Task $task) {
    $task->toggleComplete();

    return redirect()->back()->with('success', 'Task updated successfully!');
})->name('tasks.toggle-complete');
