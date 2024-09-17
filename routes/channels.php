<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::info('Authorizing channel for user: ' . $user->id);
    return (int) $user->id === 52;
});
