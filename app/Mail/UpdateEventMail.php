<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateEventMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->event->eventClass->name =='accepted') {
            $status = 'Zaakceptowany';
        } elseif ($this->event->eventClass->name =='cancelled') {
            $status = 'Odwołany';
        }

        return $this->markdown('events.update',
            [
                'status' => $status
            ]);
    }

}
