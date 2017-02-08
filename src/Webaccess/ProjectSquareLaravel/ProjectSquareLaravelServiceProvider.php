<?php

namespace Webaccess\ProjectSquareLaravel;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Facades\Image;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Webaccess\ProjectSquare\Context;
use Webaccess\ProjectSquare\Contracts\EventManager;
use Webaccess\ProjectSquare\Contracts\Translator;
use Webaccess\ProjectSquare\Events\Events;
use Webaccess\ProjectSquare\Interactors\Clients\GetClientInteractor;
use Webaccess\ProjectSquare\Interactors\Clients\GetClientsInteractor;
use Webaccess\ProjectSquare\Interactors\Clients\CreateClientInteractor;
use Webaccess\ProjectSquare\Interactors\Clients\UpdateClientInteractor;
use Webaccess\ProjectSquare\Interactors\Clients\DeleteClientInteractor;
use Webaccess\ProjectSquare\Interactors\Planning\CreateEventInteractor;
use Webaccess\ProjectSquare\Interactors\Planning\DeleteEventInteractor;
use Webaccess\ProjectSquare\Interactors\Planning\GetEventInteractor;
use Webaccess\ProjectSquare\Interactors\Planning\GetEventsInteractor;
use Webaccess\ProjectSquare\Interactors\Planning\UpdateEventInteractor;
use Webaccess\ProjectSquare\Interactors\Messages\CreateConversationInteractor;
use Webaccess\ProjectSquare\Interactors\Messages\CreateMessageInteractor;
use Webaccess\ProjectSquare\Interactors\Notifications\GetNotificationsInteractor;
use Webaccess\ProjectSquare\Interactors\Notifications\ReadNotificationInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\GetStepInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\GetStepsInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\CreateStepInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\DeleteStepInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\UpdateStepInteractor;
use Webaccess\ProjectSquare\Interactors\Reporting\GetRemainingTimeInteractor;
use Webaccess\ProjectSquare\Interactors\Reporting\GetReportingIndicatorsInteractor;
use Webaccess\ProjectSquare\Interactors\Reporting\GetTasksTotalTimeInteractor;
use Webaccess\ProjectSquare\Interactors\Reporting\GetTicketsTotalTimeInteractor;
use Webaccess\ProjectSquare\Interactors\Projects\GetProjectInteractor;
use Webaccess\ProjectSquare\Interactors\Projects\GetProjectsInteractor;
use Webaccess\ProjectSquare\Interactors\Todos\CreateTodoInteractor;
use Webaccess\ProjectSquare\Interactors\Todos\DeleteTodoInteractor;
use Webaccess\ProjectSquare\Interactors\Todos\GetTodosInteractor;
use Webaccess\ProjectSquare\Interactors\Todos\UpdateTodoInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\CreateTicketInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\DeleteTicketInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\GetTicketInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\UpdateTicketInfosInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\UpdateTicketInteractor;
use Webaccess\ProjectSquare\Interactors\Tasks\GetTasksInteractor;
use Webaccess\ProjectSquare\Interactors\Tasks\GetTaskInteractor;
use Webaccess\ProjectSquare\Interactors\Tasks\CreateTaskInteractor;
use Webaccess\ProjectSquare\Interactors\Tasks\UpdateTaskInteractor;
use Webaccess\ProjectSquare\Interactors\Tasks\DeleteTaskInteractor;
use Webaccess\ProjectSquareLaravel\Events\AlertWebsiteLoadingTimeEvent;
use Webaccess\ProjectSquareLaravel\Events\AlertWebsiteStatusCodeEvent;
use Webaccess\ProjectSquareLaravel\Http\Middleware\AfterConfig;
use Webaccess\ProjectSquareLaravel\Http\Middleware\BeforeConfig;
use Webaccess\ProjectSquareLaravel\Http\Middleware\ChangeCurrentProject;
use Webaccess\ProjectSquareLaravel\Listeners\Alerts\Slack\AlertWebsiteLoadingTimeSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Alerts\Slack\AlertWebsiteStatusCodeSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Messages\Emails\MessageCreatedEmailNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Messages\Slack\ConversationCreatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Messages\Slack\MessageCreatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails\TaskCreatedEmailNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tasks\Emails\TaskDeletedEmailNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tasks\Slack\TaskCreatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tasks\Slack\TaskDeletedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tasks\Slack\TaskUpdatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails\TicketCreatedEmailNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails\TicketDeletedEmailNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails\TicketUpdatedEmailNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tickets\Slack\TicketCreatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tickets\Slack\TicketDeletedSlackNotification;
use Webaccess\ProjectSquareLaravel\Listeners\Tickets\Slack\TicketUpdatedSlackNotification;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentClientRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentConversationRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentEventRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentMessageRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentNotificationRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentStepRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTasksRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTodoRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentUserRepository;
use Webaccess\ProjectSquareLaravel\Services\AlertManager;
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

        Event::listen(AlertWebsiteLoadingTimeEvent::class, AlertWebsiteLoadingTimeSlackNotification::class);
        Event::listen(AlertWebsiteStatusCodeEvent::class, AlertWebsiteStatusCodeSlackNotification::class);

        Context::get('event_dispatcher')->addListener(Events::CREATE_TICKET, array(new TicketCreatedSlackNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::UPDATE_TICKET, array(new TicketUpdatedSlackNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::DELETE_TICKET, array(new TicketDeletedSlackNotification(), 'handle'));

        Context::get('event_dispatcher')->addListener(Events::CREATE_TICKET, array(new TicketCreatedEmailNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::UPDATE_TICKET, array(new TicketUpdatedEmailNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::DELETE_TICKET, array(new TicketDeletedEmailNotification(), 'handle'));

        Context::get('event_dispatcher')->addListener(Events::CREATE_TASK, array(new TaskCreatedSlackNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::UPDATE_TASK, array(new TaskUpdatedSlackNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::DELETE_TASK, array(new TaskDeletedSlackNotification(), 'handle'));

        Context::get('event_dispatcher')->addListener(Events::CREATE_TASK, array(new TaskCreatedEmailNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::DELETE_TASK, array(new TaskDeletedEmailNotification(), 'handle'));


        //TODO
        Context::get('event_dispatcher')->addListener(Events::CREATE_CONVERSATION, array(new ConversationCreatedSlackNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::CREATE_MESSAGE, array(new MessageCreatedSlackNotification(), 'handle'));
        Context::get('event_dispatcher')->addListener(Events::CREATE_MESSAGE, array(new MessageCreatedEmailNotification(), 'handle'));

        //Patterns
        $basePath = __DIR__.'/../../';

        $router->aliasMiddleware('change_current_project', ChangeCurrentProject::class);
        $router->aliasMiddleware('before_config', BeforeConfig::class);
        $router->aliasMiddleware('after_config', AfterConfig::class);

        $this->loadRoutesFrom($basePath . 'routes/web.php');
        $this->loadRoutesFrom($basePath . 'routes/api.php');

        $this->loadViewsFrom($basePath.'resources/views/', 'projectsquare');
        $this->loadTranslationsFrom($basePath.'resources/lang/', 'projectsquare');

        //Assets publications
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
        App::bind('GetClientsInteractor', function () {
             return new GetClientsInteractor(
                 new EloquentClientRepository()
             );
         });

        App::bind('GetClientInteractor', function () {
            return new GetClientInteractor(
                new EloquentClientRepository()
            );
        });

        App::bind('CreateClientInteractor', function () {
            return new CreateClientInteractor(
                new EloquentClientRepository()
            );
        });

        App::bind('UpdateClientInteractor', function () {
            return new UpdateClientInteractor(
                new EloquentClientRepository()
            );
        });

        App::bind('DeleteClientInteractor', function () {
            return new DeleteClientInteractor(
                new EloquentClientRepository(),
                new EloquentProjectRepository(),
                new EloquentUserRepository(),
                new EloquentTicketRepository(),
                new EloquentTasksRepository(),
                new EloquentEventRepository(),
                new EloquentNotificationRepository()
            );
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

        App::bind('CreateConversationInteractor', function () {
            return new CreateConversationInteractor(
                new EloquentConversationRepository(),
                new EloquentMessageRepository(),
                new EloquentUserRepository(),
                new EloquentProjectRepository(),
                new EloquentNotificationRepository()
            );
        });

        App::bind('CreateMessageInteractor', function () {
            return new CreateMessageInteractor(
                new EloquentMessageRepository(),
                new EloquentConversationRepository(),
                new EloquentUserRepository(),
                new EloquentProjectRepository(),
                new EloquentNotificationRepository()
            );
        });

        App::bind('GetProjectsInteractor', function () {
            return new GetProjectsInteractor(
                new EloquentProjectRepository()
            );
        });

        App::bind('GetEventsInteractor', function () {
            return new GetEventsInteractor(
                new EloquentEventRepository()
            );
        });

        App::bind('GetEventInteractor', function () {
            return new GetEventInteractor(
                new EloquentEventRepository()
            );
        });

        App::bind('CreateEventInteractor', function () {
            return new CreateEventInteractor(
                new EloquentEventRepository(),
                new EloquentNotificationRepository(),
                new EloquentTicketRepository(),
                new EloquentProjectRepository(),
                new EloquentTasksRepository()
            );
        });

        App::bind('UpdateEventInteractor', function () {
            return new UpdateEventInteractor(
                new EloquentEventRepository()
            );
        });

        App::bind('DeleteEventInteractor', function () {
            return new DeleteEventInteractor(
                new EloquentEventRepository(),
                new EloquentNotificationRepository()
            );
        });

        App::bind('GetProjectInteractor', function () {
            return new GetProjectInteractor(
                new EloquentProjectRepository()
            );
        });

        App::bind('GetTicketInteractor', function () {
            return new GetTicketInteractor(
                new EloquentTicketRepository()
            );
        });

        App::bind('CreateTicketInteractor', function () {
            return new CreateTicketInteractor(
                new EloquentTicketRepository(),
                new EloquentProjectRepository(),
                new EloquentUserRepository(),
                new EloquentNotificationRepository()
            );
        });

        App::bind('UpdateTicketInfosInteractor', function () {
            return new UpdateTicketInfosInteractor(
                new EloquentTicketRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('UpdateTicketInteractor', function () {
            return new UpdateTicketInteractor(
                new EloquentTicketRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('DeleteTicketInteractor', function () {
            return new DeleteTicketInteractor(
                new EloquentTicketRepository(),
                new EloquentProjectRepository(),
                new EloquentUserRepository(),
                new EloquentEventRepository(),
                new EloquentNotificationRepository()
            );
        });

        App::bind('GetNotificationsInteractor', function () {
            return new GetNotificationsInteractor(
                new EloquentNotificationRepository(),
                new EloquentUserRepository()
            );
        });

        App::bind('ReadNotificationInteractor', function () {
            return new ReadNotificationInteractor(
                new EloquentNotificationRepository(),
                new EloquentUserRepository()
            );
        });

        App::bind('GetStepsInteractor', function () {
            return new GetStepsInteractor(
                new EloquentStepRepository()
            );
        });

        App::bind('GetStepInteractor', function () {
            return new GetStepInteractor(
                new EloquentStepRepository()
            );
        });

        App::bind('CreateStepInteractor', function () {
            return new CreateStepInteractor(
                new EloquentStepRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('UpdateStepInteractor', function () {
            return new UpdateStepInteractor(
                new EloquentStepRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('DeleteStepInteractor', function () {
            return new DeleteStepInteractor(
                new EloquentStepRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('GetTodosInteractor', function () {
            return new GetTodosInteractor(new EloquentTodoRepository());
        });

        App::bind('CreateTodoInteractor', function () {
            return new CreateTodoInteractor(
                new EloquentTodoRepository()
            );
        });

        App::bind('UpdateTodoInteractor', function () {
            return new UpdateTodoInteractor(
                new EloquentTodoRepository()
            );
        });

        App::bind('DeleteTodoInteractor', function () {
            return new DeleteTodoInteractor(
                new EloquentTodoRepository()
            );
        });

        App::bind('GetTasksInteractor', function () {
            return new GetTasksInteractor(
                new EloquentTasksRepository()
            );
        });

        App::bind('GetTaskInteractor', function () {
            return new GetTaskInteractor(
                new EloquentTasksRepository()
            );
        });

        App::bind('CreateTaskInteractor', function () {
            return new CreateTaskInteractor(
                new EloquentTasksRepository(),
                new EloquentProjectRepository(),
                new EloquentUserRepository(),
                new EloquentNotificationRepository()
            );
        });

        App::bind('UpdateTaskInteractor', function () {
            return new UpdateTaskInteractor(
                new EloquentTasksRepository(),
                new EloquentProjectRepository()
            );
        });

        App::bind('DeleteTaskInteractor', function () {
            return new DeleteTaskInteractor(
                new EloquentTasksRepository(),
                new EloquentProjectRepository(),
                new EloquentUserRepository(),
                new EloquentEventRepository(),
                new EloquentNotificationRepository()
            );
        });

        App::bind('GetTasksTotalTimeInteractor', function () {
            return new GetTasksTotalTimeInteractor(
                new EloquentTasksRepository()
            );
        });

        App::bind('GetReportingIndicatorsInteractor', function () {
            return new GetReportingIndicatorsInteractor(
                new EloquentTasksRepository()
            );
        });

        App::bind('GetTicketsTotalTimeInteractor', function () {
            return new GetTicketsTotalTimeInteractor(
                new EloquentTicketRepository()
            );
        });

        App::bind('GetRemainingTimeinteractor', function () {
            return new GetRemainingTimeinteractor();
        });

        Context::set('GetProjectInteractor', app()->make('GetProjectInteractor'));

        App::register('Intervention\Image\ImageServiceProvider');

        $this->commands([
            'Webaccess\ProjectSquareLaravel\Commands\CreateUserCommand',
            'Webaccess\ProjectSquareLaravel\Commands\PingWebsiteCommand',
            'Webaccess\ProjectSquareLaravel\Commands\AlertLoadingTimeCommand',
            'Webaccess\ProjectSquareLaravel\Commands\AlertStatusCodeCommand',
            'Webaccess\ProjectSquareLaravel\Commands\PurgeRequestsCommand',
            'Webaccess\ProjectSquareLaravel\Commands\SendEmailInstallCompletedCommand',
            'Webaccess\ProjectSquareLaravel\Commands\SavePlatformInfosCommand',
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
