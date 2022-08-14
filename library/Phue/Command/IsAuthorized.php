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
use Phue\Transport\Exception\UnauthorizedUserException;

/**
 * Authenticate command
 */
class IsAuthorized implements CommandInterface
{
    public function send(Client $client): bool
    {
        // Get response
        try {
            $client->getTransport()->sendRequest("/api/{$client->getUsername()}");
        } catch (UnauthorizedUserException $e) {
            return false;
        }
        
        return true;
    }
}
