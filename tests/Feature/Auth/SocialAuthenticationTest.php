<?php

namespace Tests\Feature\Auth;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SocialAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    
    private function fakeGoogleUser(
        string $id,
        string $name,
        ?string $email
    ): void {
        Role::create([
            'name' => 'user',
        ]);
        
        $googleUser = new SocialiteUser();
        
        $googleUser->map([
            'id' => $id,
            'name' => $name,
            'email' => $email,
        ]);
        
        Socialite::fake('google', $googleUser);
    }
    
    public function test_existing_social_account_logs_user_in(): void
    {
        $user = User::factory()->create();
        
        SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => 'google-123',
        ]);
        
        $this->fakeGoogleUser(
            'google-123',
            $user->name,
            $user->email
        );
        
        $response = $this->get(route('social.callback', [
            'provider' => 'google',
        ]));
        
        $response->assertRedirect();
        
        $this->assertAuthenticatedAs($user);
    }
    
    public function test_existing_user_is_linked_when_logging_in_with_google(): void
    {
        $user = User::factory()->create([
            'email' => 'manuel@example.com',
        ]);
        
        $this->fakeGoogleUser(
            'google-456',
            'Manuel Jimenez',
            'manuel@example.com'
        );
        
        $response = $this->get(route('social.callback', [
            'provider' => 'google',
        ]));
        
        $response->assertRedirect();
        
        $this->assertAuthenticatedAs($user);
        
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => 'google-456',
        ]);
    }
    
    public function test_new_google_user_is_created(): void
    {
        $this->fakeGoogleUser(
            'google-789',
            'New Google User',
            'newuser@example.com'
        );
        
        $response = $this->get(route('social.callback', [
            'provider' => 'google',
        ]));
        
        $response->assertRedirect();

        $user = User::where('email', 'newuser@example.com')->first();

        $this->assertNotNull($user);

        $this->assertAuthenticatedAs($user);
        
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => 'google-789',
        ]);
        
        $this->assertTrue($user->hasRole('user'));
    }
    
    public function test_google_login_fails_when_email_is_not_available(): void
    {
        $this->fakeGoogleUser(
            'google-no-email',
            'Google User',
            null
        );
        
        $response = $this->get(route('social.callback', [
            'provider' => 'google',
        ]));
        
        $response->assertRedirect(route('login'));
        
        $this->assertGuest();
        
        $this->assertDatabaseMissing('social_accounts', [
            'provider' => 'google',
            'provider_id' => 'google-no-email',
        ]);
    }
    
    public function test_existing_social_account_takes_precedence_over_email(): void
    {
        $socialAccountUser = User::factory()->create([
            'email' => 'google@example.com',
        ]);
        
        SocialAccount::factory()->create([
            'user_id' => $socialAccountUser->id,
            'provider' => 'google',
            'provider_id' => 'google-existing',
        ]);
        
        $this->fakeGoogleUser(
            'google-existing',
            $socialAccountUser->name,
            'google@example.com'
        );
        
        $response = $this->get(route('social.callback', [
            'provider' => 'google',
        ]));
        
        $response->assertRedirect();
        
        $this->assertAuthenticatedAs($socialAccountUser);
    }
    
    public function test_google_redirects_to_google(): void
    {
        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturnSelf();
        
        Socialite::shouldReceive('redirect')
            ->once()
            ->andReturn(redirect()->away('https://accounts.google.com/o/oauth2/auth'));
        
        $response = $this->get(route('social.auth', [
            'provider' => 'google',
        ]));
        
        $response->assertRedirect('https://accounts.google.com/o/oauth2/auth');
    }
    
    public function test_invalid_social_provider_returns_404(): void
    {
        $response = $this->get(route('social.auth', [
            'provider' => 'invalid-provider',
        ]));
        
        $response->assertNotFound();
    }
    
    public function test_invalid_social_callback_provider_returns_404(): void
    {
        $response = $this->get(route('social.callback', [
            'provider' => 'invalid-provider',
        ]));
        
        $response->assertNotFound();
    }
}