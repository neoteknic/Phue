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
 * Create group command
 */
class CreateGroup implements CommandInterface
{
    protected string $name;

    /**
     * @var array List of light Ids
     */
    protected array $lights = [];

    /**
     * @param array $lights List of light Ids or Light objects
     */
    public function __construct(string $name, array $lights = [])
    {
        $this->name($name);
        $this->lights($lights);
    }

    public function name(string $name): static
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * @param array $lights List of light Ids or Light objects
     */
    public function lights(array $lights = []): static
    {
        $this->lights = [];
        
        // Iterate through each light and append id to group list
        foreach ($lights as $light) {
            $this->lights[] = (string) $light;
        }
        
        return $this;
    }

    /**
     * Send command
     */
    public function send(Client $client): string
    {
        $response = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/groups",
            TransportInterface::METHOD_POST,
            (object) [
                'name' => $this->name,
                'lights' => $this->lights
            ]
        );
        
        $r = explode('/', $response->id);

        return $r[0];
    }
}
