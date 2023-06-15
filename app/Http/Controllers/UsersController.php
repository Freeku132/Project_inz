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
        $roleTeacher = Role::query()->where('name', 'teacher')->first(); // Get teacher role
        $roleStudent = Role::query()->where('name', 'student')->first(); // Get student role

        // Query selecting users whose role is teacher or student,
        // when request has a search field, the query selects only users whose name or email matches the search value
        // The returned object is paginated using paginate()
        $users = User::query()->whereIn('role_id' , [$roleTeacher->id, $roleStudent->id])
            ->when($request->input('search'), function ($query, $search){
                $query->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%');
            })
            ->paginate(10)
            ->withQueryString();

        // Variable used in the search field
        $filters = $request->only(['search']);

        return Inertia::render('Admin/Users',[
            'users'    => $users,
            'filters'  => $filters,
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success_message', 'user_delete');
    }

    public function update(User $user, Request $request)
    {

        $attributes = $request->validate([
            'id'       => 'required',
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'min:7|nullable',
            'role_id'  => 'required'
        ]);


        if($request['password']){
            $user->update([
                'name'      => $attributes['name'],
                'email'     => $attributes['email'],
                'role_id'   => $attributes['role_id'],
                'password'  => Hash::make($attributes['password'])
            ]);
        } else{
            $user->update([
                'name'      => $attributes['name'],
                'email'     => $attributes['email'],
                'role_id'   => $attributes['role_id'],
            ]);
        }



        return back()->with('success_message', 'user_edit');

    }

    public function store(Request $request)
    {

        $request->validate([
            'name'      => 'required|string|max:50',
            'email'     => 'required|string|email|max:30|unique:users',
            'password'  => 'min:7|required',
            'role_id'   => 'required',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role_id'   => $request->role_id,
            'password'  => Hash::make($request->password),
        ]);

         return redirect()->back()->with('success_message', 'adding_completed');
    }
}
