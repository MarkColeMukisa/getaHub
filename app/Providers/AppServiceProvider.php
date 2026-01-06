<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Contracts\SmsServiceInterface::class, function ($app) {
            if (config('services.sms.mode') === 'real') {
                return new \App\Services\RealSmsService();
            }
            return new \App\Services\FakeSmsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-tenants', function (User $user) {
            return (bool) $user->is_admin;
        });
    }
}
