<?php

namespace Webaccess\ProjectSquareLaravel\Commands;

use Faker\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;
use Webaccess\ProjectSquare\Entities\Project;
use Webaccess\ProjectSquare\Requests\Phases\CreatePhaseRequest;
use Webaccess\ProjectSquare\Requests\Tasks\CreateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tickets\CreateTicketRequest;

class InsertDemoDataCommand extends Command
{
    protected $signature = 'projectsquare:insert-demo-data';

    protected $description = 'Insère des données de test dans la base de données';

    public function handle()
    {
        $faker = Factory::create();

        //Ticket statuses
        DB::table('ticket_statuses')->insert(['name' => 'A faire', 'include_in_planning' => true]);
        DB::table('ticket_statuses')->insert(['name' => 'En cours', 'include_in_planning' => true]);
        DB::table('ticket_statuses')->insert(['name' => 'A recetter', 'include_in_planning' => true]);
        DB::table('ticket_statuses')->insert(['name' => 'A livrer en prod', 'include_in_planning' => true]);
        DB::table('ticket_statuses')->insert(['name' => 'En production', 'include_in_planning' => true]);
        DB::table('ticket_statuses')->insert(['name' => 'Archivé', 'include_in_planning' => false]);

        //Ticket types
        DB::table('ticket_types')->insert(['name' => 'Bug']);
        DB::table('ticket_types')->insert(['name' => 'Evolution']);
        DB::table('ticket_types')->insert(['name' => 'Orthographe']);
        DB::table('ticket_types')->insert(['name' => 'Question']);

        //Roles
        DB::table('roles')->insert(['name' => 'Chef de projet']);
        DB::table('roles')->insert(['name' => 'Chef de projet technique']);
        DB::table('roles')->insert(['name' => 'Développeur']);
        DB::table('roles')->insert(['name' => 'Web designer']);

        //Clients
        $client1ID = Uuid::uuid4()->toString();
        $client2ID = Uuid::uuid4()->toString();
        $client3ID = Uuid::uuid4()->toString();
        DB::table('clients')->insert(['id' => $client1ID, 'name' => $faker->company, 'address' => $faker->streetAddress . "\n" .  $faker->city]);
        DB::table('clients')->insert(['id' => $client2ID, 'name' => $faker->company, 'address' => $faker->streetAddress . "\n" .  $faker->city]);
        DB::table('clients')->insert(['id' => $client3ID, 'name' => $faker->company, 'address' =>  $faker->streetAddress . "\n" .  $faker->city]);

        //Projets
        $project1ID = Uuid::uuid4()->toString();
        $project2ID = Uuid::uuid4()->toString();
        $project3ID = Uuid::uuid4()->toString();
        $project4ID = Uuid::uuid4()->toString();
        DB::table('projects')->insert(['id' => $project1ID, 'name' => 'Projet 1', 'client_id' => $client1ID, 'color' => '#18C29C', 'status_id' => Project::IN_PROGRESS]);
        DB::table('projects')->insert(['id' => $project2ID, 'name' => 'Projet 2', 'client_id' => $client1ID, 'color' => '#2AADD4', 'status_id' => Project::IN_PROGRESS]);
        DB::table('projects')->insert(['id' => $project3ID, 'name' => 'Projet 3', 'client_id' => $client2ID, 'color' => '#E84826', 'status_id' => Project::IN_PROGRESS]);
        DB::table('projects')->insert(['id' => $project4ID, 'name' => 'Projet 4', 'client_id' => $client3ID, 'color' => '#FFD464', 'status_id' => Project::IN_PROGRESS]);

        //Users
        $user1ID = Uuid::uuid4()->toString();
        $user2ID = Uuid::uuid4()->toString();
        $user3ID = Uuid::uuid4()->toString();
        $user4ID = Uuid::uuid4()->toString();
        $user5ID = Uuid::uuid4()->toString();
        $user6ID = Uuid::uuid4()->toString();
        DB::table('users')->insert(['id' => $user1ID, 'last_name' => $faker->lastName, 'first_name' => $faker->firstName, 'email' => $faker->email, 'password' => Hash::make('111aaa'), 'role_id' => 1, 'is_administrator' => 1]);
        DB::table('users')->insert(['id' => $user2ID, 'last_name' => $faker->lastName, 'first_name' => $faker->firstName, 'email' => $faker->email, 'password' => Hash::make('111aaa'), 'role_id' => 1]);
        DB::table('users')->insert(['id' => $user3ID, 'last_name' => $faker->lastName, 'first_name' => $faker->firstName, 'email' => $faker->email, 'password' => Hash::make('111aaa'), 'role_id' => 2]);
        DB::table('users')->insert(['id' => $user4ID, 'last_name' => $faker->lastName, 'first_name' => $faker->firstName, 'email' => $faker->email, 'password' => Hash::make('111aaa'), 'role_id' => 3]);
        DB::table('users')->insert(['id' => $user5ID, 'last_name' => $faker->lastName, 'first_name' => $faker->firstName, 'email' => $faker->email, 'password' => Hash::make('111aaa'), 'role_id' => 3]);
        DB::table('users')->insert(['id' => $user6ID, 'last_name' => $faker->lastName, 'first_name' => $faker->firstName, 'email' => $faker->email, 'password' => Hash::make('111aaa'), 'role_id' => 4]);

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
        DB::table('user_projects')->insert(['user_id' => $user1ID, 'project_id' => $project4ID]);
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

        //Phases
        $phasesData = [
            [
                'name' => 'Phase 1',
                'projectID' => $project1ID,
                'order' => 1,
                'requesterUserID' => $user1ID,
            ],[
                'name' => 'Phase 2',
                'projectID' => $project1ID,
                'order' => 2,
                'requesterUserID' => $user1ID,
            ],[
                'name' => 'Phase 3',
                'projectID' => $project1ID,
                'order' => 3,
                'requesterUserID' => $user1ID,
            ],

            [
                'name' => 'Phase 1',
                'projectID' => $project2ID,
                'order' => 1,
                'requesterUserID' => $user1ID,
            ], [
                'name' => 'Phase 2',
                'projectID' => $project2ID,
                'order' => 2,
                'requesterUserID' => $user1ID,
            ], [
                'name' => 'Phase 3',
                'projectID' => $project2ID,
                'order' => 3,
                'requesterUserID' => $user1ID,
            ], [
                'name' => 'Phase 4',
                'projectID' => $project2ID,
                'order' => 4,
                'requesterUserID' => $user1ID,
            ],

            [
                'name' => 'Phase 1',
                'projectID' => $project3ID,
                'order' => 1,
                'requesterUserID' => $user1ID,
            ], [
                'name' => 'Phase 2',
                'projectID' => $project3ID,
                'order' => 2,
                'requesterUserID' => $user1ID,
            ],

            [
                'name' => 'Phase 1',
                'projectID' => $project4ID,
                'order' => 1,
                'requesterUserID' => $user1ID,
            ]
        ];

        $project1PhaseIDs = [];
        $project2PhaseIDs = [];
        $project3PhaseIDs = [];
        $project4PhaseIDs = [];
        foreach ($phasesData as $phaseData) {
            $response = app()->make('CreatePhaseInteractor')->execute(new CreatePhaseRequest([
                'name' => $phaseData['name'],
                'projectID' => $phaseData['projectID'],
                'order' => $phaseData['order'],
                'requesterUserID' => $phaseData['requesterUserID']
            ]));
            $phaseID = $response->phase->id;

            if (!in_array($phaseID, $project1PhaseIDs) && $phaseData['projectID'] == $project1ID) $project1PhaseIDs[]= $phaseID;
            if (!in_array($phaseID, $project2PhaseIDs) && $phaseData['projectID'] == $project2ID) $project2PhaseIDs[]= $phaseID;
            if (!in_array($phaseID, $project3PhaseIDs) && $phaseData['projectID'] == $project3ID) $project3PhaseIDs[]= $phaseID;
            if (!in_array($phaseID, $project4PhaseIDs) && $phaseData['projectID'] == $project4ID) $project4PhaseIDs[]= $phaseID;
        }

        //Tasks
        $tasksData = [
            [
                'title' => 'Task 1',
                'description' => $faker->sentence(),
                'phaseID' => $project1PhaseIDs[array_rand($project1PhaseIDs)],
                'projectID' => $project1ID,
                'statusID' => 1,
                'estimatedTimeDays' => 2.5,
                'estimatedTimeHours' => 0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => $user1ID,
                'requesterUserID' => $user2ID,
            ], [
                'title' => 'Task 2',
                'description' => $faker->sentence(),
                'phaseID' => $project1PhaseIDs[array_rand($project1PhaseIDs)],
                'projectID' => $project1ID,
                'statusID' => 2,
                'estimatedTimeDays' => 6.5,
                'estimatedTimeHours' => 0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => $user2ID,
                'requesterUserID' => $user1ID,
            ],[
                'title' => 'Task 3',
                'description' => $faker->sentence(),
                'phaseID' => $project1PhaseIDs[array_rand($project1PhaseIDs)],
                'projectID' => $project1ID,
                'statusID' => 3,
                'estimatedTimeDays' => 4.5,
                'estimatedTimeHours' => 0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => $user3ID,
                'requesterUserID' => $user1ID,
            ], [
                'title' => 'Task 4',
                'description' => $faker->sentence(),
                'phaseID' => $project2PhaseIDs[array_rand($project2PhaseIDs)],
                'projectID' => $project2ID,
                'statusID' => 2,
                'estimatedTimeDays' => 2.5,
                'estimatedTimeHours' => 0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => $user4ID,
                'requesterUserID' => $user2ID,
            ], [
                'title' => 'Task 5',
                'description' => $faker->sentence(),
                'phaseID' => $project2PhaseIDs[array_rand($project2PhaseIDs)],
                'projectID' => $project2ID,
                'statusID' => 2,
                'estimatedTimeDays' => 1.0,
                'estimatedTimeHours' => 0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => $user4ID,
                'requesterUserID' => $user2ID,
            ], [
                'title' => 'Task 6',
                'description' => $faker->sentence(),
                'phaseID' => $project2PhaseIDs[array_rand($project2PhaseIDs)],
                'projectID' => $project2ID,
                'statusID' => 2,
                'estimatedTimeDays' => 1.0,
                'estimatedTimeHours' => 0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => $user4ID,
                'requesterUserID' => $user2ID,
            ], [
                'title' => 'Task 7',
                'description' => $faker->sentence(),
                'phaseID' => $project3PhaseIDs[array_rand($project3PhaseIDs)],
                'projectID' => $project3ID,
                'statusID' => 3,
                'estimatedTimeDays' => 5.0,
                'estimatedTimeHours' => 0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => $user5ID,
                'requesterUserID' => $user1ID,
            ], [
                'title' => 'Task 8',
                'description' => $faker->sentence(),
                'phaseID' => $project3PhaseIDs[array_rand($project3PhaseIDs)],
                'projectID' => $project3ID,
                'statusID' => 1,
                'estimatedTimeDays' => 2.5,
                'estimatedTimeHours' => 0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => $user1ID,
                'requesterUserID' => $user1ID,
            ], [
                'title' => 'Task 9',
                'description' => $faker->sentence(),
                'phaseID' => $project4PhaseIDs[array_rand($project4PhaseIDs)],
                'projectID' => $project4ID,
                'statusID' => 2,
                'estimatedTimeDays' => 5.0,
                'estimatedTimeHours' => 0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => $user3ID,
                'requesterUserID' => $user1ID,
            ], [
                'title' => 'Task 10',
                'description' => $faker->sentence(),
                'phaseID' => $project4PhaseIDs[array_rand($project4PhaseIDs)],
                'projectID' => $project4ID,
                'statusID' => 1,
                'estimatedTimeDays' => 1.0,
                'estimatedTimeHours' => 0,
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'allocatedUserID' => $user1ID,
                'requesterUserID' => $user6ID,
            ],
        ];

        foreach ($tasksData as $taskData) {
            app()->make('CreateTaskInteractor')->execute(new CreateTaskRequest($taskData));
        }

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
            ], [
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
            ], [
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
            ], [
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
            ], [
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
            ], [
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
            ], [
                'title' => 'Ticket 7',
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
            ], [
                'title' => 'Ticket 8',
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
            ], [
                'title' => 'Ticket 9',
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
            ], [
                'title' => 'Ticket 10',
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
    }
}
