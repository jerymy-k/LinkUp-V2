@extends('layouts.app')

@section('title', 'Login')

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
                        Connexion à votre compte
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600">
                        Ou
                        <a href="/register" id="register-link" class="font-medium text-indigo-600 hover:text-indigo-500">
                            créez un nouveau compte
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

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf

                    <div class="rounded-md shadow-sm space-y-4">
                        <div>
                            <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input id="login-email" name="email" type="text" required
                                class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                placeholder="vous@exemple.com">
                        </div>

                        <div>
                            <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">
                                Mot de passe
                            </label>
                            <div class="relative">
                                <input id="login-password" name="password" type="password" required
                                    class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                    placeholder="Votre mot de passe">
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    id="toggle-login-password">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember" type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                                Se souvenir de moi
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="/password" id="forgot-password-link"
                                class="font-medium text-indigo-600 hover:text-indigo-500">
                                Mot de passe oublié ?
                            </a>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Login button -->
                        <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-sign-in-alt text-indigo-500 group-hover:text-indigo-400"></i>
                            </span>
                            Se connecter
                        </button>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Ou continuer avec</span>
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
