<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // در محیط هاست (production) در صورت وجود پوشه public_html در سطح والد، مسیر public تغییر می‌کند
        if (app()->environment('production') && is_dir(base_path('../public_html'))) {
            $this->app->usePublicPath(base_path('../public_html'));
        }

        $this->app->extend('url', function ($url, $app) {
            $custom = new \App\Routing\UrlGenerator(
                $app['router']->getRoutes(),
                $app->rebinding('request', function ($app, $request) {
                    $app['url']->setRequest($request);
                }),
                $app['config']['app.asset_url']
            );
            $custom->setSessionResolver(function () use ($app) {
                return $app['session'] ?? null;
            });
            $custom->setKeyResolver(function () use ($app) {
                $config = $app->make('config');
                return [$config->get('app.key'), ...($config->get('app.previous_keys') ?? [])];
            });
            return $custom;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');
    }
}
