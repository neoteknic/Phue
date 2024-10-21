<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Command;

use Phue\Command\GetLightById;
use Phue\Light;

/**
 * Tests for Phue\Command\GetLightById
 */
class GetLightByIdTest extends AbstractCommandTest
{
    /**
     * Test: Send get light by id command
     *
     * @covers \Phue\Command\GetLightById::__construct
     * @covers \Phue\Command\GetLightById::send
     */
    public function testSend(): void
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with("/api/{$this->mockClient->getUsername()}/lights/10")
            ->willReturn(new \stdClass());
        
        // Get light
        $x = new GetLightById(10);
        $light = $x->send($this->mockClient);
        
        // Ensure type is correct
        $this->assertInstanceOf(Light::class, $light);
    }
}
