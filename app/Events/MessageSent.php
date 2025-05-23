<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }
    public function broadcastOn()
    {
        // Use batch_id if available, otherwise fall back to staff_group_id
        $channelId = $this->message->scholarship_group_id ?? $this->message->staff_group_id ?? $this->message->conversation_id;

        return new PrivateChannel("chat.{$channelId}"); // Use Private Channel for authenticated users
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}