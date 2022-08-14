<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

use Phue\Command\DeleteUser;

class User
{
    public function __construct(protected string $username, protected \stdClass $attributes, protected Client $client)
    {}

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getDeviceType(): string
    {
        return $this->attributes->name;
    }

    public function getCreateDate(): string
    {
        return $this->attributes->{'create date'};
    }

    public function getLastUseDate(): string
    {
        return $this->attributes->{'last use date'};
    }

    public function delete(): void
    {
        $this->client->sendCommand((new DeleteUser($this)));
    }

    /**
     * @return string Username
     */
    public function __toString(): string
    {
        return $this->username;
    }
}
