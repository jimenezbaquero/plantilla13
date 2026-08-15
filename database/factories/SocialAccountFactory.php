<?php

namespace Database\Factories;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialAccountFactory extends Factory
{
    protected $model = SocialAccount::class;
    
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'provider' => 'google',
            'provider_id' => fake()->unique()->numerify('google-##########'),
            'token' => fake()->sha256(),
            'refresh_token' => null,
            'expires_at' => now()->addHour(),
        ];
    }
}