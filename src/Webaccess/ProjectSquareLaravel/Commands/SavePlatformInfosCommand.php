<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Webaccess\ProjectSquareLaravel\Models\Platform;

class SavePlatformInfosCommand extends Command
{
    protected $signature = 'projectsquare:save-platform-infos {url}';

    protected $description = 'Sauvegarde l\'URL de la plateforme en base de données';

    public function handle()
    {
        $url = $this->argument('url');
        $platform = (Platform::first()) ? Platform::first() : new Platform();
        $platform->url = $url;
        $platform->save();

        $this->info('Informations de la plateforme sauvegardées avec succès');
    }

    protected function getArguments()
    {
        return [
            ['url', InputArgument::REQUIRED, 'The URL of the platform'],
        ];
    }
}
