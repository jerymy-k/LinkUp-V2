<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Store a new message
    public function store(Request $request, $conversationId)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
            'image' => 'nullable|image|max:5120',
            'video' => 'nullable|video|max:10240',
            'file' => 'nullable|file|max:10240',
        ]);
       
        $conversation = Conversation::findOrFail($conversationId);

        // Determine receiver_id
        $receiverId = $conversation->user_id === Auth::id()
            ? $conversation->friend_id
            : $conversation->user_id;

        // Save message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'content' => $request->content,
            'is_read' => false,
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('messages/images', 'public');
            $message->update(['image' => $path]);
        }

        // Handle image upload
        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('messages/videos', 'public');
            $message->update(['video' => $path]);
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('messages/files', 'public');
            $message->update(['file' => $path]);
        }

        // Optional: broadcast new message event
        // broadcast(new \App\Events\MessageSent($message))->toOthers();

        return redirect()->back();
    }

    // Delete a message
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        // Only sender can delete
        if ($message->sender_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $message->delete();

        return redirect()->back();
    }

    // Mark conversation as read
    public function markRead($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // Only mark messages received by the user
        $conversation->messages()
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);

        return redirect()->back();
    }
}