<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;
use Webaccess\ProjectSquare\Entities\Project;
use Webaccess\ProjectSquare\Requests\Tickets\CreateTicketRequest;

class InsertDemoDataCommand extends Command
{
    protected $signature = 'projectsquare:insert-demo-data';

    protected $description = 'Insère des données de test dans la base de données';

    public function handle()
    {
        //Ticket statuses
        //DB::table('ticket_statuses')->insert(['name' => 'A faire', 'include_in_planning' => true]);
        //DB::table('ticket_statuses')->insert(['name' => 'En cours', 'include_in_planning' => true]);
        //DB::table('ticket_statuses')->insert(['name' => 'A recetter', 'include_in_planning' => true]);
        //DB::table('ticket_statuses')->insert(['name' => 'A livrer en prod', 'include_in_planning' => true]);
        //DB::table('ticket_statuses')->insert(['name' => 'En production', 'include_in_planning' => true]);
        //DB::table('ticket_statuses')->insert(['name' => 'Archivé', 'include_in_planning' => false]);

        //Ticket types
        //DB::table('ticket_types')->insert(['name' => 'Bug']);
        //DB::table('ticket_types')->insert(['name' => 'Evolution']);
        //DB::table('ticket_types')->insert(['name' => 'Orthographe']);
        //DB::table('ticket_types')->insert(['name' => 'Question']);

        //Roles
        //DB::table('roles')->insert(['name' => 'Chef de projet']);
        //DB::table('roles')->insert(['name' => 'Chef de projet technique']);
        //DB::table('roles')->insert(['name' => 'Développeur']);
        //DB::table('roles')->insert(['name' => 'Web designer']);

        //Clients
        $client1ID = Uuid::uuid4()->toString();
        $client2ID = Uuid::uuid4()->toString();
        $client3ID = Uuid::uuid4()->toString();
        DB::table('clients')->insert(['id' => $client1ID, 'name' => 'Client 1', 'address' => '']);
        DB::table('clients')->insert(['id' => $client2ID, 'name' => 'Client 2', 'address' => '']);
        DB::table('clients')->insert(['id' => $client3ID, 'name' => 'Client 3', 'address' => '']);

        //Projets
        $project1ID = Uuid::uuid4()->toString();
        $project2ID = Uuid::uuid4()->toString();
        $project3ID = Uuid::uuid4()->toString();
        $project4ID = Uuid::uuid4()->toString();
        DB::table('projects')->insert(['id' => $project1ID, 'name' => 'Projet 1', 'client_id' => $client1ID, 'color' => '#BFD962', 'status_id' => Project::IN_PROGRESS]);
        DB::table('projects')->insert(['id' => $project2ID, 'name' => 'Projet 2', 'client_id' => $client1ID, 'color' => '#1CA5B8', 'status_id' => Project::IN_PROGRESS]);
        DB::table('projects')->insert(['id' => $project3ID, 'name' => 'Projet 3', 'client_id' => $client2ID, 'color' => '#FDEEA7', 'status_id' => Project::IN_PROGRESS]);
        DB::table('projects')->insert(['id' => $project4ID, 'name' => 'Projet 4', 'client_id' => $client3ID, 'color' => '#FFD464', 'status_id' => Project::IN_PROGRESS]);

        //Users
        $user1ID = Uuid::uuid4()->toString();
        $user2ID = Uuid::uuid4()->toString();
        $user3ID = Uuid::uuid4()->toString();
        $user4ID = Uuid::uuid4()->toString();
        $user5ID = Uuid::uuid4()->toString();
        $user6ID = Uuid::uuid4()->toString();
        DB::table('users')->insert(['id' => $user1ID, 'last_name' => 'Perrin', 'first_name' => 'Bertrand', 'email' => 'bperrin@projectsquare.io', 'password' => Hash::make('111aaa'), 'role_id' => 1]);
        DB::table('users')->insert(['id' => $user2ID, 'last_name' => 'Durand', 'first_name' => 'Alice', 'email' => 'adurand@projectsquare.io', 'password' => Hash::make('111aaa'), 'role_id' => 1]);
        DB::table('users')->insert(['id' => $user3ID, 'last_name' => 'Martin', 'first_name' => 'Arnaud', 'email' => 'amartin@projectsquare.io', 'password' => Hash::make('111aaa'), 'role_id' => 2]);
        DB::table('users')->insert(['id' => $user4ID, 'last_name' => 'Leroy', 'first_name' => 'Simon', 'email' => 'sleroy@projectsquare.io', 'password' => Hash::make('111aaa'), 'role_id' => 3]);
        DB::table('users')->insert(['id' => $user5ID, 'last_name' => 'Rousseau', 'first_name' => 'Léa', 'email' => 'lrousseau@projectsquare.io', 'password' => Hash::make('111aaa'), 'role_id' => 3]);
        DB::table('users')->insert(['id' => $user6ID, 'last_name' => 'Faure', 'first_name' => 'Clément', 'email' => 'cfaure@projectsquare.io', 'password' => Hash::make('111aaa'), 'role_id' => 4]);

        //Avatars
        foreach ([$user1ID, $user2ID, $user3ID, $user4ID, $user5ID, $user6ID] as $userID) {
            $directory = public_path() . '/uploads/users/' . $userID;
            if (!is_dir($directory)) mkdir($directory);
            try {
                $image = Image::make('http://lorempixel.com/100/100/')->save($directory . '/avatar.jpg');
            } catch (\Exception $e) {

            }
        }

        //User projects
        DB::table('user_projects')->insert(['user_id' => $user1ID, 'project_id' => $project1ID]);
        DB::table('user_projects')->insert(['user_id' => $user1ID, 'project_id' => $project2ID]);
        DB::table('user_projects')->insert(['user_id' => $user1ID, 'project_id' => $project3ID]);
        DB::table('user_projects')->insert(['user_id' => $user2ID, 'project_id' => $project1ID]);
        DB::table('user_projects')->insert(['user_id' => $user2ID, 'project_id' => $project4ID]);
        DB::table('user_projects')->insert(['user_id' => $user3ID, 'project_id' => $project1ID]);
        DB::table('user_projects')->insert(['user_id' => $user3ID, 'project_id' => $project2ID]);
        DB::table('user_projects')->insert(['user_id' => $user3ID, 'project_id' => $project4ID]);
        DB::table('user_projects')->insert(['user_id' => $user4ID, 'project_id' => $project2ID]);
        DB::table('user_projects')->insert(['user_id' => $user5ID, 'project_id' => $project1ID]);
        DB::table('user_projects')->insert(['user_id' => $user5ID, 'project_id' => $project3ID]);
        DB::table('user_projects')->insert(['user_id' => $user6ID, 'project_id' => $project1ID]);
        DB::table('user_projects')->insert(['user_id' => $user6ID, 'project_id' => $project2ID]);
        DB::table('user_projects')->insert(['user_id' => $user6ID, 'project_id' => $project4ID]);

        //Events

        //Phases

        //Tasks

        //Tickets + ticket states
        $ticketsData = [
            [
                'title' => 'Ticket 1',
                'projectID' => $project1ID,
                'typeID' => 1,
                'description' => 'Lorem ipsum dolor sit amet',
                'statusID' => 1,
                'authorUserID' => $user1ID,
                'allocatedUserID' => $user2ID,
                'priority' => 1,
                'dueDate' => null,
                'estimatedTimeDays' => 0.0,
                'estimatedTimeHours' => 2.5,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => '',
                'requesterUserID' => $user1ID,
            ],
            [
                'title' => 'Ticket 2',
                'projectID' => $project1ID,
                'typeID' => 2,
                'description' => 'Lorem ipsum dolor sit amet',
                'statusID' => 2,
                'authorUserID' => $user1ID,
                'allocatedUserID' => $user3ID,
                'priority' => 2,
                'dueDate' => null,
                'estimatedTimeDays' => 0.0,
                'estimatedTimeHours' => 1.5,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => '',
                'requesterUserID' => $user1ID,
            ],
            [
                'title' => 'Ticket 3',
                'projectID' => $project1ID,
                'typeID' => 4,
                'description' => 'Lorem ipsum dolor sit amet',
                'statusID' => 3,
                'authorUserID' => $user1ID,
                'allocatedUserID' => $user1ID,
                'priority' => 3,
                'dueDate' => null,
                'estimatedTimeDays' => 0.0,
                'estimatedTimeHours' => 5.0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => '',
                'requesterUserID' => $user1ID,
            ],
            [
                'title' => 'Ticket 4',
                'projectID' => $project2ID,
                'typeID' => 1,
                'description' => 'Lorem ipsum dolor sit amet',
                'statusID' => 2,
                'authorUserID' => $user4ID,
                'allocatedUserID' => $user3ID,
                'priority' => 1,
                'dueDate' => null,
                'estimatedTimeDays' => 0.0,
                'estimatedTimeHours' => 5.0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => '',
                'requesterUserID' => $user4ID,
            ],
            [
                'title' => 'Ticket 5',
                'projectID' => $project2ID,
                'typeID' => 4,
                'description' => 'Lorem ipsum dolor sit amet',
                'statusID' => 3,
                'authorUserID' => $user6ID,
                'allocatedUserID' => $user4ID,
                'priority' => 1,
                'dueDate' => null,
                'estimatedTimeDays' => 0.0,
                'estimatedTimeHours' => 5.0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => '',
                'requesterUserID' => $user6ID,
            ],
            [
                'title' => 'Ticket 6',
                'projectID' => $project3ID,
                'typeID' => 2,
                'description' => 'Lorem ipsum dolor sit amet',
                'statusID' => 3,
                'authorUserID' => $user1ID,
                'allocatedUserID' => $user5ID,
                'priority' => 1,
                'dueDate' => null,
                'estimatedTimeDays' => 0.0,
                'estimatedTimeHours' => 1.0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => '',
                'requesterUserID' => $user1ID,
            ],
            [
                'title' => 'Ticket 6',
                'projectID' => $project3ID,
                'typeID' => 2,
                'description' => 'Lorem ipsum dolor sit amet',
                'statusID' => 3,
                'authorUserID' => $user1ID,
                'allocatedUserID' => $user5ID,
                'priority' => 3,
                'dueDate' => null,
                'estimatedTimeDays' => 0.0,
                'estimatedTimeHours' => 3.0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => '',
                'requesterUserID' => $user1ID,
            ],
            [
                'title' => 'Ticket 7',
                'projectID' => $project4ID,
                'typeID' => 1,
                'description' => 'Lorem ipsum dolor sit amet',
                'statusID' => 2,
                'authorUserID' => $user2ID,
                'allocatedUserID' => $user6ID,
                'priority' => 2,
                'dueDate' => null,
                'estimatedTimeDays' => 0.0,
                'estimatedTimeHours' => 3.0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => '',
                'requesterUserID' => $user2ID,
            ],
            [
                'title' => 'Ticket 8',
                'projectID' => $project4ID,
                'typeID' => 3,
                'description' => 'Lorem ipsum dolor sit amet',
                'statusID' => 4,
                'authorUserID' => $user2ID,
                'allocatedUserID' => $user6ID,
                'priority' => 2,
                'dueDate' => null,
                'estimatedTimeDays' => 0.0,
                'estimatedTimeHours' => 2.5,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => '',
                'requesterUserID' => $user2ID,
            ],
            [
                'title' => 'Ticket 9',
                'projectID' => $project4ID,
                'typeID' => 3,
                'description' => 'Lorem ipsum dolor sit amet',
                'statusID' => 2,
                'authorUserID' => $user6ID,
                'allocatedUserID' => $user3ID,
                'priority' => 2,
                'dueDate' => null,
                'estimatedTimeDays' => 0.0,
                'estimatedTimeHours' => 0.5,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => '',
                'requesterUserID' => $user6ID,
            ]
        ];

        foreach ($ticketsData as $ticketData) {
            app()->make('CreateTicketInteractor')->execute(new CreateTicketRequest($ticketData));
        }

        //Notifications

        //Conversations
    }
}
