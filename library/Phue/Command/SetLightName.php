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
 * Set light name command
 */
class SetLightName implements CommandInterface
{
    protected int $lightId;

    /**
     * @param mixed $light Light Id or Light object
     */
    public function __construct(mixed $light, protected string $name)
    {
        $this->lightId = (int) (string) $light;
    }

    public function send(Client $client)
    {
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/lights/{$this->lightId}",
            TransportInterface::METHOD_PUT,
            (object) array(
                'name' => $this->name
            )
        );
    }
}
