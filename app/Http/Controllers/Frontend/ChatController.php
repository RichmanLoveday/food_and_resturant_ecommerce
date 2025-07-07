<?php

namespace App\Http\Controllers\Frontend;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => ['required', 'max:1000'],
            'receiver_id' => ['required', 'integer']
        ]);


        //? store chat messages
        $chat = new Chat();
        $chat->sender_id = Auth::user()->id;
        $chat->receiver_id = $request->receiver_id;
        $chat->message = $request->message;
        $chat->save();

        //? fire event to broadcast the message
        $avatar = Auth::user()->avatar;
        broadcast(new ChatEvent($request->message, $request->receiver_id, Auth::user()->id, $avatar))
            ->toOthers();

        return response()->json(['status' => 'success']);
    }


    public function getConversation(string|int $senderId): Response|JsonResponse
    {
        $receiverId = Auth::user()->id;

        //? fetch messages related to the receiver, by sender id and receiver id
        $messages = Chat::whereIn('sender_id', [$senderId, $receiverId])
            ->whereIn('receiver_id', [$senderId, $receiverId])
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages, 200);
    }
}