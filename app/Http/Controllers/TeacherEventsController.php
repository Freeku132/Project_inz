<?php

namespace App\Http\Controllers;

use App\Events\EventUpdated;
use App\Models\Event;
use App\Models\EventClass;
use App\Models\FreeDay;
use App\Models\Semester;
use App\Models\User;
use App\Models\WeekDesignation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherEventsController extends Controller
{

    public function index(Request $request, User $user)
    {

        if($request->input('category')){
            if($request->input('category') === 'busy'){
                $category = [2];
            } elseif($request->input('category') === 'accepted'){
                $category = [3];
            } else{
                $category = [2, 3];
            }
        } else {
            $category = [1,2,3,4];
        }


        if($request->input('event')){
            if ($request->input('page') === null) {
                $eventId = $request->input('event');
            } else{
                $eventId = null;
            }
        }else {
            $eventId = null;
        }

        $events = Event::query()
            ->when($eventId, function ($query) use ($eventId) {
                 $query->where('id', $eventId)->get();
            })
            ->when($request->input('category') && $request->input('category') !== 'all', function ($query) use ($category) {
                $query->whereIn('class', $category);
            }, function ($query){
                $query->whereIn('class', [2,3]);
            })
            ->with(['teacher', 'student', 'eventClass'])
//            ->whereIn('class', $category)
            ->whereDate('start','>=' , Carbon::now()->startOfDay()->format('Y-m-d H:i'))
            ->where('teacher_id', $user->id)
            ->orderByDesc('start')
            ->paginate(5)
            ->withQueryString()
            ->through(fn($event) => [
                'id'            => $event->id,
                'subject'       => $event->subject,
                'message'       => $event->message,
                'student_info'  => $event->student_info,
                'start'         => $event->start,
                'end'           => $event->end,
                'teacher'       => $event->teacher,
                'student'       => $event->student,
                'room'          => $event->room,
                'class'         => $event->eventClass->name
            ]);




        if ($request->only(['page'])) {
        $filters = $request->only(['page']);
            } else {
            $filters = ['page' => 1];
        }

        $selected = $request->only(['event']);




        return Inertia::render('Teacher/EventsIndex',[
            'user' => $user,
            'events' => $events,
            'filters' => $filters,
            'selected' => $selected,
            'category' => $request->input('category')
        ]);
    }


    public function update(User $user,Request $request)
    {
        $event = Event::findOrFail($request->id);



        $class = EventClass::query()->where('name', $request->class)->first();

        $event->update([
            'class' => $class->id
        ]);

        if ($event->student_id !== null){
//        Mail::to($event->student->email)->send(new UpdateEventMail($event));
            EventUpdated::dispatch($event);
        }
        return redirect()->back()->with('success_message', $request->class);
    }


    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
            'week' => 'required',
            'room' => 'required',
        ]);

        // get active semester model
        $semester = Semester::query()->where('active', 1)->first();

        if($semester->name == 'semester_test'){
            return redirect()->back()->withErrors(['semester_error' => 'semester_error']);
        }


        // queries
        $freeClass = EventClass::query()->where('name', 'free')->first();
        $freeDays = FreeDay::query()->where('semester_id', $semester->id)->get();
        $weeks = WeekDesignation::query()->where('semester_id', $semester->id)->get();

        // create date variables
        $endSemesterDate = Carbon::create($semester->end_date);
        $startSemesterDate = Carbon::parse($semester->start_date);
        // get chosen day date
        $chosenDayDate = $startSemesterDate->weekday($request->day);

        // get right array form
        $weekArray = array();
        foreach ($weeks as $week) {
            $weekArray[$week->week_number] = $week->designation;
        }

        // number of weeks between end semester date and chosen date
        $diff = (date_diff($endSemesterDate, $chosenDayDate)->days)/7;


        // collection with chosen day every week from chosen date to end semester date
        $dayDates = collect([]);
        for ($i = 0; $diff >= $i; $i++){

            $dayDates->add([
                'date'      => $chosenDayDate->toDateString(),
                'startDate' => Carbon::create($chosenDayDate->format('Y-m-d')." ".$request->startTime),
                'endDate'   => Carbon::create($chosenDayDate->format('Y-m-d')." ".$request->endTime),
                'week'      => Carbon::create($chosenDayDate)->week
            ]);

            // the loop checks if the selected date is not a free day
            foreach ($freeDays as $freeDay) {
                // if chosen day is free day delete it
                if ($chosenDayDate == Carbon::create($freeDay->date)) {
                    $dayDates->forget($i);
                }
            }
            // add 7 days to chosen day date
            $chosenDayDate->addDays(7);
        }



        foreach ($dayDates as $day){
            // check in which weeks meetings should be generated
            if($request->week === 'A/B'){
                // save events in A and B weeks
                if($weekArray[$day['week']] === 'A' || $weekArray[$day['week']] === 'B'){
                    // check if the start date is later than the semester start date
                    if($day['startDate'] > Carbon::create($semester->start_date)){
                        Event::create([
                            'start'       => $day['startDate'],
                            'end'         => $day['endDate'],
                            'room'        => $request->room,
                            'class'       => $freeClass->id,
                            'subject'     => '',
                            'message'     => '',
                            'student_info'=> '',
                            'teacher_id'  => auth()->id(),
                        ]);
                    }
                }
                // save events only in selected week designations
            } elseif ($weekArray[$day['week']] === $request->week){
                // check if the start date is later than the semester start date
                if($day['startDate'] > Carbon::create($semester->start_date)){
                    Event::create([
                        'start'       => $day['startDate'],
                        'end'         => $day['endDate'],
                        'room'        => $request->room,
                        'class'       => $freeClass->id,
                        'subject'     => '',
                        'message'     => '',
                        'student_info'=> '',
                        'teacher_id'  => auth()->id(),
                    ]);
                }
            }
        }
        return redirect()->back()->with('success_message', 'Creating events completed');
    }
}
