<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UsersController extends Controller
{
//    public function index(Request $request)
//    {
//        $role = Role::query()->where('name', 'teacher')->first();
//
//        $users = User::query()->where('role_id' , $role->id)
//            ->when($request->input('search'), function ($query, $search){
//                $query->where('name', 'like', '%'.$search.'%');
//            })
//            ->paginate(5)
//            ->withQueryString();
//
//        $filters = $request->only(['search']);
//
//
//        return Inertia::render('Teacher/Index', [
//            'users' => $users,
//            'filters' => $filters,
//        ]);
//    }
//
//
    public function show(User $user, Request $request)
    {

        return Inertia::render('Teacher/Show',[
            'user'        => $user,
        ]);
    }
}
