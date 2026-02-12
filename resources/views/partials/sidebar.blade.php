                <div class="lg:col-span-1 ">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-20">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Menu</h2>
                        </div>
                        <nav class="p-4 space-y-1">
                            <a href="/home"
                                class="sidebar-link block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-home mr-3"></i> Fil d'actualités
                            </a>
                            <a href="{{ route('profile.show', auth()->user()->id) }}"
                                class="sidebar-link block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-user mr-3"></i> Mon profil
                            </a>
                            <a href="{{ route('friends.show', auth()->user()->id) }}"
                                class="sidebar-link block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-users mr-3"></i> Amis
                            </a>
                            <a href="/search"
                                class="sidebar-link block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-search mr-3"></i> Rechercher
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="sidebar-link block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-edit mr-3"></i> Modifier profil
                            </a>
                            <a href="/password/change"
                                class="sidebar-link block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-key mr-3"></i> Changer mot de passe
                            </a>
                        </nav>
                    </div>
                </div>
