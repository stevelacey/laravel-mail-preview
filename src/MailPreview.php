<?php

namespace Steve\LaravelMailPreview;

use Closure;

class MailPreview
{
    /**
     * The callback that should be used to authenticate MailPreview users.
     *
     * @var \Closure
     */
    public static $authUsing;

    /**
     * Set the callback that should be used to authenticate MailPreview users.
     *
     * @param  \Closure  $callback
     * @return static
     */
    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;

        return new static;
    }

    /**
     * Determine if the given request can access the MailPreview inbox.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }
}
