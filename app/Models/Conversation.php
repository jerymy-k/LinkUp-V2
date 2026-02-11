<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Conversation extends Model
{
    protected $fillable = [
        'user_id',
        'friend_id'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function friend() : BelongsTo
    {
        return $this->belongsTo(User::class,'friend_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
