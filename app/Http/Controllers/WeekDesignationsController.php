<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\WeekDesignation;
use Illuminate\Http\Request;

class WeekDesignationsController extends Controller
{
    public function store(Request $request)
    {
//        dd($request);
        $semester = Semester::query()->where('active', 1)->first();

        $week = WeekDesignation::firstOrCreate([
            'week_number' => $request->number,
            'semester_id' => $semester->id
        ]);

        $week->update([
            'designation' => $request->designation ,
        ]);

    }
}
