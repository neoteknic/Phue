<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

class Portal
{
    public function __construct(protected \stdClass $attributes, protected Client $client)
    {
    }

    public function isSignedOn(): bool
    {
        return $this->attributes->signedon;
    }

    public function isIncoming(): bool
    {
        return $this->attributes->incoming;
    }

    public function isOutgoing(): bool
    {
        return $this->attributes->outgoing;
    }

    /**
     * @return string Communication status
     */
    public function getCommunication(): string
    {
        return $this->attributes->communication;
    }
}
