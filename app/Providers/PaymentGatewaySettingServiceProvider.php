<?php

namespace App\Providers;

use App\services\PaymentGatewaySettingService;
use Illuminate\Support\ServiceProvider;

class PaymentGatewaySettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PaymentGatewaySettingService::class, function () {
            return new PaymentGatewaySettingService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $settingsService = $this->app->make(PaymentGatewaySettingService::class);
        $settingsService->setGlobalSettings();
    }
}