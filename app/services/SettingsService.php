<?php

namespace App\services;

use App\Models\Setting;
use Cache;

class SettingsService
{
    public function getSetting()
    {
        return Cache::rememberForever('settings', function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    public function setGlobalSettings(): void
    {
        $settings = $this->getSetting();
        config()->set('settings', $settings);
    }


    public function clearCacheSettings(): void
    {
        Cache::forget('settings');
    }
}
