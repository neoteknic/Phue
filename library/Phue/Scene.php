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

class Scene
{
    public function __construct(protected string $id, protected \stdClass $attributes, protected Client $client)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->attributes->name;
    }

    /**
     * @return array List of light ids
     */
    public function getLightIds(): array
    {
        return $this->attributes->lights;
    }

    /**
     * Is active
     *
     * @deprecated
     *
     * @return null This is now deprecated
     */
    public function isActive()
    {
        return null;
    }

    public function delete(): void
    {
        $this->client->sendCommand((new DeleteScene($this)));
    }

    /**
     * @return string Scene Id
     */
    public function __toString(): string
    {
        return $this->getId();
    }
}
