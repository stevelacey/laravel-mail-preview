<?php

namespace Steve\LaravelMailPreview\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailpreview:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the MailPreview resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing MailPreview Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'mailpreview-provider']);

        $this->comment('Publishing MailPreview Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'mailpreview-config']);

        $this->registerMailPreviewServiceProvider();

        $this->info('MailPreview scaffolding installed successfully.');
    }

    /**
     * Register the MailPreview service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerMailPreviewServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', App::getNamespace());

        $appConfig = file_get_contents(App::configPath('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\MailPreviewServiceProvider::class')) {
            return;
        }

        file_put_contents(App::configPath('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL,
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\MailPreviewServiceProvider::class,".PHP_EOL,
            $appConfig
        ));

        file_put_contents(App::path('Providers/MailPreviewServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(App::path('Providers/MailPreviewServiceProvider.php'))
        ));
    }
}
