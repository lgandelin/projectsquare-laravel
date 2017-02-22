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

        return view('projectsquare::occupation.index', [
            'month_labels' => ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'calendars' => self::CalendarsByUsers($users),
            'today' => (new DateTime())->setTime(0, 0, 0),
            'roles' => app()->make('RoleManager')->getRoles(),
            'filters' => [
                'role' => Input::get('filter_role'),
            ],
        ]);
    }

    /**
     * @param $users
     * @return array
     */
    public static function getCalendarsByUsers($users)
    {
        $events = [];
        foreach ($users as $user) {
            $events[$user->id] = app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $user->id,
            ]));
        }

        $calendars = [];
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
                            $minutesScheduled = 0;

                            foreach ($day->getEvents() as $j => $event) {
                                $diff = $event->endTime->diff($event->startTime);
                                $minutesScheduled += ($diff->h * 60) + $diff->i;
                            }

                            if (!in_array($dateTime->format('W'), $month->weeks)) {
                                $month->weeks[] = $dateTime->format('W');
                            }

                            $hoursScheduled = $minutesScheduled / 60;

                            $day->hours_scheduled = ($hoursScheduled > 8) ? 8 : $hoursScheduled;
                        }
                    }
                }
                $month->calendars[] = $calendar;
            }
            $calendars[] = $month;
        }

        return $calendars;
    }
}