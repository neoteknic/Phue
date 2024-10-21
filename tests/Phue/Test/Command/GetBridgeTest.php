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
use Phue\Command\GetBridge;
use Phue\Bridge;

/**
 * Tests for Phue\Command\GetBridge
 */
class GetBridgeTest extends AbstractCommandTest
{
    private GetBridge $getBridge;

    public function setUp(): void
    {
        $this->getBridge = new GetBridge();

        parent::setUp();
    }

    /**
     * Test: Get Bridge
     *
     * @covers \Phue\Command\GetBridge::send
     */
    public function testGetBridge(): void
    {
        // Mock transport results
        $mockTransportResults = new \stdClass();
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/config"))
            ->willReturn($mockTransportResults);
        
        // Send command and get response
        $response = $this->getBridge->send($this->mockClient);
        
        // Ensure we have a bridge object
        $this->assertInstanceOf(Bridge::class, $response);
    }
}
