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
use Phue\Command\GetSensors;

/**
 * Tests for Phue\Command\GetSensors
 */
class GetSensorsTest extends AbstractCommandTest
{
    public function setUp(): void
    {
        $this->getSensors = new GetSensors();
        
        parent::setUp();
    }

    /**
     * Test: Found no sensors
     *
     * @covers \Phue\Command\GetSensors::send
     */
    public function testFoundNoSensors()
    {
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/sensors"))
            ->will($this->returnValue(new \stdClass()));
        
        // Send command and get response
        $response = $this->getSensors->send($this->mockClient);
        
        // Ensure we have an empty array
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * Test: Found sensors
     *
     * @covers \Phue\Command\GetSensors::send
     */
    public function testFoundSensors()
    {
        // Mock transport results
        $mockTransportResults = (object) array(
            1 => new \stdClass(),
            2 => new \stdClass()
        );
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/sensors"))
            ->will($this->returnValue($mockTransportResults));
        
        // Send command and get response
        $response = $this->getSensors->send($this->mockClient);
        
        // Ensure we have an array of Sensors
        $this->assertIsArray($response);
        $this->assertContainsOnlyInstancesOf('\Phue\Sensor', $response);
    }
}
