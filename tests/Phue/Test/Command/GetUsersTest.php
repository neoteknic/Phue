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
use Phue\Command\GetUsers;
use Phue\User;

/**
 * Tests for Phue\Command\GetUsers
 */
class GetUsersTest extends AbstractCommandTest
{
    public function setUp(): void
    {
        $this->getUsers = new GetUsers();
        
        parent::setUp();
    }

    /**
     * Test: Found no users
     *
     * @covers \Phue\Command\GetUsers::send
     */
    public function testFoundNoUsers(): void
    {
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/config"))
            ->willReturn(new \stdClass());
        
        // Send command and get response
        $response = $this->getUsers->send($this->mockClient);
        
        // Ensure we have an empty array
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * Test: Found users
     *
     * @covers \Phue\Command\GetUsers::send
     */
    public function testFoundUsers(): void
    {
        // Mock transport results
        $mockTransportResults = (object) [
            'whitelist' => [
                'someusername' => new \stdClass(),
                'anotherusername' => new \stdClass()
            ]
        ];
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/config"))
            ->willReturn($mockTransportResults);
        
        // Send command and get response
        $response = $this->getUsers->send($this->mockClient);
        
        // Ensure we have an array of Users
        $this->assertIsArray($response);
        $this->assertContainsOnlyInstancesOf(User::class, $response);
    }
}
