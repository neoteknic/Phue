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
use Phue\Sensor;

/**
 * Get sensor by id command
 */
class GetSensorById implements CommandInterface
{
    public function __construct(protected int $sensorId)
    {}

    public function send(Client $client): Sensor
    {
        // Get response
        $attributes = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/sensors/{$this->sensorId}"
        );
        
        return new Sensor($this->sensorId, $attributes, $client);
    }
}
