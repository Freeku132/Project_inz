<?php

namespace App\Http\Controllers;

use App\Models\WeekDesignation;
use Illuminate\Http\Request;

class WeekDesignationsController extends Controller
{
    public function store(Request $request)
    {
//        dd($request);

        $week = WeekDesignation::firstOrCreate([
            'week_number' => $request->number,
        ]);

        $week->update([
            'designation' => $request->designation ,
        ]);

    }
}
