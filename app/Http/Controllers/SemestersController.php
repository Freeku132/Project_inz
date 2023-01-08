<?php

namespace App\Http\Controllers;

use App\Models\FreeDay;
use App\Models\Semester;
use App\Models\WeekDesignation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SemestersController extends Controller
{
    public function index()
    {
        $semester = Semester::query()->where('active', 1)->first();
        $freeDays = FreeDay::query()->get();
        $weeks = WeekDesignation::query()->where('semester_id', $semester->id)->get();


        return Inertia::render('Admin/Semester', [
            'startDate' => '2022-10-01',
            'endDate' => '2023-02-20',
            'weeks' => $weeks,
            'freeDays' => $freeDays,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'years' => 'required',
            'startDate' => 'required',
            'endDate' => 'required'
        ]);

        $semesterToDelete = Semester::query()->where('active', true)->first();

        $semesterToDelete->update([
            'active' => false,
        ]);

        $semester = Semester::firstOrCreate([
            'name' => $request->years,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'active' => 1,
        ]);



        $start = Carbon::create($request->startDate);
        $end = Carbon::create($request->endDate);

        $firstWeekDay = $start->weekday(1);
        $diff = ($start->diff($end)->days)/7;


        for($i = 0; $i <= $diff; $i++){
            WeekDesignation::firstOrCreate([
                'week_number' => $firstWeekDay->week(),
                'designation' => 'Null',
                'start_date' => $firstWeekDay->format("Y-m-d"),
                'semester_id' => $semester->id
            ]);
            $firstWeekDay = $firstWeekDay->addDays(7);
        }

        return back();


    }
}
