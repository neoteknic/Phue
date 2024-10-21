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
class Lca004Model extends AbstractLightModel
{

    /**
     * Model id
     */
    const MODEL_ID = 'LCA004';

    /**
     * Model name
     */
    const MODEL_NAME = 'Hue White and Color Ambiance E27';

    /**
     * Can retain state
     */
    const CAN_RETAIN_STATE = true;
}
