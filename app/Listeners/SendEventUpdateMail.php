<?php

namespace App\Listeners;

use App\Events\EventUpdated;
use App\Mail\UpdateEventMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEventUpdateMail
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
     * @param  \App\Events\EventUpdated  $event
     * @return void
     */
    public function handle(EventUpdated $event)
    {
        $event = $event->event;

        Mail::to($event->student->email)->send(new UpdateEventMail($event));
    }
}
