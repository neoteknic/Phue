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
use Phue\Command\DeleteUser;
use Phue\Transport\TransportInterface;

/**
 * Tests for Phue\Command\DeleteUser
 */
class DeleteUserTest extends AbstractCommandTest
{
    /**
     * Test: Send command
     *
     * @covers \Phue\Command\DeleteUser::__construct
     * @covers \Phue\Command\DeleteUser::send
     */
    public function testSend(): void
    {
        $command = new DeleteUser('atestusername');
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo(
                    "/api/{$this->mockClient->getUsername()}/config/whitelist/atestusername"
                ),
                $this->equalTo(TransportInterface::METHOD_DELETE)
            );
        
        // Send command
        $command->send($this->mockClient);
    }
}
