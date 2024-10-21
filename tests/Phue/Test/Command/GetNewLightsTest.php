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
use Phue\Command\GetNewLights;

/**
 * Tests for Phue\Command\GetNewLights
 */
class GetNewLightsTest extends AbstractCommandTest
{
    private GetNewLights $getNewLights;

    public function setUp(): void
    {
        $this->getNewLights = new GetNewLights();
        
        parent::setUp();
        
        // Mock transport results
        $mockTransportResults = (object) [
            '1' => (object) [
                'name' => 'Sensor 1'
            ],
            '2' => (object) [
                'name' => 'Sensor 2'
            ],
            'lastscan' => 'active'
        ];
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo("/api/{$this->mockClient->getUsername()}/lights/new")
            )
            ->willReturn($mockTransportResults);
    }

    /**
     * Test: Get new lights
     *
     * @covers \Phue\Command\GetNewLights::send
     * @covers \Phue\Command\GetNewLights::getLights
     * @covers \Phue\Command\GetNewLights::isScanActive
     */
    public function testGetNewLights(): void
    {
        // Send command and get response
        $response = $this->getNewLights->send($this->mockClient);
        
        // Ensure response is self object
        $this->assertEquals($this->getNewLights, $response);
        
        // Ensure array of lights
        $this->assertIsArray($response->getLights());
        
        // Ensure expected number of lights
        $this->assertCount(2, $response->getLights());
        
        // Ensure lastscan is active
        $this->assertTrue($response->isScanActive());
    }
}
