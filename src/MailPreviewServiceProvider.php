<?php

namespace Steve\LaravelMailPreview;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Steve\LaravelMailPreview\Contracts\EntriesRepository;
use Steve\LaravelMailPreview\Contracts\PrunableRepository;
use Steve\LaravelMailPreview\Contracts\ClearableRepository;
use Steve\LaravelMailPreview\Storage\DatabaseEntriesRepository;

class MailPreviewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('mail.driver') != 'preview') {
            return;
        }

        Route::middlewareGroup('mailpreview', config('mailpreview.middleware', []));

        $this->registerRoutes();
        $this->registerPublishing();

        $this->loadViewsFrom(
            __DIR__.'/../resources/views', 'mailpreview'
        );
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group([
            'namespace' => 'Steve\LaravelMailPreview\Http\Controllers',
            'middleware' => config('mailpreview.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/mailpreview.php' => config_path('mailpreview.php'),
            ], 'mailpreview-config');

            $this->publishes([
                __DIR__.'/../stubs/MailPreviewServiceProvider.stub' => app_path('Providers/MailPreviewServiceProvider.php'),
            ], 'mailpreview-provider');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/mailpreview.php', 'mailpreview'
        );

        $this->app->register(MailServiceProvider::class);

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }
}
