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

        $events = [];
        foreach ($users as $user) {
            $events[$user->id] = app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $user->id,
            ]));
        }

        $months = [];
        for ($m = 0; $m < 6; $m++) {
            $month = new \StdClass();
            $month->calendars = [];
            $month->weeks = [];

            foreach ($users as $user) {
                $calendar = new Calendar(1, Day::MONDAY, date('m') + $m, date('Y'));

                $calendar->setEvents($events[$user->id]);
                $calendar->calculateMonths();
                $calendar->user = $user;

                foreach ($calendar->getMonths() as $monthObject) {
                    foreach ($monthObject->getDays() as $i => $day) {
                        $dateTime = $day->getDateTime();
                        $day->hours_scheduled = 0;

                        if ($dateTime->format('w') != Day::SATURDAY && $dateTime->format('w') != Day::SUNDAY) {
                            $hoursScheduled = 0;

                            foreach ($day->getEvents() as $j => $event) {
                                $diff = $event->endTime->diff($event->startTime);
                                $hoursScheduled += $diff->h;
                            }

                            if (!in_array($dateTime->format('W'), $month->weeks)) {
                                $month->weeks[] = $dateTime->format('W');
                            }

                            $day->hours_scheduled = ($hoursScheduled) < 8 ? $hoursScheduled : 8;
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