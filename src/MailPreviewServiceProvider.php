<?php

namespace Themsaid\MailPreview;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class MailPreviewServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/mailpreview.php' => config_path('mailpreview.php')], 'config');

        $this->app['router']->group(['middleware' => $this->middleware()], function ($router) {
            $router->get('/mailpreview')->uses(MailPreviewController::class . '@index')->name('mailpreview');
            $router->get('/mailpreview/{path}.eml')->uses(MailPreviewController::class . '@download')->name('mailpreview_eml');
            $router->get('/mailpreview/{path}.html')->uses(MailPreviewController::class . '@html')->name('mailpreview_html');
            $router->get('/mailpreview/view-source/{path}.html')->uses(MailPreviewController::class . '@source')->name('mailpreview_source');
            $router->get('/mailpreview/{path}.txt')->uses(MailPreviewController::class . '@text')->name('mailpreview_text');
            $router->get('/mailpreview/{path}')->uses(MailPreviewController::class . '@show')->name('mailpreview.show');
        });

        if ($this->app['config']['mail.driver'] != 'preview') {
            return;
        }

        if ($this->app['config']['mailpreview.show_link_to_preview']) {
            $this->app[Kernel::class]->pushMiddleware(MailPreviewMiddleware::class);

            $this->publishes([__DIR__ . '/resources/views/notification.blade.php' => resource_path('views/vendor/mailpreview/notification.blade.php')], 'views');

            $this->loadViewsFrom(__DIR__ . '/resources/views', 'mailpreview');
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
            __DIR__.'/config/mailpreview.php', 'mailpreview'
        );

        $this->app->register(MailProvider::class);
    }

    /**
     * The array of middleware for the preview route.
     *
     * @return array
     */
    private function middleware()
    {
        return array_merge(
            (array) $this->app['config']['mailpreview.middleware'],
            [\Illuminate\Session\Middleware\StartSession::class]
        );
    }
}
