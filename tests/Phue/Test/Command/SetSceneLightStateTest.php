<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Command;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Phue\Client;
use Phue\Command\SetSceneLightState;
use Phue\Light;
use Phue\Scene;
use Phue\Transport\TransportInterface;

/**
 * Tests for Phue\Command\SetSceneLightState
 */
class SetSceneLightStateTest extends TestCase
{
    # php 8.1
    #private Client&MockObject $mockClient;
    /** @var Client&MockObject $mockClient */
    private $mockClient;
    private $mockTransport;
    private $mockScene;
    private $mockLight;

    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Mock transport
        $this->mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock scene
        $this->mockScene = $this->createMock(Scene::class);
        
        // Mock light
        $this->mockLight = $this->createMock(Light::class);
        
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
     * Test: Send command
     *
     * @covers \Phue\Command\SetSceneLightState::__construct
     * @covers \Phue\Command\SetSceneLightState::send
     */
    public function testSend(): void
    {
        // Build command
        $setSceneLightStateCmd = new SetSceneLightState($this->mockScene, $this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) [
                'ct' => '300'
            ]
        );
        
        // Change color temp and set state
        $setSceneLightStateCmd->colorTemp(300)->send($this->mockClient);
    }

    /**
     * Stub transport's sendRequest with an expected payload
     */
    protected function stubTransportSendRequestWithPayload(\stdClass $payload): void
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo(
                    "/api/{$this->mockClient->getUsername()}/scenes/". (int) $this->mockScene->getId()."/lights/{$this->mockLight->getId()}/state"
                ),
                $this->equalTo('PUT'),
                $payload
            );
    }
}
