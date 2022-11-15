<?php

use App\Http\Controllers\EventsController;
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


Route::get('/teachers',[UsersController::class, 'index'])->name('teachers.index');
Route::get('/profile/{user}', [UsersController::class, 'show'])->name('profile');



Route::post('/store', [EventsController::class, 'store']);



Route::get('/profile/{user}/events', [TeacherEventController::class, 'index'])->can('update','user')->name('event-teacher.index');
Route::patch('/profile/{user}/events/{event}/update', [TeacherEventController::class,'update'])->can('viewAny', User::class)->name('events.update');
Route::post('event/create', [TeacherEventController::class, 'store'])->can('viewAny', User::class);




Route::get('/profile', function (){
    return Inertia::render('Profile');
});
require __DIR__.'/auth.php';
