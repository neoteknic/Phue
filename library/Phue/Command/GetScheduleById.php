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
use Phue\Schedule;

/**
 * Get schedule by id command
 */
class GetScheduleById implements CommandInterface
{
    public function __construct(protected int $scheduleId)
    {}

    public function send(Client $client): Schedule
    {
        // Get response
        $attributes = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/schedules/{$this->scheduleId}"
        );
        
        return new Schedule($this->scheduleId, $attributes, $client);
    }
}
