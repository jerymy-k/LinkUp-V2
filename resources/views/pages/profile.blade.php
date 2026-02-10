@extends('layouts.app')

@section('title', $user->name . ' - Profil')

@section('content')

    @include('partials.header')

    <div>
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-t-2xl shadow-lg">
            <div class="container mx-auto px-6 py-12">
                <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                    <div class="relative">
                        @if ($user->profile_photo)
                            <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}"
                                class="h-40 w-40 rounded-full border-4 border-white shadow-xl object-cover">
                        @else
                            <div
                                class="h-40 w-40 rounded-full border-4 border-white shadow-xl bg-white flex items-center justify-center">
                                <i class="fas fa-user text-indigo-400 text-7xl"></i>
                            </div>
                        @endif

                        @if (auth()->check() && auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}"
                                class="absolute bottom-0 right-0 h-10 w-10 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                    </div>

                    <div class="text-white flex-1">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                                <p class="text-xl text-indigo-100 mt-1">{{ '@ ' . $user->username }}</p>

                                @if ($user->bio)
                                    <p class="mt-4 text-lg">{{ $user->bio }}</p>
                                @endif
                            </div>

                            @if (auth()->check() && auth()->id() !== $user->id)
                                <div class="mt-4 md:mt-0">
                                    @if (auth()->user()->isFriendWith($user->id))
                                        <form method="POST" action="{{ route('friends.remove', $user->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Supprimer cet ami ?')"
                                                class="px-6 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors">
                                                <i class="fas fa-user-check mr-2"></i> Ami
                                            </button>
                                        </form>
                                    @elseif(auth()->user()->hasPendingRequestTo($user->id))
                                        <h1
                                            class="cursor-pointer bg-gray-500 text-xl font-bold px-5 py-2 rounded-md text-gray-100">
                                            Envoyée</h1>
                                    @else
                                        <form method="POST" action="{{ route('friend.sendRequest') }}">
                                            @csrf
                                            <input type="hidden" name="reciever_id" value="{{ $user->id }}">
                                            <button type="submit"
                                                class="cursor-pointer px-6 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors">
                                                <i class="fas fa-user-plus mr-2"></i> Ajouter en ami
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="mt-8 flex flex-wrap gap-6">
                            <div class="text-center">
                                <span class="block text-2xl font-bold">{{$user->posts->count()}}</span>
                                <span class="text-indigo-200">Publications</span>
                            </div>
                            <div class="text-center">
                                <span class="block text-2xl font-bold">{{$user->friends->count()}}</span>
                                <span class="text-indigo-200">Amis</span>
                            </div>
                            <div class="text-center">
                                <span class="block text-2xl font-bold">{{ $user->created_at->format('Y') }}</span>
                                <span class="text-indigo-200">Membre depuis</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-2xl shadow-lg -mt-6 relative">
            <div class="container mx-auto px-6 pb-8">
                <div class="flex border-b border-gray-200">
                    <button data-tab="posts"
                        class="tab-button py-4 px-6 font-medium text-gray-600 hover:text-indigo-600 border-transparent hover:border-indigo-500 transition-colors flex items-center space-x-2 active">
                        <i class="fas fa-newspaper"></i>
                        <span>Publications</span>
                        <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded-full ml-2">
                            {{ $posts->count() }}
                        </span>
                    </button>

                </div>

                <div class="mt-8">
                    <div>
                        @if (auth()->check() && auth()->id() === $user->id)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                                <div class="flex items-center space-x-4 mb-6">
                                    @if ($user->profile_photo)
                                        <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}"
                                            class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <i class="fas fa-user text-indigo-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ '@ ' . $user->username }}</p>
                                    </div>
                                </div>
                                @if ($errors->any())
                                    <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                                    <textarea name="description" rows="3" placeholder="Partagez quelque chose avec vos amis..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition-colors resize-none"></textarea>

                                    <div class="mt-4 flex justify-between items-center">
                                        <div class="flex space-x-4">
                                            <label for="image-upload"
                                                class="cursor-pointer flex items-center space-x-2 text-gray-500 hover:text-indigo-600">
                                                <i class="fas fa-image"></i>
                                                <span>Photo</span>
                                            </label>
                                            <input type="file" name="image" id="image-upload" class="hidden">
                                            <label for="video-upload"
                                                class="cursor-pointer flex items-center space-x-2 text-gray-500 hover:text-indigo-600">
                                                <i class="fas fa-video"></i>
                                                <span>Vidéo</span>
                                            </label>
                                            <input type="file" name="video" id="video-upload" class="hidden">

                                        </div>

                                        <button type="submit"
                                            class="cursor-pointer px-6 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                            Publier
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @if ($posts->isEmpty())
                            <div class="text-center py-12">
                                <i class="fas fa-newspaper text-gray-300 text-6xl mb-4"></i>
                                <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune publication</h3>
                                <p class="text-gray-500">
                                    @if (auth()->check() && auth()->id() === $user->id)
                                        Partagez votre première publication !
                                    @else
                                        {{ $user->name }} n'a pas encore publié de contenu.
                                    @endif
                                </p>
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach ($posts as $post)
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                        <div class="p-6 border-b border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    @if ($post->user->profile_photo)
                                                        <img src="{{ Storage::url($post->user->profile_photo) }}"
                                                            alt="{{ $post->user->name }}"
                                                            class="h-10 w-10 rounded-full object-cover">
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                            <i class="fas fa-user text-indigo-400"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900">{{ $post->user->name }}
                                                        </h4>
                                                        <p class="text-sm text-gray-500">
                                                            {{ '@ ' . $post->user->username }} •
                                                            {{ $post->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>

                                                @if (auth()->check() && auth()->id() === $post->user_id)
                                                    <div class="flex gap-2">
                                                        @if (auth()->id() === $post->user_id)
                                                            <div
                                                                class="mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 ">
                                                                <form method="POST"
                                                                    action="{{ route('post.destroy', $post->id) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        onclick="return confirm('Supprimer cette publication ?')"
                                                                        class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 cursor-pointer ">
                                                                        <i class="fas fa-trash mr-2"></i> Supprimer
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div
                                                                class="mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 ">
                                                                <form method="GET"
                                                                    action="{{ route('post.edit', $post->id) }}">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="block w-full text-left px-4 py-2 text-green-600 hover:bg-green-50 cursor-pointer ">
                                                                        <i class="fas fa-edit mr-2"></i> edit
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="p-6">
                                            <p class="text-gray-800 whitespace-pre-line">{{ $post->description }}</p>

                                            @if ($post->image)
                                                <img src="{{ Storage::url($post->image) }}" class="rounded-lg mt-2">
                                            @endif

                                            @if ($post->video)
                                                <video controls class="rounded-lg mt-2 w-full">
                                                    <source src="{{ Storage::url($post->video) }}">
                                                </video>
                                            @endif
                                        </div>

                                        <div class="px-6 py-4 border-t border-gray-200">
                                            <div class="flex items-center justify-between text-gray-500">
                                                <div class="flex items-center space-x-4">
                                                    <button class="flex items-center space-x-1 hover:text-red-500">
                                                        <i class="far fa-heart"></i>
                                                        <span>{{ $post->likes()->count() }}</span>
                                                    </button>
                                                    <button class="flex items-center space-x-1 hover:text-blue-500">
                                                        <i class="far fa-comment"></i>
                                                        <span>{{ $post->comments()->count() }}</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mt-4 grid grid-cols-2 border-t border-gray-100 pt-4"
                                                x-data="{ open: false }">
                                                @if ($post->isLikedBy(auth()->user()))
                                                    <form method="POST" action="{{ route('posts.unlike', $post->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="cursor-pointer flex items-center justify-center space-x-2 text-gray-600 hover:text-red-500 py-2 rounded-lg hover:bg-gray-50">
                                                            <i class="fas fa-heart text-red-500"></i>
                                                            <span class="font-medium">J'aime</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('posts.like', $post->id) }}">
                                                        @csrf
                                                        <button type="submit"
                                                            class="cursor-pointer flex items-center justify-center space-x-2 text-gray-600 hover:text-red-500 py-2 rounded-lg hover:bg-gray-50">
                                                            <i class="far fa-heart"></i>
                                                            <span class="font-medium">J'aime</span>
                                                        </button>
                                                    </form>
                                                @endif

                                                <button @click="open = true"
                                                    class="text-gray-500 hover:text-indigo-600 flex items-center space-x-2 cursor-pointer">
                                                    <i class="fas fa-comment"></i>
                                                    <span>Commenter</span>
                                                </button>
                                                <div x-show="open" x-cloak
                                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

                                                    <div @click.outside="open = false"
                                                        class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <h2 class="text-lg font-semibold">Commentaires</h2>
                                                            <button @click="open = false">
                                                                <i class="fas fa-times text-gray-500"></i>
                                                            </button>
                                                        </div>

                                                        <div class="space-y-4 max-h-80 overflow-y-auto">

                                                            @forelse($post->comments as $comment)
                                                                <div class="flex space-x-3">

                                                                    @if ($comment->user?->profile_photo)
                                                                        <img src="{{ Storage::url($comment->user->profile_photo) }}"
                                                                            class="h-8 w-8 rounded-full object-cover">
                                                                    @else
                                                                        <div class="h-8 w-8 rounded-full bg-gray-200">
                                                                        </div>
                                                                    @endif

                                                                    <div class="flex-1">
                                                                        <p class="text-sm">
                                                                            <span class="font-medium">
                                                                                {{ $comment->user?->name ?? 'Deleted user' }}
                                                                            </span>
                                                                            {{ $comment->content }}
                                                                        </p>
                                                                        <p class="text-xs text-gray-500 mt-1">
                                                                            {{ $comment->created_at->diffForHumans() }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <p class="text-sm text-gray-500 text-center">
                                                                    Aucun commentaire pour le moment
                                                                </p>
                                                            @endforelse

                                                        </div>

                                                        <form method="POST"
                                                            action="{{ route('comments.store', $post) }}"
                                                            class="mt-4 flex space-x-3">
                                                            @csrf
                                                            <input type="text" name="content"
                                                                placeholder="Ajouter un commentaire..."
                                                                class="flex-1 px-4 py-2 border border-gray-300 rounded-full
                                        focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                                                required>
                                                            <button type="submit"
                                                                class="px-4 py-2 bg-indigo-600 text-white rounded-full">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
@endsection
