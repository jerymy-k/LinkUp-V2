@extends('layouts.app')

@section('title', 'Register')

@section('content')

    <div>
        <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <div class="flex justify-center">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-link text-indigo-600 text-4xl"></i>
                            <span class="text-4xl font-bold text-indigo-600">LINKUP</span>
                        </div>
                    </div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Créer un nouveau compte
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600">
                        Ou
                        <a href="/login" id="login-link" class="font-medium text-indigo-600 hover:text-indigo-500">
                            connectez-vous à votre compte
                        </a>
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-700 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ route('register') }}" class="mt-8 space-y-6">
                    @csrf

                    <div class="rounded-md shadow-sm space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="register-firstname"
                                    class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                <input id="register-firstname" name="firstname" type="text" required
                                    class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                    placeholder="Jean">
                            </div>
                            <div>
                                <label for="register-lastname"
                                    class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                <input id="register-lastname" name="lastname" type="text" required
                                    class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                    placeholder="Dupont">
                            </div>
                        </div>

                        <div>
                            <label for="register-username" class="block text-sm font-medium text-gray-700 mb-1">Pseudo
                                unique</label>
                            <input id="register-username" name="username" type="text" required
                                class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                placeholder="jdupont">
                            <p class="mt-1 text-xs text-gray-500">Ce pseudo sera visible par les autres membres.</p>
                        </div>

                        <div>
                            <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Adresse
                                email</label>
                            <input id="register-email" name="email" type="email" required
                                class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500
                                text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                placeholder="vous@exemple.com">
                        </div>

                        <div>
                            <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Mot de
                                passe</label>
                            <div class="relative">
                                <input id="register-password" name="password" type="password" required
                                    class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                    placeholder="Créez un mot de passe sécurisé">
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    id="toggle-register-password">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="register-confirm-password"
                                class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                            <div class="relative">
                                <input id="register-confirm-password" name="password_confirmation" type="password" required
                                    class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                    placeholder="Confirmez votre mot de passe">
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    id="toggle-register-confirm-password">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                            <p id="register-password-match" class="mt-2 text-sm"></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Register button -->
                        <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-user-plus text-indigo-500 group-hover:text-indigo-400"></i>
                            </span>
                            Créer mon compte
                        </button>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Ou s’inscrire avec</span>
                            </div>
                        </div>

                        <!-- Google -->
                        <a href="{{ route('social.redirect', 'google') }}"
                            class="w-full inline-flex justify-center items-center gap-2 py-3 px-4 border border-gray-300 rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fab fa-google text-red-500"></i>
                            Google
                        </a>

                        <!-- Facebook -->
                        <a href="{{ route('social.redirect', 'facebook') }}"
                            class="w-full inline-flex justify-center items-center gap-2 py-3 px-4 border border-gray-300 rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fab fa-facebook text-blue-600"></i>
                            Facebook
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
