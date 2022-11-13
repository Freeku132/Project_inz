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
    public function index(Request $request)
    {
        $role = Role::query()->where('name', 'teacher')->first();

        $users = User::query()->where('role_id' , $role->id)
            ->when($request->input('search'), function ($query, $search){
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->paginate(5)
            ->withQueryString();

        $filters = $request->only(['search']);


        return Inertia::render('Teacher/Index', [
            'users' => $users,
            'filters' => $filters,
        ]);
    }


    public function show(User $user, Request $request)
    {
        if ($request->currentDate) {
            $currentDate = $request->currentDate;
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        } else {
            $currentDate = Carbon::now()->format('Y-m-d h:i');
            $startDate = Carbon::now()->startOfWeek()->format('Y-m-d h:i');
            $endDate = Carbon::now()->endOfWeek()->format('Y-m-d h:i');
        }


        $events = EventResource::collection(Event::query()
            ->where('start', '>', $startDate)
            ->where('end', '<', $endDate)
            ->where('teacher_id', $user->id)
            ->with('eventClass')
            ->get());


        if (Auth::user()) {
            $can = Auth::user()->can('update', [User::class, $user]);
        } else{
            $can = false;
        }


        return Inertia::render('Teacher/Show',[
            'user'        => $user,
            'events'      => $events,
            'currentDate' => $currentDate,
            'can' => [
                'createEvent' => $can,
                'viewAny'     => $user->can('viewAny', $user)
            ]

        ]);
    }
}
