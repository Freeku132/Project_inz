<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\TeacherEventController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/laravel', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [EventsController::class, 'index']);
Route::post('/store', [EventsController::class, 'store']);
Route::get('/profile', function (){
    return Inertia::render('Profile');
});

Route::get('/profile/{user}', [UsersController::class, 'show'])->name('profile');

Route::get('profile/{user}/events', [TeacherEventController::class, 'index']);
Route::post('/event/create', [TeacherEventController::class, 'store']);

Route::post('/event/{event}/update', [TeacherEventController::class,'update']);


require __DIR__.'/auth.php';
