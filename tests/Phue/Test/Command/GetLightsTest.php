<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Command;

use Phue\Command\GetLights;
use Phue\Light;

/**
 * Tests for Phue\Command\GetLights
 */
class GetLightsTest extends AbstractCommandTest
{
    private GetLights $getLights;

    public function setUp(): void
    {
        $this->getLights = new GetLights();
        
        parent::setUp();
    }

    /**
     * Test: Found no lights
     *
     * @covers \Phue\Command\GetLights::send
     */
    public function testFoundNoLights(): void
    {
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/lights"))
            ->willReturn(new \stdClass());
        
        // Send command and get response
        $response = $this->getLights->send($this->mockClient);
        
        // Ensure we have an empty array
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * Test: Found lights
     *
     * @covers \Phue\Command\GetLights::send
     */
    public function testFoundLights(): void
    {
        // Mock transport results
        $mockTransportResults = (object) [
            1 => new \stdClass(),
            2 => new \stdClass()
        ];
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/lights"))
            ->willReturn($mockTransportResults);
        
        // Send command and get response
        $response = $this->getLights->send($this->mockClient);
        
        // Ensure we have an array of Lights
        $this->assertIsArray($response);
        $this->assertContainsOnlyInstancesOf(Light::class, $response);
    }
}
