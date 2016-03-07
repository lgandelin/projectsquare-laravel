<?php

namespace Webaccess\ProjectSquareLaravel;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Facades\Image;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Webaccess\ProjectSquare\Context;
use Webaccess\ProjectSquare\Contracts\EventManager;
use Webaccess\ProjectSquare\Contracts\Translator;
use Webaccess\ProjectSquare\Events\Events;
use Webaccess\ProjectSquareLaravel\Listeners\ConversationCreatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\MessageCreatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\TicketCreatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\TicketDeletedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\TicketUpdatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Services\AlertManager;
use Webaccess\ProjectSquareLaravel\Services\ClientManager;
use Webaccess\ProjectSquareLaravel\Services\ConversationManager;
use Webaccess\ProjectSquareLaravel\Services\MessageManager;
use Webaccess\ProjectSquareLaravel\Services\ProjectManager;
use Webaccess\ProjectSquareLaravel\Services\RequestManager;
use Webaccess\ProjectSquareLaravel\Services\RoleManager;
use Webaccess\ProjectSquareLaravel\Services\SettingManager;
use Webaccess\ProjectSquareLaravel\Services\TicketStatusManager;
use Webaccess\ProjectSquareLaravel\Services\TicketTypeManager;
use Webaccess\ProjectSquareLaravel\Services\UserManager;
use Webaccess\ProjectSquareLaravel\Services\FileManager;

class ProjectSquareLaravelServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot(Router $router)
    {
        Context::set('translator', new LaravelTranslator());
        Context::set('event_manager', new LaravelEventManager());
        Context::set('event_dispatcher', new EventDispatcher());

        Event::listen(\Webaccess\ProjectSquareLaravel\Events\AlertWebsiteLoadingTimeEvent::class, \Webaccess\ProjectSquareLaravel\Listeners\AlertWebsiteLoadingTimeSlackNotification::class);
        //Event::listen(\Webaccess\ProjectSquareLaravel\Events\AlertWebsiteLoadingTimeEvent::class, \Webaccess\ProjectSquareLaravel\Listeners\AlertWebsiteLoadingTimeEmailNotification::class);
        //Event::listen(\Webaccess\ProjectSquareLaravel\Events\ConversationCreatedEvent::class, \Webaccess\ProjectSquareLaravel\Listeners\ConversationCreatedEmailNotification::class);

        Context::get('event_dispatcher')->addListener(Events::CREATE_TICKET, array(new TicketCreatedSlackNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::UPDATE_TICKET, array(new TicketUpdatedSlackNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::DELETE_TICKET, array(new TicketDeletedSlackNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::CREATE_CONVERSATION, array(new ConversationCreatedSlackNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::CREATE_MESSAGE, array(new MessageCreatedSlackNotification(), 'handle'));

        $basePath = __DIR__.'/../../';

        $router->middleware('change_current_project', 'Webaccess\ProjectSquareLaravel\Http\Middleware\ChangeCurrentProject');
        include __DIR__.'/Http/routes.php';

        $this->loadViewsFrom($basePath.'resources/views/', 'projectsquare');
        $this->loadTranslationsFrom($basePath.'resources/lang/', 'projectsquare');

        $this->publishes([
            $basePath.'resources/assets/css' => base_path('public/css'),
            $basePath.'resources/assets/js' => base_path('public/js'),
            $basePath.'resources/assets/fonts' => base_path('public/fonts'),
            $basePath.'resources/assets/img' => base_path('public/img'),
        ], 'assets');

        $this->publishes([
            $basePath.'database/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    public function register()
    {
        App::bind('ClientManager', function () {
            return new ClientManager();
        });

        App::bind('ProjectManager', function () {
            return new ProjectManager();
        });

        App::bind('UserManager', function () {
            return new UserManager();
        });

        App::bind('RoleManager', function () {
            return new RoleManager();
        });

        App::bind('TicketTypeManager', function () {
            return new TicketTypeManager();
        });

        App::bind('TicketStatusManager', function () {
            return new TicketStatusManager();
        });

        App::bind('FileManager', function () {
            return new FileManager();
        });

        App::bind('RequestManager', function () {
            return new RequestManager();
        });

        App::bind('SettingManager', function () {
            return new SettingManager();
        });

        App::bind('AlertManager', function () {
            return new AlertManager();
        });

        App::bind('ConversationManager', function () {
            return new ConversationManager();
        });

        App::bind('MessageManager', function () {
            return new MessageManager();
        });

        App::bind('Image', function () {
            return new Image();
        });

        App::register('Intervention\Image\ImageServiceProvider');

        $this->commands([
            'Webaccess\ProjectSquareLaravel\Commands\CreateUserCommand',
            'Webaccess\ProjectSquareLaravel\Commands\PingWebsiteCommand',
            'Webaccess\ProjectSquareLaravel\Commands\AlertLoadingTimeCommand',
        ]);
    }
}

class LaravelTranslator implements Translator
{
    public function translate($string)
    {
        return trans('projectsquare::'.$string);
    }
}

class LaravelEventManager implements EventManager
{
    public function fire($event)
    {
        Event::fire($event);
    }
}
