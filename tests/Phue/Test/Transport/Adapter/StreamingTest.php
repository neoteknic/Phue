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
use Phue\Transport\Adapter\Streaming as StreamingAdapter;
use ReflectionObject;

/**
 * Tests for Phue\Transport\Adapter\Streaming
 */
class StreamingTest extends TestCase
{
    private StreamingAdapter $streamingAdapter;

    public function setUp(): void
    {
        $this->streamingAdapter = new StreamingAdapter();
    }

    /**
     * Test: Open streaming adapter
     *
     * @covers StreamingAdapter::open
     */
    public function testOpen(): void
    {
        $this->streamingAdapter->open();
    }

    /**
     * Test: Close streaming adapter
     *
     * @covers StreamingAdapter::close
     */
    public function testClose(): void
    {
        $this->streamingAdapter->open();
        #$this->streamingAdapter->send(false, 'GET', 'dummy');
        # ValueError : Path cannot be empty
        $this->streamingAdapter->close();

        $r = new ReflectionObject($this->streamingAdapter);
        $p = $r->getProperty('streamContext');
        $p->setAccessible(true);

        $this->assertEmpty($p->getValue($this->streamingAdapter));


        $p = $r->getProperty('fileStream');
        $p->setAccessible(true);

        $this->assertEmpty($p->getValue($this->streamingAdapter));
    }

    /**
     * Test: Send nowhere
     *
     * @covers StreamingAdapter::send
     */
    public function testSend(): void
    {
        $this->streamingAdapter->open();
        
        $this->assertFalse($this->streamingAdapter->send(false, 'GET', 'dummy'));
        
        $this->streamingAdapter->close();
    }

    /**
     * Test: Get Http Status Code
     *
     * @covers StreamingAdapter::getHttpStatusCode
     */
    public function testGetHttpStatusCode(): void
    {
        $this->streamingAdapter->open();
        
        $this->assertEmpty($this->streamingAdapter->getHttpStatusCode());
        
        $this->streamingAdapter->close();
    }

    /**
     * Test: Get Content Type
     *
     * @covers StreamingAdapter::getContentType
     */
    public function testGetContentType(): void
    {
        $this->streamingAdapter->open();
        
        $this->assertEmpty($this->streamingAdapter->getContentType());
        
        $this->streamingAdapter->close();
    }

    /**
     * Test: Get headers
     *
     * @covers StreamingAdapter::getHeaders
     */
    public function testGetHeaders(): void
    {
        $this->streamingAdapter->getHeaders();
    }
}
