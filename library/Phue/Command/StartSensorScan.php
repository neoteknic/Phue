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
 * Start Sensor Scan command
 */
class StartSensorScan implements CommandInterface
{
    public function send(Client $client): mixed
    {
        // Get response
        return $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/sensors",
            TransportInterface::METHOD_POST
        );
    }
}
