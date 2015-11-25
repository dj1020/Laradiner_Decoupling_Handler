<?php

namespace App\Http\Controllers;

use App\Events\SendSMSEvent;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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

        // 這裡和 Laravel 的 Event 系統綁太緊，變成要送簡訊一定要 fire a event
        event(new SendSMSEvent($data));

        // 挑戰 4：如果我不想使用 Event 系統來發簡訊的話怎麼辦？
    }
}
