<?php

namespace App\Handlers\Events;

use App\Events\SendSMSEvent;
use App\Sms\Mitake_SMS;
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

        // 這裡被 new 綁死了，如果要使用另一個簡訊平台發簡訊的話，
        // 必需要修改程式碼才能換平台，而且無法使用 Mock 做測試。
        $mitake = new Mitake_SMS($this->apiKey);
        $mitake->sendTextMessage([
            'to'      => $data['phone'],
            'message' => $data['message'],
        ]);

        // 挑戰 1：有沒有什麼寫法是可以換簡訊平台卻不需要修改已經寫好的 Production Code？
        // 挑戰 2：在修改最少的情況下，讓這個 Mitake_SMS 類別可以被 Mock 取代，進而測試 handle 方法。

        // 這裡和 Eloquent 的資料庫相依性太高，造成另一個測試上的困難，
        // 無法再不觸及資料庫的情況下來做測試，違背單元測試的原則。
        $user = \App\User::find($data['user']['id']);
        $user->messages()->create([
            'to'      => $data['phone'],
            'message' => $data['message'],
        ]);

        // 挑戰 3：如何在不觸及資料庫操作的前提下，寫測試驗證 handle 方法內的處理邏輯？
    }
}
