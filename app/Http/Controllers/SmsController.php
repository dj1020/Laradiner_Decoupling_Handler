<?php

namespace App\Http\Controllers;

use App\Events\SendSMSEvent;
use App\Sms\Mitake_SMS;
use App\Sms\SmsCourierInterface;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class SmsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except("_token");
        $data['user']['id'] = 1;

        App::instance(SmsCourierInterface::class, new Mitake_SMS($this->apiKey));

        event(new SendSMSEvent($data));
    }
}
