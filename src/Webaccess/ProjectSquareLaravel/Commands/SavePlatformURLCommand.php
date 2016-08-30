<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Webaccess\ProjectSquareLaravel\Models\Platform;

class SavePlatformURLCommand extends Command
{
    protected $signature = 'projectsquare:save-platform-url {url}';

    protected $description = 'Sauvegarde l\'URL de la plateforme en base de données';

    public function handle()
    {
        $url = $this->argument('url');
        $platform = new Platform();
        $platform->url = $url;
        $platform->save();

        $this->info('URL de la plateforme sauvegardée avec succès');
    }

    protected function getArguments()
    {
        return [
            ['url', InputArgument::REQUIRED, 'The URL of the platform'],
        ];
    }
}
