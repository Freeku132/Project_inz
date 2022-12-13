<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\FreeDaysController;
use App\Http\Controllers\SemestersController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\TeacherEventsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WeekDesignationsController;
use App\Models\Role;
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

Route::middleware('can:admin, App\Models\User')->group( function (){

    Route::get('adminPanel', function () {
        return Inertia::render('Admin/Panel');
    })->name('adminPanel');

    Route::get('adminPanel/users',[UsersController::class, 'index'])->name('adminPanel.users');
    Route::delete('adminPanel/users/{user}/delete',[UsersController::class, 'destroy'])->name('adminPanel.users.delete');
    Route::patch('adminPanel/users/{user}/update',[UsersController::class, 'update'])->name('adminPanel.users.update');
    Route::post('adminPanel/users',[UsersController::class, 'store'])->name('adminPanel.users.store');

    Route::get('adminPanel/events', function (){
        return Inertia::render('Admin/Events');
    })->name('adminPanel.events');


    Route::get('adminPanel/semester', [SemestersController::class, 'index'])->name('adminPanel.semester');

    Route::post('adminPanel/semester',[SemestersController::class, 'store'])->name('adminPanel.semester.store');
    Route::post('adminPanel/semester/weekdesignations', [WeekDesignationsController::class, 'store'])->name('adminPanel.semester.weekdesignations');


    Route::post('adminPanel/semester/freedays', [FreeDaysController::class, 'store'])->name('adminPanel.semester.freedays');
    Route::delete('adminPanel/semester/freedays/{freeDay}', [FreeDaysController::class, 'destroy'])->name('adminPanel.semester.freedays.delete');
});



Route::get('/teachers',[TeachersController::class, 'index'])->name('teachers.index');
Route::get('/teachers/{user}', [TeachersController::class, 'show'])->can('publicProfile', 'user')->name('teachers.show');

Route::get('/profile/{user}', [UsersController::class, 'show'])->can('studentProfile', 'user')->name('student-profile'); // DODAWAĆ?


Route::get('/teachers/{user}/events', [TeacherEventsController::class, 'index'])->can('owner','user')->name('event-teacher.index');
//Route::get('/teachers/{user}/events/{event}', [TeacherEventsController::class, 'show'])->can('owner','user')->name('event-teacher.show');
Route::post('/teachers/event/store', [TeacherEventsController::class, 'store'])->can('teacher', User::class);
Route::patch('/teachers/{user}/events/{event}/update', [TeacherEventsController::class,'update'])->can('teacher', User::class)->name('events.update');


Route::post('/event/store', [EventsController::class, 'store']);


Route::get('/profile', function (){
    return Inertia::render('Profile');
});
require __DIR__.'/auth.php';
