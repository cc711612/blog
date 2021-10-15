<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Cache;
use App\Cookies\ClientIdCookie;

class GetOnlineEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * GetOnlineEvent constructor.
     *
     * @param $uuid
     *
     * @Author: Roy
     * @DateTime: 2021/10/11 下午 02:11
     */
    public function __construct($uuid)
    {
        $common_room = [];
        if(Cache::has('common_room') === true){
            $common_room = Cache::get('common_room');
        }
        $common_room = array_unique(array_merge($common_room,[$uuid]));
        Cache::put('common_room',$common_room);
        $this->message = $common_room;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('common_room');
    }
}
