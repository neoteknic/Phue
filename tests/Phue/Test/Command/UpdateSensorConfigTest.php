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
use Phue\Client;

/**
 * Tests for Phue\Command\UpdateSensorConfig
 */
class UpdateSensorConfigTest extends TestCase
{
    /**
     * Test: Instantiating UpdateSensorConfig command
     *
     */
    public function testInstantiation(): void
    {
        $command = new UpdateSensorConfig('4');
    }

    /**
     * Test: Set config attribute
     *
     */
    public function testName(): void
    {
        $command = new UpdateSensorConfig('4');
        
        $this->assertEquals($command, $command->configAttribute('key', 'value'));
    }

    /**
     * Test: Send
     *
     */
    public function testSend(): void
    {
        // Mock client
        $mockClient = Mockery::mock(Client::class, ['getUsername' => 'abcdefabcdef01234567890123456789'])
            ->makePartial();
        
        // Mock client commands
        $mockClient->shouldReceive('getTransport->sendRequest');
        
        $sensor = new UpdateSensorConfig('5');
        $command = $sensor->configAttribute('key', 'value')->send($mockClient);
    }
}

