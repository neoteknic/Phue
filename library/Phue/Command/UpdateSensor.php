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
 * Update sensor command
 */
class UpdateSensor extends CreateSensor
{
    protected string $sensorId;

    /**
     * Sensor attributes
     */
    protected array $attributes = array();

    /**
     * @param mixed $sensor Sensor Id or Sensor object
     */
    public function __construct(mixed $sensor)
    {
        $this->sensorId = (string) $sensor;
    }

    public function name(?string $name): static
    {
        $this->attributes['name'] = $name;
        
        return $this;
    }

    /**
     * Send command
     */
    public function send(Client $client): ?int
    {
        return $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/sensors/{$this->sensorId}",
            TransportInterface::METHOD_PUT,
            (object) $this->attributes
        );
    }
}
