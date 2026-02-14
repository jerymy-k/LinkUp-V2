<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', SetUserOnline::class])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profile/edit', function () {
        return view('pages.updating-profile');
    })->name('profile.edit');

    Route::get('/profile/{user}/', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/search', function () {
        return view('pages.search');
    })->name('users.search');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/search', [SearchController::class, 'find'])->name('users.find');

    Route::post('friend', [FriendController::class, 'send'])->name('friend.sendRequest');
    Route::get('/friend/{user}', [FriendController::class, 'show'])->name('friends.show');
    Route::patch('/friend/{friendRequest}/accept', [FriendController::class, 'accept'])->name('friends.accept');
    Route::delete('/friend/{friendRequest}/reject', [FriendController::class, 'reject'])->name('friends.reject');
    Route::delete('/friend/{friendRequest}/cancel', [FriendController::class, 'cancel'])->name('friends.cancel');
    Route::delete('/friend/{user}/remove', [FriendController::class, 'remove'])->name('friends.remove');
    Route::post('/friend/generate-link', [FriendController::class, 'generateLink'])->name('friends.generateLink');
    Route::post('/friend/generate-qr', [FriendController::class, 'generateQr'])->name('friends.generateQr');
    Route::get('/friend/accept/{token}', [FriendController::class, 'acceptByToken'])->name('friends.acceptByToken');

    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
    Route::delete('/post/{post}/delete', [PostController::class, 'destroy'])->name('post.destroy');
    Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/post/{post}/update', [PostController::class, 'update'])->name('post.update');

    Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('posts.like');
    Route::delete('/posts/{post}/like', [LikeController::class, 'destroy'])->name('posts.unlike');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

});

Route::post('/heartbeat', function() {
    if(auth()->check()){
        auth()->user()->update(['is_online' => true]);
    }
    return response()->json(['status' => 'ok']);
})->middleware('auth');

Route::get('/password', function () {
    return view('auth.password-reset');
});

Route::get('/password/change', function () {
    return view('auth.change-password');
});

Route::get('/conversations',[ConversationController::class,'index'])->middleware('auth')->name('conversations.show');
Route::post('/conversations',[ConversationController::class,'store'])->middleware('auth')->name('conversations.store');

Route::Post('/message/{conversation}',[MessageController::class ,'store'])->middleware('auth')->name('messages.store');
Route::delete('/message/{conversation}',[MessageController::class,'destroy'])->middleware('auth')->name('messages.destroy');

require __DIR__.'/auth.php';


use App\Events\TestMessage;

Route::get('/test-broadcast', function () {
    event(new TestMessage('Hello from Laravel!'));
    return 'Event sent!';
});
use App\Events\ChatMessage;

Route::get('/send-chat/{msg}', function($msg){
    event(new ChatMessage(auth()->getUser()->user ?? 'TestUser', $msg));
    return 'Message sent!: ' . $msg;
});
