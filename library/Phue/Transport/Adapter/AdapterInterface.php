<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Transport\Adapter;

interface AdapterInterface
{
    /**
     * Opens the connection
     */
    public function open(): void;

    /**
     * Sends request
     *
     * @param string $address Request path
     * @param string $method  Request method
     * @param string|null $body Body data
     *
     * @return string|bool Result
     */
    public function send(string $address, string $method, string $body = null): string|bool;

    /**
     * Get http status code from response
     * @see https://www.php.net/manual/de/function.curl-getinfo.php#100556
     */
    public function getHttpStatusCode(): int;

    /**
     * Get content type from response
     *
     * @return string Content type
     */
    public function getContentType(): string;

    /**
     * Closes the connection
     */
    public function close(): void;
}
