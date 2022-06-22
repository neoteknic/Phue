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

    /**
     * Schedule Id
     *
     * @var int
     */
    protected int $scheduleId;

    /**
     * Constructs a command
     *
     * @param int $scheduleId
     *            Schedule Id
     */
    public function __construct(int $scheduleId)
    {
        $this->scheduleId = $scheduleId;
    }

    /**
     * Send command
     *
     * @param Client $client
     *            Phue Client
     *
     * @return Schedule Schedule object
     */
    public function send(Client $client)
    {
        // Get response
        $attributes = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/schedules/{$this->scheduleId}"
        );
        
        return new Schedule($this->scheduleId, $attributes, $client);
    }
}
