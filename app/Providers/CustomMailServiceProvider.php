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
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(MailSettingsService $mailSettingsService)
    {
        $mailSettings = $mailSettingsService->getSettings();

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
}
