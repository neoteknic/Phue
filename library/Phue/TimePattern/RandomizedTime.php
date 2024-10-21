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

/**
 * Randomized time
 */
class RandomizedTime extends AbstractTimePattern
{
    protected DateTime $date;

    public function __construct(string $time, protected ?int $randomWithinSeconds = null)
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
        $time = $this->date->format('Y-m-d\TH:i:s');
        
        if ($this->randomWithinSeconds !== null) {
            $time .= 'A' . date('H:i:s', $this->randomWithinSeconds);
        }
        
        return $time;
    }
}
