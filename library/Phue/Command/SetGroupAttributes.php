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
use Phue\Light;
use Phue\Transport\TransportInterface;

/**
 * Set group attributes command
 */
class SetGroupAttributes implements CommandInterface
{
    protected int $groupId;

    /**
     * Group attributes
     */
    protected array $attributes = [];

    /**
     * @param mixed $group Group Id or Group object
     */
    public function __construct(mixed $group)
    {
        $this->groupId = (int) (string) $group;
    }

    public function name(string $name): static
    {
        $this->attributes['name'] = $name;
        
        return $this;
    }

    /**
     * @param string[]|Light[] $lights List of light Ids or Light objects
     */
    public function lights(array $lights): static
    {
        $lightList = [];
        
        foreach ($lights as $light) {
            $lightList[] = (string) $light;
        }
        
        $this->attributes['lights'] = $lightList;
        
        return $this;
    }

    public function getGroupId(): int
    {
        return (int) $this->groupId;
    }

    public function send(Client $client): mixed
    {
        return $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/groups/".$this->getGroupId(),
            TransportInterface::METHOD_PUT,
            (object) $this->attributes
        );
    }
}
