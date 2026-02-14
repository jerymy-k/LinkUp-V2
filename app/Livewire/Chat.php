<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;

class Chat extends Component
{
    public $conversations;
    public $selectedConversation = null;
    public $messages = [];

    public function mount()
    {
        // Load conversations of logged user
        $this->conversations = Conversation::where('user_id', auth()->id())
            ->orWhere('friend_id', auth()->id())
            ->get();
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversation = Conversation::with('messages.sender')
        ->find($conversationId);
    }

    public function render()
    {
        return view('livewire.⚡chat');
    }
}