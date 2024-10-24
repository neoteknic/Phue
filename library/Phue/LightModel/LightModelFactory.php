<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\LightModel;

/**
 * Light model factory
 */
class LightModelFactory
{
    /**
     * Build a new light model from model id
     */
    public static function build(string $modelId): AbstractLightModel
    {
        $classNamePrefix = __NAMESPACE__ . '\\';
        $classNameModel = ucfirst(strtolower($modelId)) . 'Model';
        
        if (! class_exists($classNamePrefix . $classNameModel)) {
	        $modelId='l'.$modelId;
	        $classNameModel = ucfirst(strtolower($modelId)) . 'Model';
			if (! class_exists($classNamePrefix . $classNameModel)) {
				$classNameModel = 'UnknownModel';
			}
        }
        
        $finalClassName = $classNamePrefix . $classNameModel;
        
        return new $finalClassName();
    }
}
