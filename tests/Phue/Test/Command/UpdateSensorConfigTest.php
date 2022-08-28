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
use Phue\Command\UpdateSensorConfig;

/**
 * Tests for Phue\Command\UpdateSensorConfig
 */
class UpdateSensorConfigTest extends TestCase
{
    /**
     * Test: Instantiating UpdateSensorConfig command
     *
     * @covers \Phue\Command\UpdateSensorConfig::__construct
     */
    public function testInstantiation(): void
    {
        $command = new UpdateSensorConfig('4');
    }

    /**
     * Test: Set config attribute
     *
     * @covers \Phue\Command\UpdateSensorConfig::configAttribute
     */
    public function testName(): void
    {
        $command = new UpdateSensorConfig('4');
        
        $this->assertEquals($command, $command->configAttribute('key', 'value'));
    }

    /**
     * Test: Send
     *
     * @covers \Phue\Command\UpdateSensorConfig::send
     */
    public function testSend(): void
    {
        // Mock client
        $mockClient = Mockery::mock('\Phue\Client', ['getUsername' => 'abcdefabcdef01234567890123456789'])
            ->makePartial();
        
        // Mock client commands
        $mockClient->shouldReceive('getTransport->sendRequest');
        
        $sensor = new UpdateSensorConfig('5');
        $command = $sensor->configAttribute('key', 'value')->send($mockClient);
    }
}
