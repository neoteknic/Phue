<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

use Phue\SensorModel\AbstractSensorModel;
use Phue\SensorModel\SensorModelFactory;

class Sensor
{
    public function __construct(protected int $id, protected \stdClass $attributes, protected Client $client)
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->attributes->name;
    }

    public function getType(): string
    {
        return $this->attributes->type;
    }

    public function getModelId(): string
    {
        return $this->attributes->modelid;
    }

    public function getModel(): AbstractSensorModel
    {
        return SensorModelFactory::build($this->getModelId());
    }

    public function getManufacturerName(): string
    {
        return $this->attributes->manufacturername;
    }

    public function getSoftwareVersion(): ?string
    {
        if (isset($this->attributes->swversion)) {
            return $this->attributes->swversion;
        }
        
        return null;
    }

    public function getUniqueId(): ?string
    {
        if (isset($this->attributes->uniqueid)) {
            return $this->attributes->uniqueid;
        }
        
        return null;
    }

    public function getState(): \stdClass
    {
        return (object) $this->attributes->state;
    }

    public function getConfig(): \stdClass
    {
        return (object) $this->attributes->config;
    }

    /**
     * @return string Sensor Id
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}
