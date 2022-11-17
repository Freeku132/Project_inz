<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherEventController;
use App\Http\Controllers\UsersController;
use App\Models\User;
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

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');


Route::get('/teachers',[TeacherController::class, 'index'])->name('teachers.index');
Route::get('/teachers/{user}', [TeacherController::class, 'show'])->can('publicProfile', 'user')->name('teachers.show');

Route::get('/profile/{user}', [UsersController::class, 'show'])->can('studentProfile', 'user')->name('student-profile'); // DODAWAĆ?


Route::get('/teachers/{user}/events', [TeacherEventController::class, 'index'])->can('owner','user')->name('event-teacher.index');
//Route::get('/teachers/{user}/events/{event}', [TeacherEventController::class, 'show'])->can('owner','user')->name('event-teacher.show');
Route::post('/teachers/event/store', [TeacherEventController::class, 'store'])->can('teacher', User::class);
Route::patch('/teachers/{user}/events/{event}/update', [TeacherEventController::class,'update'])->can('teacher', User::class)->name('events.update');


Route::post('/event/store', [EventsController::class, 'store']);


Route::get('/profile', function (){
    return Inertia::render('Profile');
});
require __DIR__.'/auth.php';
