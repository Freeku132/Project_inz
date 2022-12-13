<?php

namespace App\Http\Controllers;

use App\Models\FreeDay;
use App\Models\Semester;
use Illuminate\Http\Request;

class FreeDaysController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required'
        ]);

        $semester = Semester::query()->where('active', 1)->first();

        FreeDay::create([
            'name' => 'free',
            'date' => $request->date,
            'semester_id' => $semester->id
        ]);

        return back();
    }

    public function destroy(FreeDay $freeDay)
    {
//        dd($freeDay);

        $freeDay->deleteOrFail();

        return back();
    }
}
