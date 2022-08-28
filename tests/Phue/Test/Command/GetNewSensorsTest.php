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
use Phue\Command\GetNewSensors;

/**
 * Tests for Phue\Command\GetNewSensors
 */
class GetNewSensorsTest extends AbstractCommandTest
{
    public function setUp(): void
    {
        $this->getNewSensors = new GetNewSensors();
        
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
            $this->equalTo("/api/{$this->mockClient->getUsername()}/sensors/new"))
            ->will($this->returnValue($mockTransportResults));
    }

    /**
     * Test: Get new sensors
     *
     * @covers \Phue\Command\GetNewSensors::send
     * @covers \Phue\Command\GetNewSensors::getSensors
     * @covers \Phue\Command\GetNewSensors::isScanActive
     */
    public function testGetNewSensors()
    {
        // Send command and get response
        $response = $this->getNewSensors->send($this->mockClient);
        
        // Ensure response is self object
        $this->assertEquals($this->getNewSensors, $response);
        
        // Ensure array of sensors
        $this->assertIsArray($response->getSensors());
        
        // Ensure expected number of sensors
        $this->assertCount(2, $response->getSensors());
        
        // Ensure lastscan is active
        $this->assertTrue($response->isScanActive());
    }
}
