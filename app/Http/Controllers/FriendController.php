<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\FriendRequest;
use App\Models\Friendship;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\View\View;

class FriendController extends Controller
{   
    public function show(User $user): View
    {
        $sentFriendRequests = $user->sentFriendRequests()->where('stat', 'pending')->whereNotNull('reciever_id')->with('reciever')->get(); 
        $receivedFriendRequests = $user->receiveFriendRequests()->where('stat', 'pending')->with('sender')->get();
        $inviteLinks = $user->sentFriendRequests()->where('stat', 'pending')->whereNull('reciever_id')->get();
        $friends = $user->friends;
        return view('pages.friends', ['sentRequests' => $sentFriendRequests, 'receivedRequests' => $receivedFriendRequests, 'inviteLinks' => $inviteLinks, 'friends' => $friends]);
    }

    public function send(Request $request){
        $request->validate(['reciever_id' => 'required|exists:users,id']);
        $receiverId = $request->reciever_id;
        if (auth()->user()->hasPendingRequestTo($receiverId)) {
            return back()->with('error', 'Demande déjà envoyée');
        }
        if (auth()->user()->isFriendWith($receiverId)) {
            return back()->with('error', 'Vous êtes déjà amis');
        }
        auth()->user()->sendFriendRequestTo($receiverId);
        return back()->with('success', 'Demande d\'ami envoyée');
    }
    
    public function accept(FriendRequest $friendRequest){
        // abort_if($friendRequest->reciever_id !== auth()->id(), 403);
        if ($friendRequest->isExpired()) {
            // $friendRequest->cancel();
            return back()->with('error', 'Demande expirée');
        }
        DB::transaction(function () use ($friendRequest) {
            $sender = $friendRequest->sender_id;
            // $receiver = $friendRequest->reciever_id;
            Friendship::firstOrCreate([
                'user_id' => $sender,
                'friend_id' => auth()->id(),
            ]);
            
            Friendship::firstOrCreate([
                'user_id' => auth()->id(),
                'friend_id' => $sender,
            ]);
            
            $friendRequest->accept();
        });
        
        return back()->with('success', 'Demande acceptée');
    }

    public function reject(FriendRequest $friendRequest){
        abort_if($friendRequest->reciever_id !== auth()->id(), 403);
        $friendRequest->reject();
        return back()->with('success', 'Demande refusée');
    }

    public function cancel(FriendRequest $friendRequest){
        abort_if($friendRequest->sender_id !== auth()->id(), 403);
        $friendRequest->cancel();
        return back()->with('success', 'Demande annulée');
    }

    public function remove(User $user){
        $authUser = auth()->user();
        if (!$authUser->isFriendWith($user->id)) {
            return back()->with('error', 'Vous n\'êtes pas amis');
        }
        $authUser->removeFriend($user->id);
        return back()->with('success', 'Ami supprimé avec succès');
    }

    public function acceptByToken(string $token){
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $friendRequest = FriendRequest::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();
            
        if ($friendRequest->isExpired()) {
            $friendRequest->cancel();
            return redirect()->route('home')->with('error', 'Lien expiré');
        }

        if (auth()->user()->isFriendWith($friendRequest->sender_id)) {
            return redirect()->route('home')->with('error', 'Vous êtes déjà amis');
        }

        if ($friendRequest->reciever_id === null) {
            $friendRequest->reciever_id = auth()->id();
            $friendRequest->save();
        }

        return $this->accept($friendRequest);
    }

    public function generateLink(){
        $friendRequest = FriendRequest::create([
            'sender_id' => auth()->id(),
            'reciever_id' => null,
            'token' => Str::uuid(),
            'expires_at' => now()->addHour(),
            'auto_accepted' => true,
            'stat' => 'pending',
        ]);

        $link = route('friends.acceptByToken', $friendRequest->token);
        return back()->with('invite_link', $link);
    }

    public function generateQr(){
        $friendRequest = FriendRequest::create([
            'sender_id' => auth()->id(),
            'reciever_id' => null,
            'token' => Str::uuid(),
            'expires_at' => now()->addHour(),
            'auto_accepted' => true,
            'stat' => 'pending',
        ]);
        
        $link = route('friends.acceptByToken', $friendRequest->token);
        $qr = \QrCode::size(250)->generate($link);
        
        return back()->with(['invite_link' => $link, 'qr_code' => $qr]);
    }
}