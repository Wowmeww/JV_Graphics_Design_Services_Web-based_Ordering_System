<?php
// routes/channels.php
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// routes/channels.php
Broadcast::channel('update-order-status.{userId}', function (User $user, $userId) {
    return $user->id === (int) $userId;
});
