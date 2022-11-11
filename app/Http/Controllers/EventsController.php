<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventClass;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function store( Request $request)
    {
        $request->validate([
            'id' => 'nullable',
            'start' => 'required',
            'startNew'=>'required',
            'end' => 'required',
            'endNew' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'room' => 'required',
            'class' => 'required',
            'teacher' => 'required',
            'student' => 'required']);

        $freeClass = EventClass::query()->where('name', 'free')->first();
        $busyClass = EventClass::query()->where('name', 'busy')->first();

        if($request->start != $request->startNew){
            Event::create([
                'start' => $request->start,
                'end' => $request->startNew,
                'room' => $request->room,
                'teacher_id' => $request->teacher,
                'class' => $freeClass->id
            ]);
        }

        if($request->end != $request->endNew){
            Event::create([
                'start' => $request->endNew,
                'end' => $request->end,
                'room' => $request->room,
                'teacher_id' => $request->teacher,
                'class' => $freeClass->id
            ]);
        }

        $event =Event::findOrFail($request->id);
        $event->update([
            'start' => $request->startNew,
            'end' => $request->endNew,
            'subject' => $request->subject,
            'message' => $request->message,
            'student_id' => $request->student,
            'class' => $busyClass->id
        ]);

        return back()->with('success_message', 'You has been successfully reserved event');
    }
}
