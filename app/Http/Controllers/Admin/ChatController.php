<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $userId = Auth::user()->id;

        //? get users who have sent chat to admin or admin has sent message to
        $chatUsers = User::where('id', '!=', $userId)
            ->whereHas('chats', function ($query) use ($userId) {
                $query->where(function ($subQuery) use ($userId) {
                    $subQuery->where('sender_id', $userId)
                        ->orWhere('receiver_id', $userId);
                });
            })
            ->orderByDesc('created_at')
            ->distinct()
            ->get();

        // dd($chatUsers);

        return view('admin.chat.index', compact('chatUsers'));
    }
}
