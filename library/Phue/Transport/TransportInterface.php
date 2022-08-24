<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Transport;

/**
 * Transport Interface
 */
interface TransportInterface
{
    const METHOD_GET = 'GET';

    const METHOD_POST = 'POST';

    const METHOD_PUT = 'PUT';

    const METHOD_DELETE = 'DELETE';

    /**
     * Send request
     *
     * @param string $address API path
     * @param string $method Request method
     * @param \stdClass|null $body Body data
     *
     * @return mixed Command result
     */
    public function sendRequest(string $address, string $method = self::METHOD_GET, \stdClass $body = null): mixed;

    /**
     * Send request, bypass body validation
     *
     * @param string $address API path
     * @param string $method Request method
     * @param \stdClass|null $body Body data
     *
     * @return mixed Command result
     */
    public function sendRequestBypassBodyValidation(string $address, string $method = self::METHOD_GET, \stdClass $body = null): mixed;
}
