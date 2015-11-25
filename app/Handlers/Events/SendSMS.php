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

    // 測試不是綠燈嗎？怎麼反而整個壞掉不能傳簡訊了
    // 這錯誤是什麼鬼：
    //     BindingResolutionException in Container.php line 745:
    //     Target [App\Sms\SmsCourierInterface] is not instantiable.
    //
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
