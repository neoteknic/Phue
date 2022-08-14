<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Transport\Adapter;

use CurlHandle;

/**
 * cURL Http adapter
 */
class Curl implements AdapterInterface
{
    /**
     * cURL resource
     *
     * @var resource|false|CurlHandle|null
     */
    protected $curl;

    /**
     * Constructs a cURL adapter
     */
    public function __construct()
    {
        // Throw exception if cURL extension is not loaded
        if (! extension_loaded('curl')) {
            throw new \BadFunctionCallException('The cURL extension is required.');
        }
    }

    /**
     * Opens the connection
     */
    public function open(): void
    {
        $this->curl = curl_init();
    }

    /**
     * @inheritdoc
     */
    public function send(string $address, string $method, string $body = null): string|bool
    {
        // Set connection options
        curl_setopt($this->curl, CURLOPT_URL, $address);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        if (!is_null($body) && strlen($body)) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);
        }

        return curl_exec($this->curl);
    }

    /**
     * @inheritdoc
     * TODO replace with CURLINFO_RESPONSE_CODE
     */
    public function getHttpStatusCode(): int
    {
        return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    }

    /**
     * @inheritdoc
     * TODO return string|false
     */
    public function getContentType(): string
    {
        return curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE);
    }

    public function getCurl(): false|CurlHandle|null
    {
        return $this->curl;
    }

    /**
     * Closes the cURL connection
     */
    public function close(): void
    {
        curl_close($this->curl);
        $this->curl = null;
    }
}
