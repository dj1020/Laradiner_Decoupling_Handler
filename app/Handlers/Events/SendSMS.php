<?php

namespace App\Handlers\Events;

use App\Events\SendSMSEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSMS implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event handler.
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
     * @param  SendSMSEvent  $event
     * @return void
     */
    public function handle(SendSMSEvent $event)
    {
        var_dump("Event fired");

        $data = $event->getData();

        $mitake = new Mitake_SMS($this->apiKey);
        $mitake->sendTextMessage([
            'to'      => $data['phone'],
            'message' => $data['message'],
        ]);

        $user = \App\User::find($data['user']['id']);
        $user->messages()->create([
            'to'      => $data['phone'],
            'message' => $data['message'],
        ]);
    }
}
