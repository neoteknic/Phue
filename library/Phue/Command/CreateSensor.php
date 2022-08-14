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
 * Create sensor command
 */
class CreateSensor implements CommandInterface
{
    /**
     * Sensor attributes
     */
    protected array $attributes = array();

    /**
     * Sensor state
     */
    protected array $state = array();

    /**
     * @var array
     */
    protected array $config = array();


    public function __construct(?string $name = null)
    {
        $this->name($name);
    }

    public function name(?string $name): static
    {
        $this->attributes['name'] = $name;
        
        return $this;
    }

    public function modelId(string $modelId): static
    {
        $this->attributes['modelid'] = $modelId;
        
        return $this;
    }

    public function softwareVersion(string $softwareVersion): static
    {
        $this->attributes['swversion'] = $softwareVersion;
        
        return $this;
    }

    public function type(string $type): static
    {
        $this->attributes['type'] = $type;
        
        return $this;
    }

    public function uniqueId(string $uniqueId): static
    {
        $this->attributes['uniqueid'] = $uniqueId;
        
        return $this;
    }

    public function manufacturerName(string $manufacturerName): static
    {
        $this->attributes['manufacturername'] = $manufacturerName;
        
        return $this;
    }

    public function stateAttribute(string $key, mixed $value): static
    {
        $this->state[$key] = $value;
        
        return $this;
    }

    public function configAttribute(string $key, mixed $value): static
    {
        $this->config[$key] = $value;
        
        return $this;
    }

    /**
     * Send command
     *
     * @return ?int Sensor Id
     */
    public function send(Client $client): ?int
    {
        $response = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/sensors",
            TransportInterface::METHOD_POST,
            (object) array_merge(
                $this->attributes,
                array(
                    'state' => $this->state,
                    'config' => $this->config
                )
            )
        );
        
        return $response->id;
    }
}
