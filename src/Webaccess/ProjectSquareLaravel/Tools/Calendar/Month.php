<?php

namespace Webaccess\ProjectSquareLaravel\Tools\Calendar;

use DateTime;

class Month {

    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;

    private $_number;
    private $_year;
    private $_firstDayNumber;
    private $_firstDayOfWeek;
    private $_days;
    private $_daysNumber;
    private $_events;

    public function __construct($number = 0, $year = 0, $firstDayOfWeek = 0, $events = array())
    {
        $this->_firstDayOfWeek = $firstDayOfWeek;
        $this->_events = $events;

        $this->_calculateMonthNumberAndYear($number, $year);
        $this->_calculateFirstDayNumber();
        $this->_calculateDaysNumber();
        $this->_calculateDays();
    }

    public function getNumber()
    {
        return $this->_number;
    }

    public function getYear()
    {
        return $this->_year;
    }

    public function getFirstDayNumber()
    {
        return $this->_firstDayNumber;
    }
    
    public function getDateTime()
    {
        $date = implode('-', array($this->_year, $this->_number, 1));
        return new \DateTime($date);
    }

    private function _calculateMonthNumberAndYear($number, $year)
    {
        $this->_year = ($number > 12) ? $year + floor($number / 12) : $year;
        $this->_number = ($number % 12 == 0) ? 12 : ($number % 12);
    }

    private function _calculateFirstDayNumber()
    {
        $this->_firstDayNumber = date('w', $this->getDateTime()->getTimestamp());
    }

    public function getDays()
    {
        return $this->_days;
    }

    private function _calculateDays()
    {
        $this->_addDaysOfPreviousMonth();
        $this->_addDays();
        $this->_addDaysOfNextMonth();
    }

    private function _addDaysOfPreviousMonth()
    {
        $previousMonthsDaysNumber = $this->_firstDayNumber - $this->_firstDayOfWeek;
        if ($previousMonthsDaysNumber < 0) $previousMonthsDaysNumber = $previousMonthsDaysNumber + 7;
        if ($previousMonthsDaysNumber == 5) $previousMonthsDaysNumber = 0;

        for ($i = $previousMonthsDaysNumber; $i > 0; $i--) {
            $previousMonthDay = $this->getDateTime();
            $previousMonthDay->sub(new \DateInterval('P' . $i . 'D'));
            $this->_addDay($previousMonthDay->format('j'), $previousMonthDay->format('m'), $previousMonthDay->format('Y'), null, true);
        }
    }

    private function _addDaysOfNextMonth()
    {
        $lastDayOfMonth = $this->_days[sizeof($this->_days) - 1];
        $nextMonthDaysNumber = 5 - ($lastDayOfMonth->getDayOfWeek() - $this->_firstDayOfWeek + 1);
        $nextMonthDaysNumber = ($nextMonthDaysNumber == 5) ? 0 : $nextMonthDaysNumber;

        for ($i = 1; $i <= $nextMonthDaysNumber; $i++) {
            $nextMonthDay = $lastDayOfMonth->getDateTime();
            $nextMonthDay->add(new \DateInterval('P' . $i . 'D'));
            $this->_addDay($nextMonthDay->format('j'), $nextMonthDay->format('m'), $nextMonthDay->format('Y'), null, true);
        }
    }

    private function _addDays()
    {
        for ($day = 1; $day <= $this->_daysNumber; $day++) {
            $date = new DateTime($this->_year . '-' . $this->_number . '-' . $day);

            $events = array();
            foreach ($this->_events as $i => $event) {
                $time = clone $event->startTime;
                $time->setTime(0, 0, 0);
                if ($time == $date) {
                    $events[]= $event;
                }
            }
            
            $this->_addDay($day, $this->_number, $this->_year, $events);
        }
    }

    private function _addDay($number, $month, $year, $events = array(), $disabled = false)
    {
        $day = new Day($number, $month, $year);
        if (is_array($events) && sizeof($events) > 0) {
            foreach ($events as $event) {
                $day->addEvent($event);
            }
        }
        $day->setDisabled($disabled);
        $this->_days[]= $day;
    }

    public function _removeDay($number) {
        foreach ($this->_days as $i => $day) {
            if ($day->getNumber() == $number) {
                unset($this->_days[$i]);
            }
        }
    }

    public function getDaysNumber()
    {
        return $this->_daysNumber;
    }

    private function _calculateDaysNumber()
    {
        $this->_daysNumber = date('t', $this->getDateTime()->getTimestamp());
    }
}
