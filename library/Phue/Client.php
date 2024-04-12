<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

namespace Phue;

use Phue\Command\CommandInterface;
use Phue\Command\GetBridge;
use Phue\Command\GetGroups;
use Phue\Command\GetLights;
use Phue\Command\GetRules;
use Phue\Command\GetScenes;
use Phue\Command\GetSchedules;
use Phue\Command\GetSensors;
use Phue\Command\GetTimezones;
use Phue\Command\GetUsers;
use Phue\Transport\Http;
use Phue\Transport\TransportInterface;

/**
 * Client for connecting to Philips Hue bridge
 */
class Client
{

    protected string $host;

    protected string $username;

    protected TransportInterface $transport;

    public function __construct(string $host, ?string $username = null)
    {
        $this->setHost($host);
        $this->setUsername($username);
        $this->setTransport(new Http($this));
    }

    public function getHost() : string
    {
        return $this->host;
    }

    public function setHost(string $host) : Client
    {
        $this->host = $host;
        return $this;
    }

    public function getUsername() : ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username) : Client
    {
        $this->username = (string) $username;
        return $this;
    }

    public function getBridge() : Bridge
    {
        return $this->sendCommand(new GetBridge());
    }

    /**
     * @return User[]
     */
    public function getUsers() : array
    {
        return $this->sendCommand(new GetUsers());
    }

    /**
     * @return Light[]
     */
    public function getLights() : array
    {
        return $this->sendCommand(new GetLights());
    }

    /**
     * @return Group[]
     */
    public function getGroups() : array
    {
        return $this->sendCommand(new GetGroups());
    }

    /**
     * @return Schedule[]
     */
    public function getSchedules() : array
    {
        return $this->sendCommand(new GetSchedules());
    }

    /**
     * @return Scene[]
     */
    public function getScenes() : array
    {
        return $this->sendCommand(new GetScenes());
    }

    /**
     * @return Sensor[]
     */
    public function getSensors() : array
    {
        return $this->sendCommand(new GetSensors());
    }

    /**
     * @return Rule[]
     */
    public function getRules() : array
    {
        return $this->sendCommand(new GetRules());
    }

    /**
     * Get timezones
     *
     * @return array List of timezones
     */
    public function getTimezones() : array
    {
        return $this->sendCommand(new GetTimezones());
    }

    public function getTransport() : TransportInterface
    {
        return $this->transport;
    }

    public function setTransport(TransportInterface $transport) : Client
    {
        $this->transport = $transport;
        return $this;
    }

    public function sendCommand(CommandInterface $command): mixed
    {
        return $command->send($this);
    }
}
