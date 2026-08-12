<?php

namespace XShaan\Zohal;

use Illuminate\Support\ServiceProvider;
use XShaan\Zohal\Contracts\ZohalClient as ZohalClientContract;

class ZohalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/zohal.php', 'zohal');

        $this->app->singleton(ZohalClientContract::class, function ($app) {
            return new Client(
                token: (string) $app['config']->get('zohal.token', ''),
                baseUrl: (string) $app['config']->get('zohal.base_url', 'https://service.zohal.io/api/v0'),
                timeout: (int) $app['config']->get('zohal.timeout', 30),
                connectTimeout: (int) $app['config']->get('zohal.connect_timeout', 10),
                retryTimes: (int) $app['config']->get('zohal.retry.times', 2),
                retrySleep: (int) $app['config']->get('zohal.retry.sleep', 200),
                throw: (bool) $app['config']->get('zohal.throw', true),
            );
        });

        $this->app->alias(ZohalClientContract::class, Client::class);

        $this->app->singleton(Zohal::class, function ($app) {
            return new Zohal($app->make(ZohalClientContract::class));
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/zohal.php' => config_path('zohal.php'),
            ], 'zohal-config');
        }
    }
}
