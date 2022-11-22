@component('mail::message')
# Konstultacje

Został zarezerowany nowy termin konsultacji: <br>
{{$event->start}} - {{\Illuminate\Support\Carbon::create($event->end)->format('H:i:s')}}

przez: {{$event->student->name}}, {{$event->student_info}}

@component('mail::button', ['url' => $url])
Potwierdź termin
@endcomponent

Wiadomość wysłana przez system USOS.
<br>
W ramach
{{ config('app.name') }}
@endcomponent
