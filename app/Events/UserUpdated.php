<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UserUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public function __construct(User $user)
    {
        Log::info('User updated event fired' . $user);
        $this->user = $user;
        Log::info('Broadcasting user complete' . $this->user);

    }
    public function broadcastOn(): Channel
    {
        Log::info('Broadcasting user updated event');
        return new PrivateChannel('User.' . $this->user->id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'roles' => $this->user->roles,
        ];
    }
}
