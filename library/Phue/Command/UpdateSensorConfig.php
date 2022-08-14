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
 * Update sensor config command
 */
class UpdateSensorConfig extends CreateSensor
{
    protected string $sensorId;

    /**
     * @var array
     */
    protected array $config = [];

    /**
     * @param mixed $sensor Sensor Id or Sensor object
     */
    public function __construct(mixed $sensor)
    {
        $this->sensorId = (string) $sensor;
    }

    public function configAttribute(string $key, mixed $value): static
    {
        $this->config[$key] = $value;
        
        return $this;
    }

    public function send(Client $client): ?int
    {
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/sensors/{$this->sensorId}/config",
            TransportInterface::METHOD_PUT,
            (object) $this->config
        );

        return null;
    }
}
