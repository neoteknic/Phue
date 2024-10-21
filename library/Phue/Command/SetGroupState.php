<?php /** @noinspection PhpMissingParentConstructorInspection */

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
 * Set group action command
 */
class SetGroupState extends SetLightState
{

    protected int $groupId;

    /**
     * Constructs a command
     *
     * @param mixed $group Group Id or Group object
     * @noinspection MagicMethodsValidityInspection
     */
    public function __construct(mixed $group)
    {
        $this->groupId = (int) (string) $group;
    }

    /**
     * Set scene
     */
    public function scene(mixed $scene): static
    {
        $this->params['scene'] = (string) $scene;
        
        return $this;
    }

    public function send(Client $client): void
    {
        // Get params
        $params = $this->getActionableParams($client);
        
        // Send request
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}" . $params['address'],
            $params['method'],
            $params['body']
        );
    }

    /**
     * @return array Key/value pairs of params
     */
    public function getActionableParams(Client $client): array
    {
        return [
            'address' => "/groups/{$this->groupId}/action",
            'method' => TransportInterface::METHOD_PUT,
            'body' => (object) $this->params
        ];
    }
}
