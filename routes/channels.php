<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['api', 'auth:sanctum']]);
Broadcast::channel('notifications.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
