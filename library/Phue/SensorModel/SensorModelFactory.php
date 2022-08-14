<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\SensorModel;

class SensorModelFactory
{
    /**
     * TODO add phpstan types
     * Build a new sensor model from model id
     */
    public static function build(string $modelId): AbstractSensorModel
    {
        $classNamePrefix = __NAMESPACE__ . '\\';
        $classNameModel = ucfirst(strtolower($modelId)) . 'Model';
        
        if (! class_exists($classNamePrefix . $classNameModel)) {
            $classNameModel = 'UnknownModel';
        }
        
        $finalClassName = $classNamePrefix . $classNameModel;
        
        return new $finalClassName();
    }
}
