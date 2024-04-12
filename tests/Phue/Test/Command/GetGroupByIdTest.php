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
use Phue\Group;

/**
 * Tests for Phue\Command\GetGroupById
 */
class GetGroupByIdTest extends AbstractCommandTest
{
    /**
     * Test: Send get group by id command
     *
     * @covers \Phue\Command\GetGroupById::__construct
     * @covers \Phue\Command\GetGroupById::send
     */
    public function testSend(): void
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with("/api/{$this->mockClient->getUsername()}/groups/5")
            ->willReturn(new \stdClass());
        
        // Get group
        $x = new GetGroupById(5);
        $group = $x->send($this->mockClient);
        
        // Ensure type is correct
        $this->assertInstanceOf(Group::class, $group);
    }
}
