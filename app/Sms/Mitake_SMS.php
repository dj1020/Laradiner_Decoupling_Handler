<?php
/**
 * Author: twinkledj
 * Date: 11/26/15
 */

namespace App\Sms;


class Mitake_SMS implements SmsCourierInterface
{

    public function sendTextMessage($param)
    {
        var_dump('Message Sent by Mitake SMS platform');
    }
}