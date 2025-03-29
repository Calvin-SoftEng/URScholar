<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeneralNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $type;
    public $data;

    public function __construct($message, $type = 'info', $data = null)
    {
        $this->message = $message;
        $this->type = $type;
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new Channel('general-notifications');
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'data' => $this->data
        ];
    }
}