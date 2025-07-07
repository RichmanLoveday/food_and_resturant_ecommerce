<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Chat;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $userId = Auth::user()->id;

        //? get users who have sent chat to admin or admin has sent message to
        // $chatUsers = User::where('id', '!=', $userId)
        //     ->whereHas('chats', function ($query) use ($userId) {
        //         $query->where(function ($subQuery) use ($userId) {
        //             $subQuery->where('sender_id', $userId)
        //                 ->orWhere('receiver_id', $userId);
        //         });
        //     })
        //     ->orderByDesc('created_at')
        //     ->distinct()
        //     ->get();

        $senders = Chat::select('sender_id')
            ->where('receiver_id', $userId)
            ->where('sender_id', '!=', $userId)
            ->selectRaw('MAX(created_at) as latest_message_sent')
            ->groupBy('sender_id')
            ->orderBy('latest_message_sent', 'desc')
            ->with(['sender' => function ($query) {
                $query->select('id', 'name', 'avatar');
            }])
            ->get()
            ->map(function ($sender) use ($userId) {
                $sender->unseen_messages = Chat::where('sender_id', $sender->sender_id)
                    ->where('receiver_id', $userId)
                    ->where('seen', false)
                    ->count();

                return $sender;
            });


        // dd($senders->toArray());

        return view('admin.chat.index', compact('senders'));
    }


    public function getConversation(string|int $senderId): Response|JsonResponse
    {
        $receiverId = Auth::user()->id;

        try {
            DB::beginTransaction();

            //? update unseen messages to seen
            Chat::where(['sender_id' => $senderId, 'receiver_id' => $receiverId])
                ->where('seen', false)
                ->update(['seen' => true]);

            //? fetch messages related to the receiver, by sender id and receiver id
            $messages = Chat::whereIn('sender_id', [$senderId, $receiverId])
                ->whereIn('receiver_id', [$senderId, $receiverId])
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();

            DB::commit();

            return response()->json($messages, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to fetch conversation.'], 500);
        }
    }


    public function sendMessage(Request $request)
    {
        // dd($request->all());
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
        broadcast(new ChatEvent(
            $request->message,
            $request->receiver_id,
            Auth::user()->id,
            $avatar
        ))
            ->toOthers();

        return response()->json(['status' => 'success']);
    }
}