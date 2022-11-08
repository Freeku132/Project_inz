<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherEventController extends Controller
{
    public function index(User $user)
    {
        $events = Event::query()
            ->with(['teacher', 'student'])

            ->where('teacher_id', $user->id)
            ->where('class', 'busy')
            ->orWhere('class', 'claim')
            ->where('teacher_id', $user->id)
            ->orderBy('updated_at')
            ->paginate(20);


        return Inertia::render('Teacher/EventsIndex',[
            'user' => $user,
            'events' => $events,
        ]);
    }
    public function update(User $user,Request $request, Event $event)
    {


        $event = Event::findOrFail($request->id);

        $event->update([
            'class' => $request->class
        ]);

        return redirect('/profile/'.$user->id.'/events')->with('success_message', 'You has been completed change event status to '.$request->class);
    }

    public function store(Request $request)
    {

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
                'date' =>$chosenDayDate->toDateString(),
                'startDate' => Carbon::create($chosenDayDate->format('Y-m-d')." ".$request->startTime),
                'endDate' => Carbon::create($chosenDayDate->format('Y-m-d')." ".$request->endTime),
                'week' => Carbon::create($chosenDayDate)->week
            ]);
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
                        'start' => $item['startDate'],
                        'end' => $item['endDate'],
                        'subject'=> '',
                        'message' => '',
                        'room' => $request->room,
                        'class' => 'free',
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
                        'start' => $item['startDate'],
                        'end' => $item['endDate'],
                        'subject'=> '',
                        'message' => '',
                        'room' => $request->room,
                        'class' => 'free',
                        'teacher_id'  => auth()->id(),
                    ]);
                }
            }
        }


        return redirect()->back()->with('success_message', 'Creating events completed');
    }
}
