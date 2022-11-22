<?php

namespace App\Http\Controllers;

use App\Events\EventBooking;
use App\Mail\NewEventBookingMail;
use App\Models\Event;
use App\Models\EventClass;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
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
            'student_info'=>'required',
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
            'student_info'=>$request->student_info,
            'message' => $request->message,
            'student_id' => $request->student,
            'class' => $busyClass->id
        ]);

//        Mail::to($event->teacher->email)->send(new NewEventBookingMail($event));

        EventBooking::dispatch($event);

        return back()->with('success_message', 'created_info');
    }
}
