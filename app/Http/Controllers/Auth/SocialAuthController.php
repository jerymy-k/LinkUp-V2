<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, ['google', 'facebook']), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        abort_unless(in_array($provider, ['google', 'facebook']), 404);

        $socialUser = Socialite::driver($provider)->stateless()->user();

        $email = $socialUser->getEmail();
        if (!$email) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Impossible de récupérer votre email depuis ' . $provider . '.']);
        }

        $existing = User::where('email', $email)->first();
        if ($existing) {
            Auth::login($existing);
            return redirect()->route('dashboard');
        }

        $fullName = $socialUser->getName() ?? $socialUser->getNickname() ?? 'User';
        $names = $this->splitName($fullName);

        // base username: from email before @, fallback from firstname
        $baseUsername = explode('@', $email)[0] ?? $names['firstname'];
        $username = $this->generateUniqueUsername($baseUsername);

        $user = User::create([
            'email' => $email,
            'firstname' => $names['firstname'],
            'lastname' => $names['lastname'],
            'username' => $username,
            'password' => bcrypt(Str::random(32)),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    private function splitName(?string $fullName): array
    {
        $fullName = trim((string) $fullName);

        if ($fullName === '') {
            return ['firstname' => 'User', 'lastname' => ''];
        }

        $parts = preg_split('/\s+/', $fullName) ?: [];
        $firstname = $parts[0] ?? 'User';
        $lastname = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

        return ['firstname' => $firstname, 'lastname' => $lastname];
    }

    private function generateUniqueUsername(string $base): string
    {
        // keep only letters/numbers/underscore, lowercase
        $base = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $base));
        $base = $base ?: 'user';

        $username = $base;
        $i = 0;

        while (User::where('username', $username)->exists()) {
            $i++;
            $username = $base . $i; // user, user1, user2...
        }

        return $username;
    }

}
