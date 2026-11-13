<?php

namespace App\Providers;

use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::viaRequest('custom-token', function (Request $request) {
            $token = $request->bearerToken();
            if (!$token) {
                return null;
            }

            $apiToken = ApiToken::where('token', $token)->with('user')->first();
            if ($apiToken && !$apiToken->isExpired()) {
                $apiToken->update(['last_used_at' => now()]);
                return $apiToken->user;
            }

            return null;
        });
    }
}
