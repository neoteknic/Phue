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
use Phue\Command\SetLightName;
use Phue\Light;
use Phue\Transport\TransportInterface;
use Phue\Client;

/**
 * Tests for Phue\Command\SetLightName
 */
class SetLightNameTest extends TestCase
{
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Mock transport
        $this->mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock light
        $this->mockLight = $this->createMock(
            Light::class,
            null,
            array(
                3,
                new \stdClass(),
                $this->mockClient
            )
        );
        
        // Stub client's getUsername method
        $this->mockClient->expects($this->any())
            ->method('getUsername')
            ->willReturn('abcdefabcdef01234567890123456789');
        
        // Stub client's getTransport method
        $this->mockClient->expects($this->any())
            ->method('getTransport')
            ->willReturn($this->mockTransport);
    }

    /**
     * Test: Set light name
     *
     * @covers \Phue\Command\SetLightName::__construct
     * @covers \Phue\Command\SetLightName::send
     */
    public function testSend(): void
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo(
                    "/api/{$this->mockClient->getUsername()}/lights/{$this->mockLight->getId()}"
                ),
                $this->equalTo('PUT'),
                $this->isInstanceOf(\stdClass::class)
            );
        
        $lightname = new SetLightName($this->mockLight, 'Dummy name');
        $lightname->send($this->mockClient);
    }
}
