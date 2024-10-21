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
 * Delete group command
 */
class DeleteGroup implements CommandInterface
{
    protected string $groupId;

    /**
     * Constructs a command
     *
     * @param mixed $group
     *            Group Id or Group object
     */
    public function __construct(mixed $group)
    {
        $this->groupId = (string) $group;
    }

    /**
     * Send command
     */
    public function send(Client $client): void
    {
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/groups/{$this->groupId}",
            TransportInterface::METHOD_DELETE
        );
    }
}
