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
use Phue\Command\GetSensorById;
use Phue\Sensor;

/**
 * Tests for Phue\Command\GetSensorById
 */
class GetSensorByIdTest extends AbstractCommandTest
{
    /**
     * Test: Send get sensor by id command
     *
     * @covers \Phue\Command\GetSensorById::__construct
     * @covers \Phue\Command\GetSensorById::send
     */
    public function testSend(): void
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with("/api/{$this->mockClient->getUsername()}/sensors/10")
            ->willReturn(new \stdClass());
        
        // Get light
        $x = new GetSensorById(10);
        $sensor = $x->send($this->mockClient);
        
        // Ensure type is correct
        $this->assertInstanceOf(Sensor::class, $sensor);
    }
}
