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
use Webaccess\ProjectSquare\Interactors\Calendar\CreateEventInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\DeleteEventInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\GetEventInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\GetEventsInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\UpdateEventInteractor;
use Webaccess\ProjectSquare\Interactors\Messages\CreateConversationInteractor;
use Webaccess\ProjectSquare\Interactors\Messages\CreateMessageInteractor;
use Webaccess\ProjectSquare\Interactors\Notifications\GetNotificationsInteractor;
use Webaccess\ProjectSquare\Interactors\Notifications\ReadNotificationInteractor;
use Webaccess\ProjectSquare\Interactors\Planning\GetStepInteractor;
use Webaccess\ProjectSquare\Interactors\Planning\GetStepsInteractor;
use Webaccess\ProjectSquare\Interactors\Planning\CreateStepInteractor;
use Webaccess\ProjectSquare\Interactors\Planning\DeleteStepInteractor;
use Webaccess\ProjectSquare\Interactors\Planning\UpdateStepInteractor;
use Webaccess\ProjectSquare\Interactors\Projects\GetProjectInteractor;
use Webaccess\ProjectSquare\Interactors\Projects\GetProjectsInteractor;
use Webaccess\ProjectSquare\Interactors\Tasks\CreateTaskInteractor;
use Webaccess\ProjectSquare\Interactors\Tasks\DeleteTaskInteractor;
use Webaccess\ProjectSquare\Interactors\Tasks\GetTasksInteractor;
use Webaccess\ProjectSquare\Interactors\Tasks\UpdateTaskInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\CreateTicketInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\DeleteTicketInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\GetTicketInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\UpdateTicketInfosInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\UpdateTicketInteractor;
use Webaccess\ProjectSquareLaravel\Listeners\ConversationCreatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\MessageCreatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\TicketCreatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\TicketDeletedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\TicketUpdatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentConversationRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentEventRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentMessageRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentNotificationRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentStepRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTaskRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentUserRepository;
use Webaccess\ProjectSquareLaravel\Services\AlertManager;
use Webaccess\ProjectSquareLaravel\Services\ClientManager;
use Webaccess\ProjectSquareLaravel\Services\ConversationManager;
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

        App::bind('Image', function () {
            return new Image();
        });

        App::bind('CreateConversationInteractor', function() {
            return new CreateConversationInteractor(
                new EloquentConversationRepository(),
                new EloquentMessageRepository(),
                new EloquentUserRepository(),
                new EloquentProjectRepository(),
                new EloquentNotificationRepository()
            );
        });

        App::bind('CreateMessageInteractor', function() {
            return new CreateMessageInteractor(
                new EloquentMessageRepository(),
                new EloquentConversationRepository(),
                new EloquentUserRepository(),
                new EloquentProjectRepository(),
                new EloquentNotificationRepository()
            );
        });

        App::bind('GetProjectsInteractor', function() {
            return new GetProjectsInteractor(
                new EloquentProjectRepository()
            );
        });

        App::bind('GetEventsInteractor', function() {
            return new GetEventsInteractor(
                new EloquentEventRepository()
            );
        });

        App::bind('GetEventInteractor', function() {
            return new GetEventInteractor(
                new EloquentEventRepository()
            );
        });

        App::bind('CreateEventInteractor', function() {
            return new CreateEventInteractor(
                new EloquentEventRepository(),
                new EloquentNotificationRepository()
            );
        });

        App::bind('UpdateEventInteractor', function() {
            return new UpdateEventInteractor(
                new EloquentEventRepository()
            );
        });

        App::bind('DeleteEventInteractor', function() {
            return new DeleteEventInteractor(
                new EloquentEventRepository()
            );
        });

        App::bind('GetProjectInteractor', function() {
            return new GetProjectInteractor(
                new EloquentProjectRepository()
            );
        });

        App::bind('GetTicketInteractor', function() {
            return new GetTicketInteractor(
                new EloquentTicketRepository()
            );
        });

        App::bind('CreateTicketInteractor', function() {
            return new CreateTicketInteractor(
                new EloquentTicketRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('UpdateTicketInfosInteractor', function() {
            return new UpdateTicketInfosInteractor(
                new EloquentTicketRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('UpdateTicketInteractor', function() {
            return new UpdateTicketInteractor(
                new EloquentTicketRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('DeleteTicketInteractor', function() {
            return new DeleteTicketInteractor(
                new EloquentTicketRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('GetNotificationsInteractor', function() {
            return new GetNotificationsInteractor(
                new EloquentNotificationRepository(),
                new EloquentUserRepository()
            );
        });

        App::bind('ReadNotificationInteractor', function() {
            return new ReadNotificationInteractor(
                new EloquentNotificationRepository(),
                new EloquentUserRepository()
            );
        });

        App::bind('GetStepsInteractor', function() {
            return new GetStepsInteractor(
                new EloquentStepRepository()
            );
        });

        App::bind('GetStepInteractor', function() {
            return new GetStepInteractor(
                new EloquentStepRepository()
            );
        });

        App::bind('CreateStepInteractor', function() {
            return new CreateStepInteractor(
                new EloquentStepRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('UpdateStepInteractor', function() {
            return new UpdateStepInteractor(
                new EloquentStepRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('DeleteStepInteractor', function() {
            return new DeleteStepInteractor(
                new EloquentStepRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('GetTasksInteractor', function() {
            return new GetTasksInteractor(new EloquentTaskRepository());
        });

        App::bind('CreateTaskInteractor', function() {
            return new CreateTaskInteractor(
                new EloquentTaskRepository()
            );
        });

        App::bind('UpdateTaskInteractor', function() {
            return new UpdateTaskInteractor(
                new EloquentTaskRepository()
            );
        });

        App::bind('DeleteTaskInteractor', function() {
            return new DeleteTaskInteractor(
                new EloquentTaskRepository()
            );
        });

        Context::set('GetProjectInteractor', app()->make('GetProjectInteractor'));

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
