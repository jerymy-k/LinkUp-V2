@extends('layouts.app')

@section('title', 'Messages')

@section('content')

@include('partials.header')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-[calc(100vh-12rem)]">
        <!-- Sidebar - Liste des conversations -->
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
            <!-- En-tête -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-comments mr-2 text-indigo-600"></i> Messages
                    </h2>
                </div>
            </div>
            
            <!-- Liste des conversations -->
            <div class="flex-1 overflow-y-auto">
                @if($conversations->isEmpty())
                    <div class="p-6 text-center">
                        <div class="text-gray-400 text-5xl mb-4">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune conversation</h3>
                        <p class="text-gray-500 text-sm mb-4">Commencez une nouvelle conversation avec un ami !</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($conversations as $conversation)
                            
                            <a href="{{ route('conversations.show', $conversation->id) }}" 
                               class="conversation-item block p-4 hover:bg-gray-50 transition-colors {{ $isActive ? 'bg-indigo-50' : '' }}"
                               data-conversation-id="{{ $conversation->id }}">
                                <div class="flex items-center space-x-3">
                                    <!-- Avatar -->
                                    <div class="relative flex-shrink-0">
                                        @if($otherUser->profile_photo)
                                            <img src="{{ Storage::url($otherUser->profile_photo) }}" 
                                                 alt="{{ $otherUser->name }}" 
                                                 class="h-12 w-12 rounded-full object-cover">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <i class="fas fa-user text-indigo-400"></i>
                                            </div>
                                        @endif
                                        
                                        @if($otherUser->is_online)
                                            <div class="absolute bottom-0 right-0 h-3 w-3 bg-green-500 rounded-full border-2 border-white"></div>
                                        @endif
                                    </div>
                                    
                                    <!-- Infos conversation -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-semibold text-gray-900 truncate">
                                                {{ $otherUser->name }}
                                            </h3>
                                            @if($lastMessage)
                                                <span class="text-xs text-gray-500">
                                                    {{ $lastMessage->created_at->diffForHumans(null, true) }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-sm text-gray-500 truncate">
                                            @if($lastMessage)
                                                @if($lastMessage->sender_id === auth()->id())
                                                    <span class="text-gray-400">Vous : </span>
                                                @endif
                                                {{ $lastMessage->content }}
                                            @else
                                                <span class="text-gray-400">Aucun message</span>
                                            @endif
                                        </p>
                                        
                                        @if($unreadCount > 0)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-indigo-600 rounded-full">
                                                    {{ $unreadCount }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Zone principale - Conversation sélectionnée -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
            @if(isset($selectedConversation))
                @php
                    $otherUser = $selectedConversation->user1_id === auth()->id() ? $selectedConversation->user2 : $selectedConversation->user1;
                @endphp
                
                <!-- En-tête de la conversation -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('profile.show', $otherUser->username) }}" class="flex items-center space-x-3">
                                <div class="relative">
                                    @if($otherUser->profile_photo)
                                        <img src="{{ Storage::url($otherUser->profile_photo) }}" 
                                             alt="{{ $otherUser->name }}" 
                                             class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <i class="fas fa-user text-indigo-400"></i>
                                        </div>
                                    @endif
                                    
                                    @if($otherUser->is_online)
                                        <div class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-green-500 rounded-full border-2 border-white"></div>
                                    @endif
                                </div>
                                <div>
                                    <h2 class="font-semibold text-gray-900">{{ $otherUser->name }}</h2>
                                    <p class="text-sm text-gray-500">
                                        @if($otherUser->is_online)
                                            <span class="text-green-600">● En ligne</span>
                                        @else
                                            Dernière connexion {{ $otherUser->last_seen?->diffForHumans() ?? 'inconnue' }}
                                        @endif
                                    </p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('profile.show', $otherUser->username) }}" 
                               class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                               title="Voir le profil">
                                <i class="fas fa-user"></i>
                            </a>
                            <button onclick="toggleConversationMenu()" 
                                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Menu de conversation -->
                    <div id="conversation-menu" class="absolute right-6 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden">
                        <div class="py-1">
                            <form method="POST" action="{{ route('conversations.mark-read', $selectedConversation->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-check-double mr-2"></i> Marquer comme lu
                                </button>
                            </form>
                            
                            <hr class="my-1 border-gray-200">
                            
                            <form method="POST" action="{{ route('conversations.block', $otherUser->id) }}">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Bloquer {{ $otherUser->name }} ? Vous ne pourrez plus recevoir de messages de cette personne.')"
                                        class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                    <i class="fas fa-ban mr-2"></i> Bloquer l'utilisateur
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Zone des messages -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4" data-conversation-id="{{ $selectedConversation->id }}">
                    @foreach($selectedConversation->messages()->with('sender')->orderBy('created_at')->get() as $message)
                        @php
                            $isOwn = $message->sender_id === auth()->id();
                        @endphp
                        
                        <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                            @if(!$isOwn)
                                <div class="flex-shrink-0 mr-3">
                                    @if($otherUser->profile_photo)
                                        <img src="{{ Storage::url($otherUser->profile_photo) }}" 
                                             alt="{{ $otherUser->name }}" 
                                             class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <i class="fas fa-user text-indigo-400 text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="flex flex-col {{ $isOwn ? 'items-end' : 'items-start' }} max-w-[70%]">
                                <div class="group relative">
                                    <div class="px-4 py-2 rounded-2xl {{ $isOwn ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                                        <p class="text-sm whitespace-pre-line break-words">{{ $message->content }}</p>
                                    </div>
                                    
                                    @if($isOwn)
                                        <div class="absolute top-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity -mt-1 -mr-1">
                                            <div class="flex space-x-1">
                                                <form method="POST" action="{{ route('messages.destroy', $message->id) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Supprimer ce message ?')"
                                                            class="p-1 bg-red-500 text-white rounded-full hover:bg-red-600 text-xs">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex items-center space-x-2 mt-1 text-xs text-gray-500">
                                    <span>{{ $message->created_at->format('H:i') }}</span>
                                    @if($isOwn)
                                        @if($message->is_read)
                                            <span class="text-indigo-600">
                                                <i class="fas fa-check-double"></i> Lu
                                            </span>
                                        @else
                                            <span class="text-gray-400">
                                                <i class="fas fa-check"></i> Envoyé
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <div id="typing-indicator" class="hidden">
                        <div class="flex items-center space-x-2">
                            <div class="flex-shrink-0">
                                @if($otherUser->profile_photo)
                                    <img src="{{ Storage::url($otherUser->profile_photo) }}" 
                                         alt="{{ $otherUser->name }}" 
                                         class="h-6 w-6 rounded-full object-cover">
                                @else
                                    <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <i class="fas fa-user text-indigo-400 text-xs"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="bg-gray-100 rounded-full px-4 py-2">
                                <div class="flex space-x-1">
                                    <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                                    <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Zone de saisie -->
                <div class="p-6 border-t border-gray-200">
                    <form id="message-form" method="POST" action="{{ route('messages.store', $selectedConversation->id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                        
                        <div class="flex items-end space-x-2">
                            <div class="flex-1 relative">
                                <textarea name="content" 
                                          id="message-input"
                                          rows="1"
                                          placeholder="Écrivez votre message..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none resize-none"
                                          oninput="autoResize(this)"></textarea>
                                
                                <div class="absolute right-2 bottom-2 flex space-x-2">
                                    <label class="cursor-pointer text-gray-400 hover:text-indigo-600">
                                        <i class="fas fa-image text-lg"></i>
                                        <input type="file" name="image" accept="image/*" class="hidden">
                                    </label>
                                    <label class="cursor-pointer text-gray-400 hover:text-indigo-600">
                                        <i class="fas fa-paperclip text-lg"></i>
                                        <input type="file" name="file" accept="*/*" class="hidden">
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" 
                                    id="send-button"
                                    class="flex-shrink-0 p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                    
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <!-- Aucune conversation sélectionnée -->
                <div class="flex flex-col items-center justify-center h-full p-8">
                    <div class="text-gray-300 text-7xl mb-6">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Vos messages</h3>
                    <p class="text-gray-500 text-center mb-8 max-w-md">
                        Sélectionnez une conversation dans la liste ou commencez une nouvelle discussion avec un ami.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Nouvelle Conversation -->
