<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function index(Request $request)
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

//    $builder = \App\Models\Event::query()
//        ->when($request->currentDate, function ($query) use ($startDate, $endDate) {
//            $query->where('start', '<', $startDate)
//                ->where('end', '>', $endDate);
//        })
//        ->get();
        $builder = \App\Models\Event::query()
            ->where('start', '>', $startDate)
            ->where('end', '<', $endDate)
            ->get();
        // dd($currentDate);

        return Inertia::render('Home', [
            'events' => $builder,
            'currentDate' => $currentDate
        ]);
    }

    public function store( Request $request)
    {

        //dd($request);
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


        if( $request->start === $request->startNew && $request->end === $request->endNew){

            if(Event::find($request->id)){
                Event::destroy($request->id);
            }

            Event::create([
                'start' => $request->startNew,
                'end' => $request->endNew,
                'subject' => $request->subject,
                'message' => $request->message,
                'room' => $request->room,
                'teacher_id' => $request->teacher,
                'student_id' => $request->student,
                'class' => 'busy',


            ]);

            return back();
        }

        if ( $request->start === $request->startNew || $request->end === $request->endNew){

            if(Event::find($request->id)){
                Event::destroy($request->id);
            }

            if ($request->start === $request->startNew) {

                Event::create([
                    'start' => $request->endNew,
                    'end' => $request-> end,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'room' => $request->room,
                    'teacher_id' => $request->teacher,
                    'class' => 'free'
                ]);
                Event::create([
                    'start' => $request->startNew,
                    'end' => $request-> endNew,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'room' => $request->room,
                    'teacher_id' => $request->teacher,
                    'student_id' => $request->student,
                    'class' => 'busy'
                ]);
                return back();
            } else {
                Event::create([
                    'start' => $request->start,
                    'end' => $request-> startNew,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'room' => $request->room,
                    'teacher_id' => $request->teacher,
                    'class' => 'free'
                ]);
                Event::create([
                    'start' => $request->startNew,
                    'end' => $request-> endNew,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'room' => $request->room,
                    'teacher_id' => $request->teacher,
                    'student_id' => $request->student,
                    'class' => 'busy'
                ]);

                return back();
            }
        }

        if(Event::find($request->id)){
            Event::destroy($request->id);
        }

        Event::create([
            'start' => $request->start,
            'end' => $request-> startNew,
            'subject' => $request->subject,
            'message' => $request->message,
            'room' => $request->room,
            'teacher_id' => $request->teacher,
            'class' => 'free'
        ]);
        Event::create([
            'start' => $request->startNew,
            'end' => $request-> endNew,
            'subject' => $request->subject,
            'message' => $request->message,
            'room' => $request->room,
            'teacher_id' => $request->teacher,
            'student_id' => $request->student,
            'class' => 'busy'
        ]);
        Event::create([
            'start' => $request->endNew,
            'end' => $request-> end,
            'subject' => $request->subject,
            'message' => $request->message,
            'room' => $request->room,
            'teacher_id' => $request->teacher,
            'class' => 'free'
        ]);


        return back()->with('succes_message', 'You has been successfully reserved event');

    }
}
