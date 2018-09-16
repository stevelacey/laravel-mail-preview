<?php

namespace Themsaid\MailPreview;

use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;

class MailPreviewController extends BaseController
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function index()
    {
        $inbox = $this->inbox();
        $email = $inbox->first();

        return view('mailpreview::show', ['inbox' => $inbox, 'email' => $email]);
    }

    public function show($path)
    {
        $inbox = $this->inbox();
        $email = $inbox->firstWhere('path', $path);

        if (!$email) {
            return redirect(route('mailpreview'));
        }

        return view('mailpreview::show', ['inbox' => $inbox, 'email' => $email]);
    }

    public function download($path)
    {
        return $this->render(config('mailpreview.path') . "/{$path}.eml", 'text/plain');
    }

    public function html($path)
    {
        return $this->render(config('mailpreview.path') . "/{$path}.html", 'text/html');
    }

    public function source($path)
    {
        return $this->render(config('mailpreview.path') . "/{$path}.html", 'text/plain');
    }

    public function text($path)
    {
        return $this->render(config('mailpreview.path') . "/{$path}.txt", 'text/plain');
    }

    protected function inbox()
    {
        return collect(glob(config('mailpreview.path') . '/*.eml'))
            ->sort()
            ->reverse()
            ->map(function ($realpath) {
                return $this->process($realpath);
            });
    }

    protected function process($realpath)
    {
        $eml = file_get_contents($realpath);

        preg_match('#Date: (.*)#', $eml, $matches);
        $date = $matches[1];

        preg_match('#Subject: (.*)#', $eml, $matches);
        $subject = $matches[1];

        preg_match('#From: (.*)#', $eml, $matches);
        $from = $matches[1];

        preg_match('#To: (.*)#', $eml, $matches);
        $to = $matches[1];

        return (object) [
            'subject' => $subject,
            'from' => $from,
            'to' => $to,
            'date' => $date,
            'datetime' => Carbon::parse($date),
            'path' => substr($realpath, strlen(config('mailpreview.path')) + 1, -4),
            'active' => false,
        ];
    }

    protected function render($realpath, $type)
    {
        if (file_exists($realpath)) {
            return response(file_get_contents($realpath), 200, ['Content-Type' => $type]);
        }

        return response('Not found', 404, ['Content-Type' => $type]);
    }
}
