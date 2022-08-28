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
use Phue\Command\GetScenes;

/**
 * Tests for Phue\Command\GetScenes
 * @property GetScenes $getScenes
 */
class GetScenesTest extends AbstractCommandTest
{
    public function setUp(): void
    {
       $this->getScenes = new GetScenes();
        
       parent::setUp();
    }

    /**
     * Test: Found no scenes
     *
     * @covers \Phue\Command\GetScenes::send
     */
    public function testFoundNoScenes(): void
    {
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/scenes"))
            ->will($this->returnValue(new \stdClass()));
        
        // Send command and get response
        $response = $this->getScenes->send($this->mockClient);
        
        // Ensure we have an empty array
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * Test: Found scenes
     *
     * @covers \Phue\Command\GetScenes::send
     */
    public function testFoundScenes(): void
    {
        // Mock transport results
        $mockTransportResults = (object) [
            1 => new \stdClass(),
            2 => new \stdClass()
        ];
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/scenes"))
            ->will($this->returnValue($mockTransportResults));
        
        // Send command and get response
        $response = $this->getScenes->send($this->mockClient);
        
        // Ensure we have an array of Scenes
        $this->assertIsArray($response);
        $this->assertContainsOnlyInstancesOf('\Phue\Scene', $response);
    }
}
