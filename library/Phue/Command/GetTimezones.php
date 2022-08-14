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
 * Get timezones command
 */
class GetTimezones implements CommandInterface
{
    /**
     * Send command
     *
     * @return array List of timezones
     */
    public function send(Client $client): array
    {
        // Get response
        $response = $client->getTransport()->sendRequestBypassBodyValidation(
            "/api/{$client->getUsername()}/info/timezones"
        );
        
        $timezones = array();
        foreach ($response as $timezone) {
            $timezones[] = $timezone;
        }
        
        return $timezones;
    }
}
