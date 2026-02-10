<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home',[HomeController::class,'index'])->middleware('auth')->name('home');

Route::get('/profile/edit', function () {
    return view('pages.updating-profile');
})->middleware('auth')->name('profile.edit');

Route::get('/profile/{user}/', [ProfileController::class, 'show'])->middleware('auth')->name('profile.show');


Route::get('/search', function () {
    return view('pages.search');
})->middleware('auth')->name('users.search');


Route::get('/password', function () {
    return view('auth.password-reset');
});

Route::get('/password/change' , function(){
    return view('auth.change-password');
})->middleware('auth');

Route::patch('/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');
Route::post('/search', [SearchController::class, 'find'])->middleware('auth')->name('users.find');
Route::post('friend', [FriendController::class, 'send'])->middleware('auth')->name('friend.sendRequest');
Route::get('/friend/{user}', [FriendController::class, 'show'])->middleware('auth')->name('friends.show');
Route::patch('/friend/{friendRequest}/accept', [FriendController::class, 'accept'])->middleware('auth')->name('friends.accept');
Route::delete('/friend/{friendRequest}/reject', [FriendController::class, 'reject'])->middleware('auth')->name('friends.reject');
Route::delete('/friend/{friendRequest}/cancel', [FriendController::class, 'cancel'])->middleware('auth')->name('friends.cancel');
Route::delete('/friend/{user}/remove', [FriendController::class, 'remove'])->middleware('auth')->name('friends.remove');

Route::post('/post/store',[PostController::class, 'store'])->middleware('auth')->name('post.store');
Route::delete('/post/{post}/delete}',[PostController::class, 'destroy'])->middleware('auth')->name('post.destroy');
Route::get('/post/{post}/edit}',[PostController::class, 'edit'])->middleware('auth')->name('post.edit');
Route::put('/post/{post}/update}',[PostController::class, 'update'])->middleware('auth')->name('post.update');

Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('posts.like');
Route::delete('/posts/{post}/like', [LikeController::class, 'destroy'])->name('posts.unlike');

Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

require __DIR__.'/auth.php';
