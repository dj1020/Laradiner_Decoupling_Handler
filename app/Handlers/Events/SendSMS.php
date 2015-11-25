<?php

namespace App\Handlers\Events;

use App\Events\SendSMSEvent;
use App\Sms\SmsCourierInterface;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSMS implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var User
     */
    private $users;

    /**
     * @var SmsCourierInterface
     */
    private $courier;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(User $users, SmsCourierInterface $courier)
    {
        $this->users = $users;
        $this->courier = $courier;
    }

    /**
     * Handle the event.
     *
     * @param  SendSMSEvent  $event
     * @return void
     */
    public function handle(SendSMSEvent $event)
    {
        $data = $event->getData();

        $user = $this->users->find($data['user']['id']);

        $user->sendSms($this->courier, $data['phone'], $data['message']);
    }
}
