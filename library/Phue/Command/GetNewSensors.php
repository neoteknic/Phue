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

/**
 * Get new sensors command
 */
class GetNewSensors implements CommandInterface
{
    protected string $lastScan;

    /**
     * Found sensors
     *
     * @var array
     */
    protected array $sensors = array();

    public function send(Client $client): static
    {
        // Get response
        $response = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/sensors/new"
        );
        
        $this->lastScan = $response->lastscan;
        
        // Remove scan from response
        unset($response->lastscan);
        
        // Iterate through left over properties as sensors
        foreach ($response as $sensorId => $sensor) {
            $this->sensors[$sensorId] = $sensor->name;
        }
        
        return $this;
    }

    /**
     * Get sensors
     *
     * @return array List of new sensors
     */
    public function getSensors(): array
    {
        return $this->sensors;
    }

    public function isScanActive(): bool
    {
        return $this->lastScan == 'active';
    }
}
