<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Models\FriendRequest;
use App\Models\Post;
use App\Models\Like;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function receiveFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'reciever_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function isFriendWith(int $userId)
    {
        return $this->friends()->where('friend_id', $userId)->exists();
    }

    public function hasPendingRequestTo(int $userId)
    {
        return FriendRequest::where('sender_id', $this->id)
            ->where('reciever_id', $userId)
            ->where('stat', 'pending')
            ->exists();
    }

    public function sendFriendRequestTo(int $userId): void
    {
        if ($this->id === $userId)
            return;
        FriendRequest::firstOrCreate(['sender_id' => $this->id, 'reciever_id' => $userId,], ['stat' => 'pending']);
    }

    public function removeFriend(int $userId): void
    {
        Friendship::where(function ($q) use ($userId) {
            $q->where('user_id', $this->id)
                ->where('friend_id', $userId);
        })->delete();
    }

    public function friends()
    {
        return $this->belongsToMany(
            User::class,
            'friendships',
            'user_id',
            'friend_id'
        );
    }
}
