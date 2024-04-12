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
use Phue\Command\SetBridgeConfig;

/**
 * Tests for Phue\Command\SetBridgeConfig
 */
class SetBridgeConfigTest extends AbstractCommandTest
{
    /**
     * Test: Set bridge config
     *
     * @covers \Phue\Command\SetBridgeConfig::__construct
     * @covers \Phue\Command\SetBridgeConfig::send
     */
    public function testSend(): void
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo("/api/{$this->mockClient->getUsername()}/config"),
                $this->equalTo('PUT'),
                $this->isInstanceOf(\stdClass::class)
            );
        
        $bridgeconfig = new SetBridgeConfig(array(
            'name' => 'test'
        ));
        $bridgeconfig->send($this->mockClient);
    }
}
