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


    public function setGlobalSettings()
    {
        $mailSettings = $this->getSettings();

        //? check if mailsettings contains datas
        if ($mailSettings) {
            config()->set('mail.default', $mailSettings['mail_driver']);
            config()->set('mail.mailers.smtp.port', $mailSettings['mail_port']);
            config()->set('mail.mailers.smtp.host', $mailSettings['mail_host']);
            config()->set('mail.mailers.smtp.username', $mailSettings['mail_username']);
            config()->set('mail.mailers.smtp.encryption', $mailSettings['mail_encryption']);
            config()->set('mail.mailers.smtp.password', $mailSettings['mail_password']);
            config()->set('mail.from.address', $mailSettings['mail_form_address']);
        }
    }

    public function clearCacheSettings(): void
    {
        Cache::forget('mail_settings');
    }
}