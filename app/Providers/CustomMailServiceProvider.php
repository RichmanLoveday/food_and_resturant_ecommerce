<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\MailSettingsService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;


class CustomMailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MailSettingsService::class, function () {
            return new MailSettingsService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $settingsService = $this->app->make(MailSettingsService::class);
        $settingsService->setGlobalSettings();
    }
}