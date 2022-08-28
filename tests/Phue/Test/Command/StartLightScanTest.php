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
use Phue\Command\StartLightScan;

/**
 * Tests for Phue\Command\StartLightScan
 */
class StartLightScanTest extends AbstractCommandTest
{
    /**
     * Test: Send start light scan command
     *
     * @covers \Phue\Command\StartLightScan::send
     */
    public function testSend(): void
    {
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/lights"), 
            $this->equalTo('POST'))
            ->will($this->returnValue('success!'));
        
        // $this->assertEquals(
        // 'success!',
        // (new StartLightScan)->send($this->mockClient)
        // );
        $lightscan = new StartLightScan();
        $this->assertEquals('success!', $lightscan->send($this->mockClient));
    }
}
