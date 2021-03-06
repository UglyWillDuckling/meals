<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class Meal_Relation_Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $relation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($relation)
    {
        $this->relation = $relation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('meal-channel');
    }
}
