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

//        Mail::to($event->student->email)->send(new UpdateEventMail($event));
        EventUpdated::dispatch($event);

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

        $semester = Semester::query()->where('active', 1)->first();



        if($semester->name == 'semester_test'){
            return redirect()->back()->withErrors(['semester_error' => 'semester_error']);
        }


        $freeClass = EventClass::query()->where('name', 'free')->first();



        $startSemester = $semester->start_date;
        $endSemesterDate = $semester->end_date;


        $freeDays = FreeDay::query()->where('semester_id', $semester->id)->get();


        $endSemesterDate = Carbon::create($endSemesterDate);
        $startSemesterDate = Carbon::parse($startSemester);
        $chosenDayDate = $startSemesterDate->weekday($request->day);


        $weeks = WeekDesignation::query()->where('semester_id', $semester->id)->get();



        $weekArray = array();

        foreach ($weeks as $week) {
            $weekArray[$week->week_number] = $week->designation;
        }



        $diff = (date_diff($endSemesterDate, $chosenDayDate)->days)/7;


        $dayDates = collect([]);
        for ($i = 0; $diff >= $i; $i++){

            $dayDates->add([
                'date'      => $chosenDayDate->toDateString(),
                'startDate' => Carbon::create($chosenDayDate->format('Y-m-d')." ".$request->startTime),
                'endDate'   => Carbon::create($chosenDayDate->format('Y-m-d')." ".$request->endTime),
                'week'      => Carbon::create($chosenDayDate)->week
            ]);

            foreach ($freeDays as $freeDay) {
                if ($chosenDayDate == Carbon::create($freeDay->date)) {
                    $dayDates->forget($i);
                }
            }

            $chosenDayDate->addDays(7);
        }



        foreach ($dayDates as $day){
            if($request->week === 'A/B'){
                if($weekArray[$day['week']] === 'A' || $weekArray[$day['week']] === 'B'){
                    if($day['startDate'] < Carbon::create($startSemester)){

                    } else {
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


            }elseif ($weekArray[$day['week']] === $request->week){
                if($day['startDate'] < Carbon::create($startSemester)){
                } else {
                    echo $day['week'];
                    echo "- $request->week ";
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
