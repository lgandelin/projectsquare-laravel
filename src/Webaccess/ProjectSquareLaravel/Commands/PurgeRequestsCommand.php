<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;

class PurgeRequestsCommand extends Command
{
    protected $signature = 'projectsquare:purge-requests';

    protected $description = 'Supprime les anciennes requêtes (> 1 semaine)';

    public function handle()
    {
        app()->make('RequestManager')->deleteOldRequests();
        $this->info('Requêtes obsolètes purgées avec succès');
    }
}