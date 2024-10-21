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
use Phue\Command\UpdateSensor;
use Phue\Client;

/**
 * Tests for Phue\Command\UpdateSensor
 */
class UpdateSensorTest extends TestCase
{
    /**
     * Test: Instantiating UpdateSensor command
     *
     * @covers \Phue\Command\UpdateSensor::__construct
     */
    public function testInstantiation(): void
    {
        $command = new UpdateSensor('4');
    }

    /**
     * Test: Set name
     *
     * @covers \Phue\Command\UpdateSensor::name
     */
    public function testName(): void
    {
        $command = new UpdateSensor('4');
        
        $this->assertEquals($command, $command->name('dummy name'));
    }

    /**
     * Test: Send
     *
     * @covers \Phue\Command\UpdateSensor::send
     */
    public function testSend(): void
    {
        // Mock client
        $mockClient = Mockery::mock(
            Client::class,
            ['getUsername' => 'abcdefabcdef01234567890123456789']
        )
            ->makePartial();
        
        // Mock client commands
        $mockClient->shouldReceive('getTransport->sendRequest');
        
        // $command = (new UpdateSensor('5'))
        // ->send($mockClient);
        $sensor = new UpdateSensor('5');
        $command = $sensor->send($mockClient);
    }
}
