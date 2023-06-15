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
            'name' => 'required|unique:semesters',
            'startDate' => 'required',
            'endDate' => 'required'
        ]);


        Semester::query()->where('active', true)->first()->update(['active' => false]); // deactivate old semester

        // Store a new semester in DB
        $semester = Semester::firstOrCreate([
            'name'       => $request->name,
            'start_date' => $request->startDate,
            'end_date'   => $request->endDate,
            'active'     => true,
        ]);

        //Use Carbon klass to transform start and end date from form to dateTime format
        $start = Carbon::create($request->startDate);
        $end = Carbon::create($request->endDate);

        //Get the first day(monday) of the weekend that starts the semester
        $firstWeekDay = $start->weekday(1);
        //Get number of weeks between the start and end date
        $numberOfWeeks = ($start->diff($end)->days)/7;

        //Create WeekDesignations in for loop using number of weeks.
        for($i = 0; $i <= $numberOfWeeks; $i++){
            WeekDesignation::firstOrCreate([
                'week_number' => $firstWeekDay->week(),
                'designation' => 'Null',
                'start_date' => $firstWeekDay->format("Y-m-d"),
                'semester_id' => $semester->id
            ]);
            $firstWeekDay = $firstWeekDay->addDays(7);
        }

        return back()->with('success_message', 'create_semester_completed');
    }
}
