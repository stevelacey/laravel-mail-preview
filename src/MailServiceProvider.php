<?php

namespace Steve\LaravelMailPreview;

use Illuminate\Mail\MailServiceProvider as BaseMailServiceProvider;
use Swift_Mailer;

class MailServiceProvider extends BaseMailServiceProvider
{
    /**
     * Register the Swift Mailer instance.
     *
     * @return void
     */
    public function registerSwiftMailer()
    {
        if (config('mail.driver') == 'preview') {
            $this->registerPreviewSwiftMailer();
        } else {
            parent::registerSwiftMailer();
        }
    }

    /**
     * Register the Preview Swift Mailer instance.
     *
     * @return void
     */
    protected function registerPreviewSwiftMailer()
    {
        $this->app->singleton('swift.mailer', function($app) {
            return new Swift_Mailer(
                new PreviewTransport(
                    $app->make('Illuminate\Filesystem\Filesystem'),
                    config('mailpreview.path'),
                    config('mailpreview.maximum_lifetime')
                )
            );
        });
    }
}
