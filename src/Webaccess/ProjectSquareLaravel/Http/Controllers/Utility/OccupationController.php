<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Utility;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Planning\GetEventsRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquareLaravel\Tools\Calendar\Calendar;
use Webaccess\ProjectSquareLaravel\Tools\Calendar\Day;

class OccupationController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        $users = app()->make('UserManager')->getUsersByRole(Input::get('filter_role'));
        $roles = app()->make('RoleManager')->getRoles();

        $months = [];
        for ($m = 0; $m < 6; $m++) {
            $month = new \StdClass();
            $month->calendars = [];

            foreach ($users as $user) {
                $calendar = new Calendar(1, Day::MONDAY, date('m') + $m, date('Y'), 4);
                $rawEvents = app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                    'userID' => $user->id,
                ]));

                $calendar->setEvents($rawEvents);
                $calendar->calculateMonths();
                $calendar->user = $user;

                foreach ($calendar->getMonths() as $monthObject) {
                    foreach ($monthObject->getDays() as $day) {
                        $hoursScheduled = 0;
                        foreach ($day->getEvents() as $i => $event) {
                            $diff = $event->endTime->diff($event->startTime);
                            $hoursScheduled += $diff->h;
                        }

                        if ($hoursScheduled >= 6) {
                            $day->color = 'red';
                        } elseif ($hoursScheduled >= 3) {
                            $day->color = 'orange';
                        } elseif ($hoursScheduled > 0) {
                            $day->color = 'green';
                        } else {
                            $day->color = 'white';
                        }
                    }
                }

                $month->calendars[]= $calendar;
            }
            $months[]= $month;
        }

        return view('projectsquare::occupation.index', [
            'month_labels' => ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'months' => $months,
            'today' => (new DateTime())->setTime(0, 0, 0),
            'roles' => $roles,
            'filters' => [
                'role' => Input::get('filter_role'),
            ],
        ]);
    }
}