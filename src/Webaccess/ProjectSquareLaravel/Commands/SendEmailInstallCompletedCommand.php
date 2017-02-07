<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailInstallCompletedCommand extends Command
{
    protected $signature = 'projectsquare:send-email-installation-completed {email} {url} {ip_address}';

    protected $description = 'Envoie l\'email de fin d\'installation';

    public function handle()
    {
        $email = $this->argument('email');
        $url = $this->argument('url');
        $ipAddress = $this->argument('ip_address');
        Mail::send('projectsquare::emails.install_completed', array('email' => $email, 'url' => $url, 'ip_address' => $ipAddress), function ($message) use ($email) {
            $message->to($email)
                ->from('no-reply@projectsquare.io')
                ->subject('[projectsquare] Votre plateforme a été installée avec succès !');
        });

        $this->info('Email de fin d\'installation envoyé avec succès');
    }

    protected function getArguments()
    {
        return [
            ['email', InputArgument::REQUIRED, 'The email adresse to send'],
            ['url', InputArgument::REQUIRED, 'The URL of the website'],
            ['ip_address', InputArgument::REQUIRED, 'The IP address of the platform'],
        ];
    }
}
