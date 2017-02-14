<?php

namespace Webaccess\ProjectSquareLaravel\Tools\Calendar;

class Calendar {

    private $_firstDayOfWeek;
    private $_monthsNumber;
    private $_startingMonth;
    private $_startingYear;
    private $_months;
    private $_events;

    public function __construct($monthsNumber = null, $firstDayOfWeek = null, $startingMonth = null, $startingYear = null)
    {
        $this->_monthsNumber = $monthsNumber;
        $this->_firstDayOfWeek = ($firstDayOfWeek != null) ? $firstDayOfWeek : 0;
        $this->_months = array();
        $this->_startingMonth = ($startingMonth) ? $startingMonth : date('n');
        $this->_startingYear = ($startingYear) ? $startingYear : date('Y');
        $this->_events = array();
    }

    public function setEvents($events = array())
    {
        $this->_events = $events;
    }

    public function getFirstDayOfWeek()
    {
        return $this->_firstDayOfWeek;
    }

    public function getMonthsNumber()
    {
        return $this->_monthsNumber;
    }

    public function getMonths()
    {
        return $this->_months;
    }

    public function calculateMonths()
    {
        for ($monthNumber = $this->_startingMonth; $monthNumber < $this->_startingMonth + $this->_monthsNumber; $monthNumber++) {
            $month = new Month($monthNumber, $this->_startingYear, $this->_firstDayOfWeek, $this->_events);
            $this->_months[]= $month;
        }
    }

}