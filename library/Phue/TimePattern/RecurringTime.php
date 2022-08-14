<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\TimePattern;

use DateTime;
use DateTimeZone;

class RecurringTime extends AbstractTimePattern
{
    /**
     * Days of the week.
     */
    const MONDAY = 64;

    const TUESDAY = 32;

    const WEDNESDAY = 16;

    const THURSDAY = 8;

    const FRIDAY = 4;

    const SATURDAY = 2;

    const SUNDAY = 1;

    /**
     * Groups of days.
     */
    const WEEKDAY = 124;

    const WEEKEND = 3;

    protected string $timeOfDay;

    /**
     * @param int $daysOfWeek Bitmask of days (MONDAY|WEDNESDAY|FRIDAY)
     */
    public function __construct(protected int $daysOfWeek, int $hour = 0, int $minute = 0, int $second = 0)
    {
        $timeOfDay = new DateTime();
        $this->timeOfDay = $timeOfDay->setTime($hour, $minute, $second)
            ->setTimeZone(new DateTimeZone('UTC'))
            ->format('H:i:s');
    }

    /**
     * To string
     *
     * @return string Formatted date
     */
    public function __toString(): string
    {
        return "W{$this->daysOfWeek}/T{$this->timeOfDay}";
    }
}
