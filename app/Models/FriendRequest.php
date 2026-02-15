<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FriendRequest extends Model
{
    protected $fillable = ['reciever_id', 'sender_id', 'stat', 'token', 'expires_at', 'auto_accepted', 'accepted_at'];
    protected $casts = ['expires_at' => 'datetime', 'accepted_at' => 'datetime', 'auto_accepted' => 'boolean'];

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reciever(){
        return $this->belongsTo(User::class, 'reciever_id');
    }
    
    public function accept(): void{
        if ($this->isExpired()) {
            abort(403, 'Invitation expirée');
        }
        // $this->update(['stat' => 'accepted', 'accepted_at' => now(), 'token' => null, 'expires_at' => null]);
        $this->update(['accepted_at' => now()]);
    }

    public function reject(): void{
        $this->update(['stat' => 'refused', 'token' => null, 'expires_at' => null]);
    }

    public function cancel(): void{
        $this->update(['stat' => 'cancelled', 'token' => null, 'expires_at' => null]);
    }

    public function isExpired(): bool{
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }
}