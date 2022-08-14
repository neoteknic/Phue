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
use Phue\Transport\TransportInterface;

/**
 * Create user command
 */
class CreateUser implements CommandInterface
{
    /**
     * Client name
     */
    const DEFAULT_DEVICE_TYPE = 'Phue';

    protected string $deviceType;

    public function __construct(string $deviceType = self::DEFAULT_DEVICE_TYPE)
    {
        $this->setDeviceType($deviceType);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function setDeviceType(string $deviceType): static
    {
        if (strlen($deviceType) > 40) {
            throw new \InvalidArgumentException(
                "Device type must not have a length have more than 40 characters"
            );
        }
        
        $this->deviceType = $deviceType;
        
        return $this;
    }

    /**
     * Send command
     *
     * @return \stdClass Authentication response
     */
    public function send(Client $client): \stdClass|string
    {
        // Get response
        $response = $client->getTransport()->sendRequest(
            '/api',
            TransportInterface::METHOD_POST,
            $this->buildRequestData($client)
        );
        
        return $response;
    }

    /**
     * Build request data
     *
     * @return \stdClass Request data object
     */
    protected function buildRequestData(Client $client): \stdClass
    {
        // Initialize data to send
        $request = [
            'devicetype' => $this->deviceType
        ];
        
        return (object) $request;
    }
}
