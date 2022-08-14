<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\TimePattern;

class Timer extends AbstractTimePattern
{
    /**
     * Number of times to repeat
     */
    protected int $repeat;

    /**
     * @param int $seconds Number of seconds until event
     */
    public function __construct(protected int $seconds)
    {
        # TODO unsure if nullable (see below)
        #$this->repeat = null;
    }

    public function repeat(int $count): static
    {
        $this->repeat = $count;
        
        return $this;
    }

    /**
     * To string
     *
     * @return string Formatted date
     */
    public function __toString(): string
    {
        $timer = 'PT' . date('H:i:s', $this->seconds);
        
        if ($this->repeat !== null) {
            $timer = sprintf('R%1$02d/%2$s', $this->repeat, $timer);
        }
        
        return $timer;
    }
}
