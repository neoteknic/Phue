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
use Phue\Command\GetGroups;

/**
 * Tests for Phue\Command\GetGroups
 */
class GetGroupsTest extends AbstractCommandTest
{
    private GetGroups $getGroups;

    public function setUp(): void
    {
        $this->getGroups = new GetGroups();

        parent::setUp();
    }

    /**
     * Test: Found no groups
     *
     * @covers \Phue\Command\GetGroups::send
     */
    public function testFoundNoGroups(): void
    {
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/groups"))
            ->will($this->returnValue(new \stdClass()));
        
        // Send command and get response
        $response = $this->getGroups->send($this->mockClient);
        
        // Ensure we have an empty array
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * Test: Found groups
     *
     * @covers \Phue\Command\GetGroups::send
     */
    public function testFoundGroups()
    {
        // Mock transport results
        $mockTransportResults = (object) array(
            1 => new \stdClass(),
            2 => new \stdClass()
        );
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/groups"))
            ->will($this->returnValue($mockTransportResults));
        
        // Send command and get response
        $response = $this->getGroups->send($this->mockClient);
        
        // Ensure we have an array of Groups
        $this->assertIsArray($response);
        $this->assertContainsOnlyInstancesOf('\Phue\Group', $response);
    }
}
