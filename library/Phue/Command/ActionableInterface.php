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

/**
 * Actionable Interface
 */
interface ActionableInterface
{
    /**
     * Get actionable request params
     *
     * @return array|\stdClass Key/value array of request params
     */
    public function getActionableParams(Client $client): array|\stdClass;
}
