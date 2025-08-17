<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('user', function () {
    return Inertia::render('user/User');
})->middleware(['auth',EnsureUserIsAdmin::class])->name('user');

Route::get('/project/{id}/task', function () {
    return Inertia::render('project/Tasks');
})->middleware(['auth'])->name('project.task');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
