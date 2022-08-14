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
use Phue\Command\GetGroupById;

/**
 * Tests for Phue\Command\GetGroupById
 */
class GetGroupByIdTest extends TestCase
{
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock('\Phue\Client', 
            array(
                'getUsername',
                'getTransport'
            ), array(
                '127.0.0.1'
            ));
        
        // Mock transport
        $this->mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Stub client's getUsername method
        $this->mockClient->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue('abcdefabcdef01234567890123456789'));
        
        // Stub client getTransport usage
        $this->mockClient->expects($this->any())
            ->method('getTransport')
            ->will($this->returnValue($this->mockTransport));
    }

    /**
     * Test: Send get group by id command
     *
     * @covers \Phue\Command\GetGroupById::__construct
     * @covers \Phue\Command\GetGroupById::send
     */
    public function testSend()
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with("/api/{$this->mockClient->getUsername()}/groups/5")
            ->will($this->returnValue(new \stdClass()));
        
        // Get group
        $x = new GetGroupById(5);
        $group = $x->send($this->mockClient);
        
        // Ensure type is correct
        $this->assertInstanceOf('\Phue\Group', $group);
    }
}
