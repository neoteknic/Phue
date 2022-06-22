<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

use Phue\Command\DeleteScene;

/**
 * Scene object
 */
class Scene
{

    /**
     * Id
     *
     * @var int
     */
    protected int $id;

    /**
     * Scene attributes
     *
     * @var \stdClass
     */
    protected \stdClass $attributes;

    /**
     * Phue client
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Construct a Phue Scene object
     *
     * @param string $id
     *            Id
     * @param \stdClass $attributes
     *            Scene attributes
     * @param Client $client
     *            Phue client
     */
    public function __construct($id, \stdClass $attributes, Client $client)
    {
        $this->id = $id;
        $this->attributes = $attributes;
        $this->client = $client;
    }

    /**
     * Get scene Id
     *
     * @return int Scene id
     */
    public function getId():int
    {
        return $this->id;
    }

    /**
     * Get assigned name of scene
     *
     * @return string Name of scene
     */
    public function getName():string
    {
        return $this->attributes->name;
    }

    /**
     * Get light ids
     *
     * @return array List of light ids
     */
    public function getLightIds():array
    {
        return $this->attributes->lights;
    }


    /**
     * Delete scene
     */
    public function delete()
    {
        $this->client->sendCommand((new DeleteScene($this)));
    }

    /**
     * __toString
     *
     * @return string Scene Id
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}
