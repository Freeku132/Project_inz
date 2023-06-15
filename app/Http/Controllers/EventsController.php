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
        // Validate data
        $request->validate([
            'id' => 'nullable',
            'start' => 'required',
            'startNew' => 'required',
            'end' => 'required',
            'endNew' => 'required',
            'subject' => 'required',
            'student_info' => 'required',
            'message' => 'required',
            'room' => 'required',
            'class' => 'required',
            'teacher' => 'required',
            'student' => 'required']);

        // Get from DB free and busy EventClass
        $freeClass = EventClass::query()->where('name', 'free')->first();
        $busyClass = EventClass::query()->where('name', 'busy')->first();

        // Get from DB Event model to change
        $event = Event::findOrFail($request->id);

        // Check event class, if is busy return error
        if ($event->class === $freeClass->id) {

            // Update old event
            $event->update([
                'start' => $request->startNew,
                'end' => $request->endNew,
                'subject' => $request->subject,
                'student_info' => $request->student_info,
                'message' => $request->message,
                'student_id' => $request->student,
                'class' => $busyClass->id
            ]);
            // If new event start is different from the old event start, create a new event
            if ($request->start != $request->startNew) {
                Event::create([
                    'start' => $request->start,
                    'end' => $request->startNew,
                    'room' => $request->room,
                    'teacher_id' => $request->teacher,
                    'class' => $freeClass->id
                ]);
            }
            // If new event end is different from the old event end, create a new event
            if ($request->end != $request->endNew) {
                Event::create([
                    'start' => $request->endNew,
                    'end' => $request->end,
                    'room' => $request->room,
                    'teacher_id' => $request->teacher,
                    'class' => $freeClass->id
                ]);
            }

            // Dispatch event to send email
            EventBooking::dispatch($event);

            return back()->with('success_message', 'created_info');

        } else {
            return back()->withErrors('event_already_booked');
        }
    }
}

//        Mail::to($event->teacher->email)->send(new NewEventBookingMail($event));
