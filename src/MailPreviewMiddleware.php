<?php

namespace Themsaid\MailPreview;

use Closure;
use Illuminate\Http\Response;

class MailPreviewMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (
            $request->hasSession() &&
            $response instanceOf Response &&
            $previewPath = $request->session()->get('mail_preview_path')
        ) {
            $this->addLinkToResponse($response, $previewPath);

            $request->session()->forget('mail_preview_path');
        }

        return $response;
    }

    /**
     * Modify the response to add link to the email preview.
     *
     * @param $response
     * @param $previewPath
     */
    private function addLinkToResponse($response, $previewPath)
    {
        if (app()->runningInConsole()) {
            return;
        }

        $content = $response->getContent();

        $notification = view('mailpreview::notification', [
            'timeout' => intval(config('mailpreview.popup_timeout', 8000)),
            'url' => route('mailpreview.show', $previewPath),
        ]);

        $bodyPosition = strripos($content, '</body>');

        if (false !== $bodyPosition) {
            $content = substr($content, 0, $bodyPosition) . $notification . substr($content, $bodyPosition);
        }

        $response->setContent($content);
    }
}
