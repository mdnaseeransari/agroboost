<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'receiver_id' => 'nullable|exists:users,id',
        ]);

        $sender = Auth::user();
        $receiverId = $request->receiver_id;

        // If farmer is sending, the receiver must be the admin
        if ($sender->role === 'farmer') {
            $admin = User::where('farm_id', $sender->farm_id)->where('role', 'admin')->first();
            if (!$admin) {
                return back()->with('error', 'No admin found to message.');
            }
            $receiverId = $admin->id;
        }

        if (!$receiverId) {
            return back()->with('error', 'Please select a recipient.');
        }

        Message::create([
            'farm_id' => $sender->farm_id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Message sent!');
    }
}
