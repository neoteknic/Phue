<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Command;

use Mockery;
use PHPUnit\Framework\TestCase;
use Phue\Command\UpdateSensorState;
use Phue\Client;

/**
 * Tests for Phue\Command\UpdateSensorState
 */
class UpdateSensorStateTest extends TestCase
{
    /**
     * Test: Instantiating UpdateSensorState command
     *
     * @covers \Phue\Command\UpdateSensorState::__construct
     */
    public function testInstantiation(): void
    {
        $command = new UpdateSensorState('4');
    }

    /**
     * Test: Set config attribute
     *
     * @covers \Phue\Command\UpdateSensorState::stateAttribute
     */
    public function testName(): void
    {
        $command = new UpdateSensorState('4');
        
        $this->assertEquals($command, $command->stateAttribute('key', 'value'));
    }

    /**
     * Test: Send
     *
     * @covers \Phue\Command\UpdateSensorState::send
     */
    public function testSend(): void
    {
        // Mock client
        $mockClient = Mockery::mock(
            Client::class,
            [
                'getUsername' => 'abcdefabcdef01234567890123456789'
            ]
        )->makePartial();
        
        // Mock client commands
        $mockClient->shouldReceive('getTransport->sendRequest');
        
        // $command = (new UpdateSensorState('5'))
        // ->stateAttribute('key', 'value')
        // ->send($mockClient);
        $sensor = new UpdateSensorState('5');
        $command = $sensor->stateAttribute('key', 'value')->send($mockClient);
    }
}
