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
use Phue\Command\CreateSensor;
use Phue\Client;

/**
 * Tests for Phue\Command\CreateSensor
 */
class CreateSensorTest extends TestCase
{
    /**
     * Test: Instantiating CreateSensor command
     *
     */
    public function testInstantiation(): void
    {
        $command = new CreateSensor('dummy name');
    }

    /**
     * Test: Set name
     *
     */
    public function testName(): void
    {
        $command = new CreateSensor();
        
        $this->assertEquals($command, $command->name('dummy name'));
    }

    /**
     * Test: Set model Id
     *
     */
    public function testModelId(): void
    {
        $command = new CreateSensor();
        
        $this->assertEquals($command, $command->modelId('modelid'));
    }

    /**
     * Test: Set software version
     *
     */
    public function testSoftwareVersion(): void
    {
        $command = new CreateSensor();
        
        $this->assertEquals($command, $command->softwareVersion('123'));
    }

    /**
     * Test: Set type
     *
     */
    public function testType(): void
    {
        $command = new CreateSensor();
        
        $this->assertEquals($command, $command->type('sensortype'));
    }

    /**
     * Test: Set unique Id
     *
     */
    public function testUniqueId(): void
    {
        $command = new CreateSensor();
        
        $this->assertEquals($command, $command->uniqueId('123.456.789'));
    }

    /**
     * Test: Set manufacturer name
     *
     */
    public function testManufacturerName(): void
    {
        $command = new CreateSensor();
        
        $this->assertEquals($command, $command->manufacturerName('PhueClient'));
    }

    /**
     * Test: Set config attribute
     *
     */
    public function testConfigAttribute(): void
    {
        $command = new CreateSensor();
        
        $this->assertEquals($command, $command->configAttribute('key', 'value'));
    }

    /**
     * Test: Set state attribute
     *
     */
    public function testStateAttribute(): void
    {
        $command = new CreateSensor();
        
        $this->assertEquals($command, $command->stateAttribute('key', 'value'));
    }

    /**
     * Test: Send
     *
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
        $mockClient->shouldReceive('getTransport->sendRequest')->
        andReturn((object) [
            'id' => '5'
        ]);
        
        $command = (new CreateSensor('test'));
        
        $this->assertEquals('5', $command->send($mockClient));
    }
}

