<?php

use App\Http\Controllers\Project\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/project', [ProjectController::class, 'store'])->name('new_project');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');

    Route::get('/project/{id}', [ProjectController::class, 'show'])->name('project');
   
});
