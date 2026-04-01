<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\SmsServiceInterface;
use App\Models\User;
use App\Services\FakeSmsService;
use App\Services\RealSmsService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(function ($app): SmsServiceInterface {
            if (config('services.sms.mode') === 'real') {
                return new RealSmsService;
            }

            return new FakeSmsService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-tenants', fn (User $user) => (bool) $user->is_admin);
    }
}
