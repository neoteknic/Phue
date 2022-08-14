<?php

/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Sherri Wheeler (@SyntaxSeed)
 * @copyright Copyright (c) 2022 Sherri Wheeler & 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Command;

use Phue\Client;
use Phue\Transport\TransportInterface;

/**
 * Delete scene command
 */
class DeleteScene implements CommandInterface
{
    protected string $sceneId;

    /**
     * Constructs a command
     *
     * @param mixed $scene
     *            Scene Id or Scene object
     */
    public function __construct(mixed $scene)
    {
        $this->sceneId = (string) $scene;
    }

    /**
     * Send command
     */
    public function send(Client $client)
    {
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/scenes/{$this->sceneId}",
            TransportInterface::METHOD_DELETE
        );
    }
}
