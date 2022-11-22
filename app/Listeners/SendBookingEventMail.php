<?php

namespace App\Listeners;

use App\Events\EventBooking;
use App\Mail\NewEventBookingMail;
use App\Mail\UpdateEventMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBookingEventMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EventBooking  $event
     * @return void
     */
    public function handle(EventBooking $event)
    {
        $event = $event->event;

        Mail::to($event->teacher->email)->send(new NewEventBookingMail($event));
    }
}
