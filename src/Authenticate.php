<?php

namespace Themsaid\MailPreview;

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
            return abort(404);
        }

        return MailPreview::check($request) ? $next($request) : abort(403);
    }
}
