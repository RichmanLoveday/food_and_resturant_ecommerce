<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Chat;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
            // DB::beginTransaction();

            //? update unseen messages to seen
            $this->updateUnseenMessages($senderId, $receiverId);

            //? fetch messages related to the receiver, by sender id and receiver id
            $messages = Chat::whereIn('sender_id', [$senderId, $receiverId])
                ->whereIn('receiver_id', [$senderId, $receiverId])
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();

            // DB::commit();

            return response()->json($messages, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to fetch conversation.'], 500);
        }
    }


    public function sendMessage(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();

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

            //? update unseen messages to seen for the receiver
            $this->updateUnseenMessages($request->receiver_id, Auth::user()->id);

            //? fire event to broadcast the message
            $avatar = Auth::user()->avatar;
            broadcast(new ChatEvent(
                $request->message,
                $request->receiver_id,
                Auth::user()->id,
                $avatar,
                $chat->created_at,
                Auth::user()->name
            ))->toOthers();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'msgId' => $request->msg_temp_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Chat message sending failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send message.'], 500);
        }
    }


    private function updateUnseenMessages(int $senderId, int $receiverId): void
    {
        //? update unseen messages to seen
        Chat::where(['sender_id' => $senderId, 'receiver_id' => $receiverId])
            ->where('seen', false)
            ->update(['seen' => true]);
    }

    public function getUserConversations(Request $request, string|int $senderId): View|RedirectResponse
    {
        try {
            $receiverId = Auth::user()->id;

            //? update unseen messages to seen
            $this->updateUnseenMessages($senderId, $receiverId);

            //? fetch messages related to the receiver, by sender id and receiver id
            $senders = Chat::select('sender_id')
                ->where('receiver_id', $receiverId)
                ->where('sender_id', '!=', $receiverId)
                ->selectRaw('MAX(created_at) as latest_message_sent')
                ->groupBy('sender_id')
                ->orderBy('latest_message_sent', 'desc')
                ->with(['sender' => function ($query) {
                    $query->select('id', 'name', 'avatar');
                }])
                ->get()
                ->map(function ($sender) use ($receiverId) {
                    $sender->unseen_messages = Chat::where('sender_id', $sender->sender_id)
                        ->where('receiver_id', $receiverId)
                        ->where('seen', false)
                        ->count();

                    return $sender;
                });


            //? fetch messages related to the receiver, by sender id and receiver id
            $messages = Chat::whereIn('sender_id', [$senderId, $receiverId])
                ->whereIn('receiver_id', [$senderId, $receiverId])
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();

            //? Fetch sender details
            $senderDetails = User::find($senderId);

            // dd($messages->toArray(), $senders->toArray());
            return view('admin.chat.index', compact(
                'messages',
                'senders',
                'senderDetails',
                'receiverId',
            ));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to fetch messages.']);
        }
    }


    public function markAllAsRead(Request $request): RedirectResponse
    {
        try {
            $userId = Auth::user()->id;

            //? update unseen messages to seen for the user
            Chat::where('receiver_id', $userId)
                ->where('seen', false)
                ->update(['seen' => true]);

            toastr()->success('All messages marked as read successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            logger()->error('Failed to mark messages as read: ' . $e->getMessage());

            toastr()->error('Failed to mark messages as read.');
            return redirect()->back();
        }
    }
}