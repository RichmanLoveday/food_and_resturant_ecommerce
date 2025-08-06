<?php

namespace App\Services;

use App\Models\PaymentGatewaySetting;
use Cache;

class PaymentGatewaySettingService
{
    public function getSetting()
    {
        return Cache::rememberForever('gatewaySettings', function () {
            return PaymentGatewaySetting::pluck('value', 'key')->toArray();
        });
    }

    public function setGlobalSettings(): void
    {
        $settings = $this->getSetting();
        config()->set('gatewaySettings', $settings);
    }


    public function clearCacheSettings(): void
    {
        Cache::forget('gatewaySettings');
    }
}
