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

class AbsoluteTime extends AbstractTimePattern
{
    protected DateTime $date;

    /**
     * @throws \Exception
     */
    public function __construct(string $time)
    {
        $this->date = (new DateTime($time));
        $this->date->setTimeZone(new DateTimeZone('UTC'));
    }

    /**
     * To string
     *
     * @return string Formatted date
     */
    public function __toString(): string
    {
        return $this->date->format('Y-m-d\TH:i:s');
    }
}
