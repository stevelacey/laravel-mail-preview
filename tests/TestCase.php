<?php

namespace Steve\LaravelMailPreview\Tests;

use Orchestra\Testbench;

abstract class TestCase extends Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['Steve\LaravelMailPreview\MailPreviewServiceProvider'];
    }
}
