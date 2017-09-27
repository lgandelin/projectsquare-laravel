<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;

class PurgeRequestsCommand extends Command
{
    protected $signature = 'projectsquare:purge';

    protected $description = 'Supprime les anciennes requêtes et alertes (> 1 semaine)';

    public function handle()
    {
        app()->make('RequestManager')->deleteOldRequests();
        app()->make('AlertManager')->deleteOldAlerts();
        $this->info('Données obsolètes purgées avec succès');
    }
}
