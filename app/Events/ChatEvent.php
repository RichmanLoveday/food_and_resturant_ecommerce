<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public int|string $receiverId;
    public int|string $senderId;
    public string $avatar;
    public string $created_at;
    public string $senderName;

    /**
     * Create a new event instance.
     */
    public function __construct(string $message, int|string $receiverId, int|string $senderId, string $avatar, string $created_at, string $senderName)
    {
        $this->message = $message;
        $this->receiverId = $receiverId;
        $this->senderId = $senderId;
        $this->avatar = $avatar;
        $this->created_at = $created_at;
        $this->senderName = $senderName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("chat.{$this->receiverId}"),
        ];
    }
}
