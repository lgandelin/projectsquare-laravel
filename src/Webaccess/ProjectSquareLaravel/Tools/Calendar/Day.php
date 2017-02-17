<?php

namespace Webaccess\ProjectSquareLaravel\Tools\Calendar;

class Day {

    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;

    private $_number;
    private $_month;
    private $_year;
    private $_disabled;
    public $_events;

    public function __construct($day = 0, $month = 0, $year = 0)
    {
        $this->_number = $day;
        $this->_month = $month;
        $this->_year = $year;
        $this->_disabled = false;
        $this->_events = array();
    }

    public function getDateTime()
    {
        $date = implode('-', array($this->_year, $this->_month, $this->_number));
        return new \DateTime($date);
    } 

    public function getTimestamp()
    {
        return $this->getDateTime()->getTimestamp();
    }

    public function getNumber()
    {
        return $this->_number;
    }

    public function getDayOfWeek()
    {
        return date('w', $this->getTimestamp());
    }

    public function setDisabled($disabled)
    {
        $this->_disabled = $disabled;
    }

    public function isDisabled()
    {
        return $this->_disabled;
    }

    public function addEvent($event)
    {
        $this->_events[]= $event;
    }

    public function getEvents()
    {
        return $this->_events;
    }
}
