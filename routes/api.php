<?php

use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\TaskController;

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {

    /*User Endpoints */
    Route::get('/users', [UserController::class, 'index'])->name('users');

    Route::post('/user', [UserController::class, 'store'])->name('new_user');

    Route::put('/user/{id}', [UserController::class, 'update'])->name('update_user');

    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('delete_user');


    /*Project Endpoints */
    Route::post('/project', [ProjectController::class, 'store'])->name('new_project');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');

    Route::get('/projects/user/{id}', [ProjectController::class, 'byUser'])->name('projects.by_user');

    Route::get('/project/{id}', [ProjectController::class, 'show'])->name('project');

    Route::get('/project/{project}/users', [ProjectController::class, 'users'])->name('project.users');

    Route::put('/project/{id}', [ProjectController::class, 'update'])->name('update_project');

    Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('delete_project');

    
    /*Tasks Endpoints */

    Route::get('/project/{id}/tasks', action: [TaskController::class, 'index'])->name('project.tasks');

    Route::post('/project/{id}/task', action: [TaskController::class, 'store'])->name('project.new_task');

    Route::put('/task/{id}', [TaskController::class, 'update'])->name('update_task');

    Route::put('/task/{id}/status', [TaskController::class, 'updateStatus'])->name('update_task.status');

    Route::put('/task/{id}/assign-user', [TaskController::class, 'assignUser'])->name('update_task.assign-user');

    Route::delete('/task/{id}', [TaskController::class, 'destroy'])->name('delete_task');
});
