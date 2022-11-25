<?php

namespace App\Http\Controllers;

use App\Events\EventUpdated;
use App\Models\Event;
use App\Models\EventClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherEventController extends Controller
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
            ->whereDate('start','>' ,Carbon::now()->format('Y-m-d'))
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




//        $events = Event::query()
//            ->with(['teacher', 'student', 'eventClass'])
//            ->whereIn('class', [2,3])
//            ->whereDate('start','>' ,Carbon::now()->format('Y-m-d'))
//            ->where('teacher_id', $user->id)
//            ->orderBy('start')
//            ->paginate(5)
//            ->withQueryString()
//            ->through(fn($event) => [
//                'id'      => $event->id,
//                'subject' => $event->subject,
//                'message' => $event->message,
//                'start'    => $event->start,
//                'end'    => $event->end,
//                'teacher' => $event->teacher,
//                'student' => $event->student,
//                'room' => $event->room,
//                'class' => $event->eventClass->name
//            ]);
//
////        dd($events);
//
//
//        if($request->input('event')){
//            if ($request->input('page') === null){
//                $events = Event::query()
//                    ->where('id' , $request->input('event'))
//                    ->with(['teacher', 'student', 'eventClass'])
//                    ->union(Event::query()
//                    ->whereIn('class', [2,3])
//                    ->whereDate('start','>' ,Carbon::now()->format('Y-m-d'))
//                    ->where('teacher_id', $user->id)
//                    ->orderByDesc('start')
//                    )
//                    ->paginate(5)
//                    ->withQueryString()
//                    ->through(fn($event) => [
//                        'id'      => $event->id,
//                        'subject' => $event->subject,
//                        'message' => $event->message,
//                        'start'    => $event->start,
//                        'end'    => $event->end,
//                        'teacher' => $event->teacher,
//                        'student' => $event->student,
//                        'room' => $event->room,
//                        'class' => $event->eventClass->name
//                    ]);
//
//            }
//        }


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
        $freeClass = EventClass::query()->where('name', 'free')->first();


  //      dd($request);
        $request->validate([
            'day' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
            'week' => 'required',
            'room' => 'required',
        ]);

        $startSemester = '01-10-2022';
//        $startSemester = '25-02-2023';
        $endSemesterDate = "20-02-2023";
//        $endSemesterDate = "12-07-2023";

        $freeDays = [
            '0'  => '31-10-2022',
            '1'  => '01-11-2022',
            '2'  => '11-11-2022',
            '3'  => '23-12-2022',
            '4'  => '24-12-2022',
            '5'  => '25-12-2022',
            '6'  => '26-12-2022',
            '7'  => '27-12-2022',
            '8'  => '28-12-2022',
            '9'  => '29-12-2022',
            '10' => '30-12-2022',
            '11' => '30-12-2022',
        ];

        $endSemesterDate = Carbon::create($endSemesterDate);
        $startSemesterDate = Carbon::parse($startSemester);

        $chosenDayDate = $startSemesterDate->weekday($request->day);

        $weekArray = [
            '38' => 'B',
            '39' => 'A',
            '40' => 'B',
            '41' => 'A',
            '42' => 'B',
            '43' => 'A',
            '44' => 'B',
            '45' => 'A',
            '46' => 'B',
            '47' => 'A',
            '48' => 'B',
            '49' => 'A',
            '50' => 'B',
            '51' => 'A',
            '52' => 'Null',
            '53' => 'Null',
            '1' => 'B',
            '2' => 'A',
            '3' => 'B',
            '4' => 'A',
            '5' => 'B',
            '6' => 'A',
            '7' => 'B',
            '8' => 'B',
            '9' => 'A',
            '10' => 'B',
            '11' => 'A',
            '12' => 'B',
            '13' => 'A',
            '14' => 'B',
            '15' => 'A',
            '16' => 'B',
            '17' => 'A',
            '18' => 'B',
            '19' => 'A',
            '20' => 'B',
            '21' => 'A',
            '22' => 'B',
            '23' => 'A',
            '24' => 'B',
            '25' => 'A',
            '26' => 'B',
            '27' => 'A',
            '28' => 'B',
            '29' => 'A',
        ];


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
                if ($chosenDayDate == Carbon::create($freeDay)) {
                    $dayDates->forget($i);
                }
            }

            $chosenDayDate->addDays(7);
        }

        //  dd($dayDates);

//        dd($eventsDates);
        foreach ($dayDates as $item){
            if($request->week === 'A/B'){
                if($weekArray[$item['week']] === 'A' || $weekArray[$item['week']] === 'B'){
                    if($item['startDate'] < Carbon::create($startSemester)){

                    } else {
                        Event::create([
                        'start'       => $item['startDate'],
                        'end'         => $item['endDate'],
                        'room'        => $request->room,
                        'class'       => $freeClass->id,
                        'subject'     => '',
                        'message'     => '',
                        'student_info'=> '',
                        'teacher_id'  => auth()->id(),
                        ]);
                    }
                }


            }elseif ($weekArray[$item['week']] === $request->week){
                if($item['startDate'] < Carbon::create($startSemester)){

                } else {
                    echo $item['week'];
                    echo "- $request->week ";
                    Event::create([
                        'start'       => $item['startDate'],
                        'end'         => $item['endDate'],
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
