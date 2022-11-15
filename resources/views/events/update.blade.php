@component('mail::message')
# Konsultacje
<h2>
Status konsultacji ({{$event->start}} - {{$event->end}}),
<br>
u prowadzącego: {{$event->teacher->name}}
<br>
został zmieniony na: {{strtoupper($status)}}
</h2>


Wiadomość wysłana przez system USOS.
<br>
W ramach
{{ config('app.name') }}
@endcomponent
