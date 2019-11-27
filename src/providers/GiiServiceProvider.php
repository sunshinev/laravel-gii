<?php

namespace Sunshinev\Gii\Providers;

use Illuminate\Support\ServiceProvider;

class GiiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 扩展视图
        $this->loadViewsFrom(__DIR__.'/../views/', 'gii_views');

        $this->publishes([
            __DIR__.'/../assets' => public_path('/gii_assets'),
        ], 'laravel-gii');
    }
}
