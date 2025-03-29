<?php

namespace App\Events;

use App\Models\Message;
use App\Models\Scholarship;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $scholarshipId;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @param int $scholarshipId
     */
    public function __construct(Message $message, $scholarshipId)
    {
        $this->message = $message;
        $this->scholarshipId = $scholarshipId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Create a channel specific to this scholarship
        return new PrivateChannel('scholarship.' . $this->scholarshipId);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }
    
    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message->load('user'),
            'scholarship_id' => $this->scholarshipId
        ];
    }
}