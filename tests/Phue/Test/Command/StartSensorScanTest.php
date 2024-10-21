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
use Phue\Command\StartSensorScan;

/**
 * Tests for Phue\Command\StartSensorScan
 */
class StartSensorScanTest extends AbstractCommandTest
{
    /**
     * Test: Send start sensor scan command
     *
     * @covers \Phue\Command\StartSensorScan::send
     */
    public function testSend(): void
    {
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo("/api/{$this->mockClient->getUsername()}/sensors"),
                $this->equalTo('POST')
            )
            ->willReturn('success!');
        
        $sensor = new StartSensorScan();
        $this->assertEquals('success!', $sensor->send($this->mockClient));
    }
}
