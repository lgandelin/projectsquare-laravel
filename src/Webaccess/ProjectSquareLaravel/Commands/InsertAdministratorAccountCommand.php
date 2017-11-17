<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;
use Webaccess\ProjectSquareLaravel\Models\Project;

class InsertAdministratorAccountCommand extends Command
{
    protected $signature = 'projectsquare:insert-administrator-account {admin_email} {admin_password}';

    protected $description = 'Crée le compte administrateur après inscription';

    public function handle()
    {
        try {
            $userID = app()->make('UserManager')->createUser(
                '',
                '',
                $this->argument('admin_email'),
                $this->argument('admin_password'),
                null,
                null,
                null,
                null,
                1,
                true
            );
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        try {
            foreach (Project::all() as $project) {
                DB::table('user_projects')->insert(['user_id' => $userID, 'project_id' => $project->id]);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        $this->info('Compte administrateur créé avec succès');
    }

    protected function getArguments()
    {
        return [
            ['admin_email', InputArgument::REQUIRED, 'Administrator email'],
            ['admin_password', InputArgument::REQUIRED, 'Administrator password'],
        ];
    }
}
