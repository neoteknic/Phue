<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Transport\Adapter;

use PHPUnit\Framework\TestCase;
use Phue\Transport\Adapter\Curl as CurlAdapter;
use CurlHandle;

/**
 * Tests for Phue\Transport\Adapter\Curl
 */
class CurlTest extends TestCase
{
    private CurlAdapter $curlAdapter;

    public function setUp(): void
    {
        try {
            $this->curlAdapter = new CurlAdapter();
        } catch (\BadFunctionCallException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    /**
     * Test: Instantiation without exception
     *
     * @covers CurlAdapter::__construct
     */
    public function testInstantiation()
    {
        $curlAdapter = new CurlAdapter();
    }

    /**
     * Test: Open curl adapter
     *
     * @covers CurlAdapter::open
     */
    public function testOpen()
    {
        $this->curlAdapter->open();
        
        #$this->assertIsResource($this->curlAdapter->getCurl());
        $this->assertInstanceOf(CurlHandle::class, $this->curlAdapter->getCurl());
    }

    /**
     * Test: Close curl adapter
     *
     * @covers CurlAdapter::close
     */
    public function testClose()
    {
        $this->curlAdapter->open();
        $this->curlAdapter->close();
        
        $this->assertEmpty($this->curlAdapter->getCurl());
    }

    /**
     * Test: Send nowhere
     *
     * @covers CurlAdapter::send
     */
    public function testSend()
    {
        $this->curlAdapter->open();
        
        $this->assertFalse($this->curlAdapter->send(false, 'GET', 'dummy'));
        
        $this->curlAdapter->close();
    }

    /**
     * Test: Get Http Status Code
     *
     * @covers CurlAdapter::getHttpStatusCode
     */
    public function testGetHttpStatusCode()
    {
        $this->curlAdapter->open();
        
        $this->assertEmpty($this->curlAdapter->getHttpStatusCode());
        
        $this->curlAdapter->close();
    }

    /**
     * Test: Get Content Type
     *
     * @covers CurlAdapter::getContentType
     */
    public function testGetContentType()
    {
        $this->curlAdapter->open();
        
        $this->assertEmpty($this->curlAdapter->getContentType());
        
        $this->curlAdapter->close();
    }
}
