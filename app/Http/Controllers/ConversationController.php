<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Conversation;

class ConversationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $conversations = $user->conversations()->get();
        return view('pages.conversation',['user'=> $user,'conversations'=>$conversations]);
    }

    public function store(Request $request):RedirectResponse
    {
        Conversation::create([
            'user_id' => $request->user,
            'friend_id' => $request->friend
        ]);
        
        return redirect()->route('conversations.show');
    }

    public function destroy(Conversation $conversation): RedirectResponse
    {
        $conversation->delete();
        return redirect()->back();
    }
}
