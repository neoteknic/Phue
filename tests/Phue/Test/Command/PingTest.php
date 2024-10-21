<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Command;

use PHPUnit\Framework\TestCase;
use Phue\Command\Ping;

/**
 * Tests for Phue\Command\Ping
 */
class PingTest extends AbstractCommandTest
{
    /**
     * Test: Send ping command
     *
     * @covers \Phue\Command\Ping::send
     */
    public function testSend(): void
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('/api/none/config'));
        
        $ping = new Ping();
        $ping->send($this->mockClient);
    }
}
