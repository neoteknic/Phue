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
use Phue\Command\CreateUser;

/**
 * Tests for Phue\Command\CreateUser
 */
class CreateUserTest extends AbstractCommandTest
{
    /**
     * Test: Instantiating CreateUser command
     *
     * @covers \Phue\Command\CreateUser::__construct
     * @covers \Phue\Command\CreateUser::setDeviceType
     */
    public function testInstantiation(): void
    {
        $command = new CreateUser('phpunit');
    }

    /**
     * Test: Setting invalid device type
     *
     * @covers \Phue\Command\CreateUser::setDeviceType
     */
    public function testExceptionOnInvalidDeviceType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new CreateUser();
        $command->setDeviceType(str_repeat('X', 41));
    }

    /**
     * Test: Send create user command
     *
     * @covers \Phue\Command\CreateUser::send
     * @covers \Phue\Command\CreateUser::buildRequestData
     */
    public function testSend(): void
    {
        // Set up device type to pass to create user command
        $deviceType = 'phpunit';
        
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('/api'), $this->equalTo('POST'), $this->anything())
            ->willReturn('success!');
        
        $x = new CreateUser('phpunit');
        $this->assertEquals(
            'success!',
            $x->send($this->mockClient)
        );
    }
}
