<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class MailSettingsService
{
    public function getSettings()
    {
        $mailSetting = Cache::rememberForever('mail_settings', function () {
            $keys = [
                'mail_driver',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_form_address',
                'mail_receive_address',
            ];

            //? get the key and value of the specific keys found
            return Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();
        });

        return $mailSetting;
    }


    public function clearCacheSettings(): void
    {
        Cache::forget('mail_settings');
    }
}
