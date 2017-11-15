<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class InsertAdministratorAccountCommand extends Command
{
    protected $signature = 'projectsquare:insert-administrator-account {admin_email} {admin_password}';

    protected $description = 'Crée le compte administrateur après inscription';

    public function handle()
    {
        try {
            app()->make('UserManager')->createUser(
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
            $this->info('Compte administrateur créé avec succès');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    protected function getArguments()
    {
        return [
            ['admin_email', InputArgument::REQUIRED, 'Administrator email'],
            ['admin_password', InputArgument::REQUIRED, 'Administrator password'],
        ];
    }
}
