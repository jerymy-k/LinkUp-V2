@extends('layouts.app')

@section('title', 'Mes amis')

@section('content')

    @include('partials.header')

    <div>
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mes amis</h1>
            <p class="text-gray-600 mt-2">Gérez vos relations et demandes d'amitié</p>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-700">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm h-80 overflow-scroll [scrollbar-width:none]">
                    <div class="p-6 border-b border-gray-200 ">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-user-clock mr-2 text-indigo-600"></i>
                            Demandes d'amitié reçues
                            @if ($receivedRequests->count() > 0)
                                <span
                                    class="ml-2 inline-flex items-center justify-center h-6 w-6 rounded-full bg-red-100 text-red-800 text-xs font-bold">
                                    {{ $receivedRequests->count() }}
                                </span>
                            @endif
                        </h2>
                    </div>
                    <div class="p-6">
                        @if ($receivedRequests->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500">Aucune demande d'amitié en attente</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach ($receivedRequests as $request)
                                    <div
                                        class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-4">
                                            @if ($request->sender->profile_photo)
                                                <img src="{{ Storage::url($request->sender->profile_photo) }}"
                                                    alt="{{ $request->sender->username }}"
                                                    class="h-12 w-12 rounded-full object-cover">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-indigo-400"></i>
                                                </div>
                                            @endif

                                            <div>
                                                <h3 class="font-medium text-gray-900">{{ $request->sender->name }}</h3>
                                                <p class="text-sm text-gray-500">{{ '@ ' . $request->sender->username }}</p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $request->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex space-x-2">
                                            <form method="POST" action="{{ route('friends.accept', $request->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                    <i class="fas fa-check mr-2"></i> Accepter
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('friends.reject', $request->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Rejeter cette demande d\'amitié ?')"
                                                    class="cursor-pointer px-4 py-2 border border-red-300 text-red-700 rounded-lg font-medium hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                    <i class="fas fa-times mr-2"></i> Refuser
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm h-80 overflow-scroll [scrollbar-width:none] mt-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-paper-plane mr-2 text-indigo-600"></i> Demandes envoyées
                        </h2>
                    </div>
                    <div class="p-6">
                        @if ($sentRequests->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-paper-plane text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500">Aucune demande d'amitié envoyée</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach ($sentRequests as $request)
                                    <div
                                        class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-4">
                                            @if ($request->reciever->profile_photo)
                                                <img src="{{ Storage::url($request->reciever->profile_photo) }}"
                                                    alt="{{ $request->reciever->username }}"
                                                    class="h-12 w-12 rounded-full object-cover">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-indigo-400"></i>
                                                </div>
                                            @endif

                                            <div>
                                                <h3 class="font-medium text-gray-900">{{ $request->reciever->name }}</h3>
                                                <p class="text-sm text-gray-500">{{ '@ ' . $request->reciever->username }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    En attente depuis {{ $request->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>

                                        <form method="POST" action="{{ route('friends.cancel', $request->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Annuler cette demande d\'amitié ?')"
                                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors cursor-pointer hover:bg-red-500 hover:text-white">
                                                <i class="fas fa-trash mr-2"></i> Annuler
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white rounded-xl shadow-sm h-168 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-user-friends mr-2 text-indigo-600"></i>
                            Mes amis
                            @if ($friends->count() > 0)
                                <span
                                    class="ml-2 inline-flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 text-indigo-800 text-xs font-bold">
                                    {{ $friends->count() }}
                                </span>
                            @endif
                        </h2>
                    </div>
                    <div class="p-6">
                        @if ($friends->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500">Vous n'avez pas encore d'amis</p>
                                <p class="text-sm text-gray-400 mt-2">Recherchez des membres pour les ajouter !</p>
                            </div>
                        @else
                            <div class="space-y-4 h-[90%] overflow-scroll [scrollbar-width:none] pr-2">
                                @foreach ($friends as $friend)
                                    <div
                                        class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            @if ($friend->profile_photo)
                                                <img src="{{ Storage::url($friend->profile_photo) }}"
                                                    alt="{{ $friend->username }}"
                                                    class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-indigo-400"></i>
                                                </div>
                                            @endif

                                            <div>
                                                <h3 class="font-medium text-gray-900 text-sm">{{ $friend->name }}</h3>
                                                <span class="text-xs font-medium {{ $friend->is_online ? 'text-green-600' : 'text-gray-400' }}">
                                                    {{ $friend->is_online ? 'Online' : 'Offline' }}
                                                </span>
                                                <p class="text-xs text-gray-500">{{ '@ ' . $friend->username }}</p>
                                            </div>
                                        </div>

                                        <div class="flex space-x-2">
                                            <a href="{{ route('profile.show', $friend->id) }}"
                                                class="p-2 text-gray-500 hover:text-indigo-600 rounded-full hover:bg-indigo-50 transition-colors"
                                                title="Voir le profil">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <form method="POST" action="{{ route('friends.remove', $friend->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Supprimer cet ami ?')"
                                                    class="cursor-pointer p-2 text-gray-500 hover:text-red-600 rounded-full hover:bg-red-50 transition-colors"
                                                    title="Supprimer l'ami">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
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
    </div>
    </div>
@endsection
