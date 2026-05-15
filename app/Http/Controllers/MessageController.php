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
            'receiver_id' => 'required|exists:users,id',
        ]);

        $sender = Auth::user();
        
        // Ensure users are in the same farm system (except for buyers)
        $receiver = User::findOrFail($validated['receiver_id']);
        
        // Basic check: Admin/Farmer can message each other. Buyer can message Farmer/Admin.
        // For simplicity in this marketplace transformation, we'll allow cross-messaging.

        Message::create([
            'farm_id' => $sender->farm_id ?? $receiver->farm_id, // Use farmer's farm if sender is buyer
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Message sent!');
    }
}
