<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $roleTeacher = Role::query()->where('name', 'teacher')->first();
        $roleStudent = Role::query()->where('name', 'student')->first();

        $users = User::query()->whereIn('role_id' , [$roleTeacher->id, $roleStudent->id])
            ->when($request->input('search'), function ($query, $search){
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->paginate(5)
            ->withQueryString();

        $filters = $request->only(['search']);

        return Inertia::render('Admin/User/Index',[
            'users' => $users,
            'filters' => $filters,
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success_message', 'UserDelete');
    }

    public function update(User $user, Request $request)
    {
        $attributes = $request->validate([
            'user' => 'required',
            'user.id' => 'required',
            'user.name' => 'required',
            'user.email' => 'required|email',
            'user.password' => 'min_digits:7|nullable',
            'user.role_id' => 'required'
        ]);

        $attributes = $attributes['user'];

        $user->update([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'role_id' => $attributes['role_id'],
            'password' => Hash::make($attributes['password'])
            ]);

        return back()->with('success_message', 'UserEdit');

    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'role_id' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

    }
}
