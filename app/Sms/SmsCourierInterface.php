<?php
/**
 * Author: twinkledj
 * Date: 11/26/15
 */
namespace App\Sms;

interface SmsCourierInterface
{
    public function sendTextMessage($param);
}