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
 * Update sensor state command
 */
class UpdateSensorState extends CreateSensor
{
    protected string $sensorId;

    /**
     * @var array
     */
    protected array $state = [];

    /**
     * @param mixed $sensor Sensor Id or Sensor object
     */
    public function __construct(mixed $sensor)
    {
        $this->sensorId = (string) $sensor;
    }

    public function stateAttribute(string $key, mixed $value): static
    {
        $this->state[$key] = $value;
        
        return $this;
    }

    /**
     * Send command
     */
    public function send(Client $client): ?int
    {
        return $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/sensors/{$this->sensorId}/state",
            TransportInterface::METHOD_PUT,
            (object) $this->state
        );
    }
}
