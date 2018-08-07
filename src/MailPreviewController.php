<?php

namespace Themsaid\MailPreview;

use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;

class MailPreviewController extends BaseController
{
    public function index()
    {
        $inbox = $this->inbox();

        return view('mailpreview::show', ['inbox' => $inbox, 'email' => $email]);
    }

    public function show($path)
    {
        $inbox = $this->inbox()->map(function ($entry) use ($path) {
            $entry->active = $entry->path == $path;

            return $entry;
        });

        $email = $this->process(config('mailpreview.path') . '/' . $path . '.eml');

        return view('mailpreview::show', ['inbox' => $inbox, 'email' => $email]);
    }

    public function download($path)
    {
        $content = file_get_contents(config('mailpreview.path') . '/' . $path . '.eml');

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }

    public function html($path)
    {
        $content = file_get_contents(config('mailpreview.path') . '/' . $path . '.html');

        return $content;
    }

    public function source($path)
    {
        $content = file_get_contents(config('mailpreview.path') . '/' . $path . '.html');

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }

    public function text($path)
    {
        $content = file_get_contents(config('mailpreview.path') . '/' . $path . '.txt');

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }

    public function inbox()
    {
        return collect(glob(config('mailpreview.path') . '/*.eml'))
            ->sort()
            ->reverse()
            ->map([$this, 'process']);
    }

    public function process($path)
    {
        $eml = file_get_contents($path);

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
            'path' => substr($path, strlen(config('mailpreview.path')) + 1, -4),
            'active' => false,
        ];
    }
}
