<?php

namespace Steve\LaravelMailPreview\Http\Middleware;

use Steve\LaravelMailPreview\MailPreview;

class Authenticate
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|null
     */
    public function handle($request, $next)
    {
        if (config('mail.driver') != 'preview') {
            return abort(503);
        }

        return MailPreview::check($request) ? $next($request) : abort(403);
    }
}
