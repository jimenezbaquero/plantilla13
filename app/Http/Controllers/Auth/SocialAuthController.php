<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    private array $providers;
    
    public function __construct(){
        $this->providers = config('socialite.providers');
    }
    
    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, $this->providers), 404);
        return Socialite::driver($provider)->redirect();
    }
    
    public function callback(string $provider)
    {
        abort_unless(in_array($provider, $this->providers), 404);
        $socialUser = Socialite::driver($provider)->user();
        
        if (!$socialUser->getEmail()) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'No se ha podido obtener el email de tu cuenta de ' . ucfirst($provider) . '.',
                ]);
        }
        
        try {
            $user = DB::transaction(function () use ($provider, $socialUser) {
                $socialAccount = SocialAccount::where('provider', $provider)
                    ->where('provider_id', $socialUser->getId())
                    ->first();
                
                if ($socialAccount) {
                    return $socialAccount->user;
                }
                
                $user = User::where('email', $socialUser->getEmail())->first();
                
                if (!$user) {
                    $user = User::create([
                        'name' => $socialUser->getName()
                            ?? $socialUser->getNickname()
                                ?? 'Usuario',
                        'email' => $socialUser->getEmail(),
                        'password' => Str::random(32),
                        'email_verified_at' => now(),
                    ]);
                    
                    $user->assignRole('usuario');
                }
                
                SocialAccount::create([
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'token' => $socialUser->token,
                    'refresh_token' => $socialUser->refreshToken,
                    'expires_at' => $socialUser->expiresIn
                        ? now()->addSeconds($socialUser->expiresIn)
                        : null,
                ]);
                
                return $user;
            });
            
            Auth::login($user, true);
            request()->session()->regenerate();
            
            return redirect()->intended(route('dashboard'));
            
        } catch (Throwable $e) {
            Log::error($e);
            
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Ha ocurrido un error generando su usuario',
                ]);
        }
    }
}