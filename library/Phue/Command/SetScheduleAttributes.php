<?php

/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Command;

use Phue\Client;
use Phue\Transport\TransportInterface;

/**
 * Set schedule attributes command
 */
class SetScheduleAttributes extends CreateSchedule implements CommandInterface
{
    protected int $scheduleId;

    /**
     * @param mixed $schedule Schedule Id or Schedule object
     */
    public function __construct(mixed $schedule)
    {
        $this->scheduleId = (int) (string) $schedule;
    }

    public function send(Client $client): ?int
    {
        // Set command attribute if passed
        if ($this->command) {
            $params = $this->command->getActionableParams($client);
            $params['address'] = "/api/{$client->getUsername()}" . $params['address'];
            
            $this->attributes['command'] = $params;
        }
        
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/schedules/{$this->scheduleId}",
            TransportInterface::METHOD_PUT,
            (object) $this->attributes
        );

        return null;
    }
}