<div id="new-conversation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                Nouvelle conversation
            </h3>
            <button  class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Rechercher un ami
            </label>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" 
                       id="search-friends"
                       placeholder="Nom ou pseudo..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">
            </div>
        </div>
        
        <div id="friends-list" class="max-h-64 overflow-y-auto mb-4 space-y-2">
            @foreach(auth()->user()->friends as $friend)
                <div class="friend-item flex items-center justify-between p-3 rounded-lg hover:bg-gray-50"">
                    <div class="flex items-center space-x-3">
                        @if($friend->profile_photo)
                            <img src="{{ Storage::url($friend->profile_photo) }}" 
                                 alt="{{ $friend->name }}" 
                                 class="h-10 w-10 rounded-full object-cover">
                        @else
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-user text-indigo-400"></i>
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ $friend->name }}</p>
                            <p class="text-sm text-gray-500">@{{ $friend->username }}</p>
                        </div>
                    </div>
                    <button  
                            class="px-3 py-1 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-comment mr-1"></i> Message
                    </button>
                </div>
            @endforeach
        </div>
        
        @if(auth()->user()->friends()->count() === 0)
            <div class="text-center py-6">
                <p class="text-gray-500 mb-2">Vous n'avez pas encore d'amis</p>
                <a href="{{ route('friends.show',auth()->user()->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    Trouver des amis
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Template pour les nouveaux messages -->
<template id="message-template">
    <div class="flex justify-end">
        <div class="flex flex-col items-end max-w-[70%]">
            <div class="px-4 py-2 rounded-2xl bg-indigo-600 text-white">
                <p class="text-sm whitespace-pre-line break-words"></p>
            </div>
            <div class="flex items-center space-x-2 mt-1 text-xs text-gray-500">
                <span class="time">Maintenant</span>
                <span class="text-gray-400">
                    <i class="fas fa-check"></i> Envoyé
                </span>
            </div>
        </div>
    </div>
</template>

@endsection