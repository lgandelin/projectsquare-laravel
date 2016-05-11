<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUserCommand extends Command
{
    protected $signature = 'projectsquare:create-user';

    protected $description = 'Créer un utilisateur';

    public function handle()
    {
        $firstName = $this->ask('Entrez le prénom de l\'utilisateur');
        $lastName = $this->ask('Entrez le nom de l\'utilisateur');
        $email = $this->ask('Entrez l\'email de l\'utilisateur');
        $password = $this->secret('Entrez le mot de passe de l\'utilisateur');

        try {
            app()->make('UserManager')->createUser($firstName, $lastName, $email, Hash::make($password), null);
        } catch (\Exception $e) {
            $this->error('Une erreur est survenue lors de l\'ajout de l\'utilisateur');
        }

        $this->info('L\'utilisateur a été créé avec succès');
    }
}
