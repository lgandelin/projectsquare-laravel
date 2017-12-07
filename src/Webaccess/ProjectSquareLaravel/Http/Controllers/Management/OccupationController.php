<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Management;

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

        return view('projectsquare::management.occupation.index', [
            'month_labels' => self::getMonthLabels(),
            'calendars' => self::getUsersCalendarsByRole(Input::get('filter_role')),
            'roles' => app()->make('RoleManager')->getRoles(),
            'filters' => [
                'role' => Input::get('filter_role'),
            ],
        ]);
    }

    /**
     * @param null $roleID
     * @return array
     */
    public static function getUsersCalendarsByRole($roleID = null)
    {
        $users = app()->make('UserManager')->getUsersByRole($roleID);

        $events = [];
        foreach ($users as $user) {
            $events[$user->id] = app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $user->id,
            ]));
        }

        $calendars = [];
        for ($m = 0; $m < 15; $m++) {
            $month = new \StdClass();
            $month->calendars = [];
            $month->weeks = [];

            foreach ($users as $user) {
                $calendar = new Calendar(1, Day::MONDAY, date('m') + $m - 2, date('Y'));

                //Sort events by starting time
                $e = $events[$user->id];
                if (is_array($e) && sizeof($e) > 0) {
                    usort($e, function ($a, $b) {
                        return $a->startTime > $b->startTime;
                    });
                }
                $calendar->setEvents($e);
                $calendar->calculateMonths();
                $calendar->user = $user;

                foreach ($calendar->getMonths() as $monthObject) {
                    foreach ($monthObject->getDays() as $i => $day) {
                        $dateTime = $day->getDateTime();

                        $day->duration = 0;
                        foreach ($day->getEvents() as $event) {
                            $day->duration += $event->durationInHours;
                        }

                        if ($dateTime->format('w') != Day::SATURDAY && $dateTime->format('w') != Day::SUNDAY) {
                            if (!in_array($dateTime->format('W'), $month->weeks)) {
                                $month->weeks[] = $dateTime->format('W');
                            }
                        }
                    }
                }
                $month->calendars[] = $calendar;
            }
            $calendars[] = $month;
        }

        return $calendars;
    }

    /**
     * @return array
     */
    public static function getMonthLabels()
    {
        return ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    }
}