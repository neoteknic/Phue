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
 * Create scene command
 */
class CreateScene implements CommandInterface
{
    protected string $name;

    protected array $lights = array();

    protected mixed $transitionTime = null;

    private string $id;

    /**
     * @param array $lights
     *            List of light Ids or Light objects
     */
    public function __construct(string $id, string $name, array $lights = [])
    {
        $this->id($id);
        $this->name($name);
        $this->lights($lights);
    }

    /**
     * Set id
     */
    public function id(string $id): static
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Set name
     */
    public function name(string $name): static
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Set lights
     *
     * @param array $lights List of light Ids or Light objects
     */
    public function lights(array $lights = []): static
    {
        $this->lights = [];
        // Iterate through each light and append id to scene list
        foreach ($lights as $light) {
            $this->lights[] = (string) $light;
        }
        
        return $this;
    }

    public function transitionTime(float $seconds): static
    {
        // Don't continue if seconds is not valid
        if ($seconds < 0) {
            throw new \InvalidArgumentException('Time must be at least 0');
        }
        
        // Value is in 1/10 seconds
        $this->transitionTime = (int) ($seconds * 10);
        
        return $this;
    }

    /**
     * Send command
     * @return string Scene Id
     */
    public function send(Client $client): string
    {
        $body = (object) [
            'name' => $this->name,
            'lights' => $this->lights
        ];
        
        if ($this->transitionTime !== null) {
            $body->transitiontime = $this->transitionTime;
        }
        
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/scenes/{$this->id}",
            TransportInterface::METHOD_PUT,
            $body
        );
        
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
