<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Webaccess\ProjectSquareLaravel\Tools\PingTool;

class PingWebsiteCommand extends Command
{
    protected $signature = 'projectsquare:ping-websites';

    protected $description = 'Tester les performances des sites internet';

    public function handle()
    {
        foreach (app()->make('ProjectManager')->getProjects() as $project) {
            $siteURL = $project->website_front_url;
            if (list($httpCode, $totalTime) = PingTool::exec($siteURL)) {
                app()->make('RequestManager')->createRequest($project->id, $httpCode, $totalTime);
            }
        }
    }
}
