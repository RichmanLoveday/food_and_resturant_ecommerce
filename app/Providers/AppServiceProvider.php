<?php

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }

        Paginator::useBootstrapFive();

        // ✅ Prevent failure if settings table does not exist
        if (!Schema::hasTable('settings')) {
            return;
        }

        $keys = [
            'pusher_key',
            'pusher_secret',
            'pusher_app_id',
            'pusher_cluster'
        ];

        $pusherConf = Setting::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        config([
            'broadcasting.connections.pusher.key' => $pusherConf['pusher_key'] ?? '',
            'broadcasting.connections.pusher.secret' => $pusherConf['pusher_secret'] ?? '',
            'broadcasting.connections.pusher.app_id' => $pusherConf['pusher_app_id'] ?? '',
            'broadcasting.connections.pusher.options.cluster' => $pusherConf['pusher_cluster'] ?? '',
        ]);
    }
}
