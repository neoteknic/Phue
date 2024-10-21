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
use Phue\Command\GetScheduleById;
use Phue\Schedule;

/**
 * Tests for Phue\Command\GetScheduleById
 */
class GetScheduleByIdTest extends AbstractCommandTest
{
    /**
     * Test: Send get schedule by id command
     *
     * @covers \Phue\Command\GetScheduleById::__construct
     * @covers \Phue\Command\GetScheduleById::send
     */
    public function testSend(): void
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with("/api/{$this->mockClient->getUsername()}/schedules/9")
            ->willReturn(new \stdClass());
        
        // Get schedule
        $sched = new GetScheduleById(9);
        $schedule = $sched->send($this->mockClient);
        
        // Ensure type is correct
        $this->assertInstanceOf(Schedule::class, $schedule);
    }
}
