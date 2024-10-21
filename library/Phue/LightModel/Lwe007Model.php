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
 * Hue LightStrips
 */
class Lwe007Model extends AbstractLightModel
{

    /**
     * Model id
     */
    const MODEL_ID = 'LWE007';

    /**
     * Model name
     */
    const MODEL_NAME = 'Hue white Candle bulb E14';

    /**
     * Can retain state
     */
    const CAN_RETAIN_STATE = true;
}
