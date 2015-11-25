<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendSMSEvent extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    private $data;
    private $courier;

    /**
     * Create a new event instance.
     *
     * @param $data
     */
    public function __construct($data, $courier)
    {
        $this->data = $data;
        $this->courier = $courier;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function getCourier()
    {
        return $this->courier;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
