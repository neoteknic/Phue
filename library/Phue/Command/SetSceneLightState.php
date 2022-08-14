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
 * Set scene light state command
 */
class SetSceneLightState extends SetLightState
{
    protected int $sceneId;

    protected int $lightId;

    /**
     * Constructs a command
     *
     * @param mixed $scene Scene Id or Scene object
     * @param mixed $light Light Id or Light object
     */
    public function __construct(mixed $scene, mixed $light)
    {
        $this->sceneId = (int) (string) $scene;

        parent::__construct($light);
    }

    /**
     * Send command
     */
    public function send(Client $client)
    {
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/scenes/".$this->sceneId."/lights/".$this->lightId."/state",
            TransportInterface::METHOD_PUT,
            (object) $this->params
        );
    }
}
