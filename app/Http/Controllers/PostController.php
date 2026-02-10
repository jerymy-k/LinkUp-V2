<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function edit(Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403);
        return view('pages.updating-post', compact('post'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'video' => 'nullable|mimetypes:video/mp4,video/webm|max:10240',
        ]);

            if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('posts/images', 'public');
    }

    if ($request->hasFile('video')) {
        $data['video'] = $request->file('video')->store('posts/videos', 'public');
    }

    auth()->user()->posts()->create($data);

    return redirect()->back();
    }

    public function update(Request $request, Post $post)
    {
        // Authorization
        abort_if($post->user_id !== auth()->id(), 403);

        // Validation
        $data = $request->validate([
        'description' => 'required|string',
        'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'video'   => 'nullable|mimetypes:video/mp4,video/webm|max:10240',
        ]);

        if ($request->hasFile('image')) {

           if ($post->image) {
               Storage::disk('public')->delete($post->image);
           }

           $data['image'] = $request->file('image')
                                  ->store('posts/images', 'public');
        }

        if ($request->hasFile('video')) {

           if ($post->video) {
               Storage::disk('public')->delete($post->video);
            }

            $data['video'] = $request->file('video')
                                  ->store('posts/videos', 'public');
        }

           $post->update($data);

           return redirect()->route('profile.show',auth()->id());
        }

        public function destroy(Post $post)
        {
            abort_if($post->user_id !== auth()->id(), 403);

            $post->delete();

            return redirect()->route('profile.show',auth()->user()->id);
        }
}
