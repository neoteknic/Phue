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
use Phue\Command\DeleteSchedule;
use Phue\Transport\TransportInterface;

/**
 * Tests for Phue\Command\DeleteSchedule
 */
class DeleteScheduleTest extends AbstractCommandTest
{
    /**
     * Test: Send command
     *
     * @covers \Phue\Command\DeleteSchedule::__construct
     * @covers \Phue\Command\DeleteSchedule::send
     */
    public function testSend(): void
    {
        $command = new DeleteSchedule(4);
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
            $this->equalTo("/api/{$this->mockClient->getUsername()}/schedules/4"), 
            $this->equalTo(TransportInterface::METHOD_DELETE));
        
        // Send command
        $command->send($this->mockClient);
    }
}
