<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user = null, $id) {
    return $user?->id == $id;
});

Broadcast::channel('order-placed', function () {
    return true;
});


/***
 * Chat channels
 * This channel is used for private chat between users.
 * The receiverId is the ID of the user who will receive the message.
 * The channel is private, meaning only the sender and receiver can listen to it.
 * It is used to broadcast chat messages in real-time.
 * The channel name is in the format 'chat.{receiverId}' where receiverId is the ID of the user receiving the message.
 * The channel will only be accessible to the user with the matching ID.
 * Example: If the receiverId is 5, the channel name will be 'chat.5'.
 * When a user sends a message, it will be broadcasted to this channel.
 * The user can listen to this channel to receive real-time updates of the chat messages.
 */
Broadcast::channel('chat.{receiverId}', function ($user, $receiverId) {
    return (int) $user->id === (int) $receiverId;
});
