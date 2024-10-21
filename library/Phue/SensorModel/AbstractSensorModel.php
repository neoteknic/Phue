<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\SensorModel;

/**
 * Abstract sensor model
 */
abstract class AbstractSensorModel
{
    const MODEL_ID = 'model id';

    const MODEL_NAME = 'model name';

    public function getId(): string
    {
        return static::MODEL_ID;
    }

    public function getName(): string
    {
        return static::MODEL_NAME;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
