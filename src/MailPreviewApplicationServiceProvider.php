<?php

namespace Steve\LaravelMailPreview;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class MailPreviewApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->authorization();
    }

    /**
     * Configure the MailPreview authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        MailPreview::auth(function ($request) {
            return app()->environment('local') || Gate::check('viewMail', [$request->user()]);
        });
    }

    /**
     * Register the MailPreview gate.
     *
     * This gate determines who can access MailPreview in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewMail', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
