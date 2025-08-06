<?php
// routes/channels.php
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

// routes/channels.php

Broadcast::channel('order-placed', function (User $user) {
    return $user->is_admin;
});
Broadcast::channel('transaction', function (User $user) {
    return $user->is_admin;
});
Broadcast::channel('update-order-status.{userId}', function (User $user, $userId) {
    return $user->id === (int) $userId;
});
Broadcast::channel('new-order-notification.{receiverId}', function (User $user, $receiverId) {
    return $user->id === (int) $receiverId;
});

Broadcast::channel('message-sent.{receiverId}', function (User $user, $receiverId) {
    return (int) $user->id === (int) $receiverId;
});
