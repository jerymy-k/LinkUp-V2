<header id="main-header" class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-link text-indigo-600 text-2xl"></i>
                    <span class="text-2xl font-bold text-indigo-600">LINKUP</span>
                </div>
            </div>

            <nav class="hidden md:flex space-x-4">
                <a href="/home"
                    class="nav-link px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-home mr-1"></i> Accueil
                </a>
                <a href="/search"
                    class="nav-link px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-search mr-1"></i> Recherche
                </a>
                <a href="{{ route('profile.show', auth()->user()->id) }}"
                    class="nav-link px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-user mr-1"></i> Profil
                </a>
                <a href="{{ route('friends.show', auth()->user()->id) }}"
                    class="nav-link px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-user mr-1"></i> Amis
                </a>
                <a href="{{ route('conversations.show', auth()->user()->id) }}"
                    class="nav-link px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-message mr-1"></i> Conversations
                </a>
            </nav>

            <div class="flex items-center space-x-3">
                <div class="relative">
                    @if (auth()->user()->profile_photo)
                        <img id="user-avatar"
                            class="h-10 w-10 rounded-full border-2 border-gray-200 object-cover cursor-pointer"
                            src="{{ Storage::url(auth()->user()->profile_photo) }}" alt="Avatar">
                    @else
                        <img id="user-avatar"
                            class="h-10 w-10 rounded-full border-2 border-gray-200 object-cover cursor-pointer"
                            src="https://intranet.youcode.ma/storage/users/profile/0.jpg" alt="Avatar">
                    @endif
                </div>

                <a href="{{ route('logout') }}"
                    class="hidden md:inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                </a>

                <button id="mobile-menu-button"
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-indigo-600 hover:bg-gray-100 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="md:hidden hidden pb-3 border-t border-gray-200">
            <div class="pt-2 space-y-1">
                <a href="/home" data-page="home"
                    class="mobile-nav-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-100">
                    <i class="fas fa-home mr-2"></i> Accueil
                </a>
                <a href="/search" data-page="search"
                    class="mobile-nav-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-100">
                    <i class="fas fa-search mr-2"></i> Recherche
                </a>
                <a href="{{ route('profile.show', auth()->user()->id) }}" data-page="profile"
                    class="mobile-nav-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-100">
                    <i class="fas fa-user mr-2"></i> Profil
                </a>
                <a href="{{ route('logout') }}"
                    class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-100">
                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>
</header>
