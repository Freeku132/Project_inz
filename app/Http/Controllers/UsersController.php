<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function show(User $user, Request $request)
    {
        if ($request->currentDate) {
            $currentDate = $request->currentDate;
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        } else {
            $currentDate = \Illuminate\Support\Carbon::now()->format('Y-m-d h:i');
            $startDate = \Illuminate\Support\Carbon::now()->subDays(3)->format('Y-m-d h:i');
            $endDate = \Illuminate\Support\Carbon::now()->addDays(3)->format('Y-m-d h:i');

        }

        $builder = \App\Models\Event::query()
            ->where('start', '>', $startDate)
            ->where('end', '<', $endDate)
            ->where('teacher_id', $user->id)
            ->get();

        if (Auth::user()) {
            $can = Auth::user()->can('update', [User::class, $user]);
        } else{
            $can = false;
        }

            $view = Auth::user()->can('viewAny',  $user);


        return Inertia::render('TeacherProfile',[
            'user' => $user,
            'events' => $builder,
            'currentDate' => $currentDate,
            'can' => [
                'createEvent' => $can,
                'viewAny' => $user->can('viewAny', $user)
            ]

        ]);
    }
}
