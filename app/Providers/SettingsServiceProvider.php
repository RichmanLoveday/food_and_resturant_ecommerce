<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingsService::class, function () {
            return new SettingsService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //    \Log::info('Boot started SettingsServiceProvider, memory: ' . memory_get_usage(true));
           
        $settingsService = $this->app->make(SettingsService::class);
        $settingsService->setGlobalSettings();
    }
}