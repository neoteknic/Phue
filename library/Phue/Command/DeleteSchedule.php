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
 * Delete schedule command
 */
class DeleteSchedule implements CommandInterface
{
    protected string $scheduleId;

    /**
     * Constructs a command
     *
     * @param mixed $schedule
     *            Schedule Id or Schedule object
     */
    public function __construct(mixed $schedule)
    {
        $this->scheduleId = (string) $schedule;
    }

    /**
     * Send command
     */
    public function send(Client $client)
    {
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/schedules/{$this->scheduleId}",
            TransportInterface::METHOD_DELETE
        );
    }
}
