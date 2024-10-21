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
use Phue\Command\SetScheduleAttributes;
use Phue\Transport\TransportInterface;
use Phue\Command\ActionableInterface;
use Phue\Schedule;
use Phue\Client;

/**
 * Tests for Phue\Command\SetScheduleAttributes
 */
class SetScheduleAttributesTest extends TestCase
{
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Mock transport
        $this->mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock schedule
        $this->mockSchedule = $this->createMock(
            Schedule::class,
            null,
            array(
                12,
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
        
        // Mock actionable command
        $this->mockCommand = $this->createMock(
            ActionableInterface::class,
            array(
                'getActionableParams'
            )
        );
        
        // Stub command's getActionableParams method
        $this->mockCommand->expects($this->any())
            ->method('getActionableParams')
            ->willReturn(
                [
                    'address' => '/thing/value',
                    'method' => 'POST',
                    'body' => 'Dummy'
                ]
            );
    }

    /**
     * Test: Send command
     *
     * @covers \Phue\Command\SetScheduleAttributes::__construct
     * @covers \Phue\Command\SetScheduleAttributes::send
     */
    public function testSend(): void
    {
        // Build command
        $setScheduleAttributesCmd = new SetScheduleAttributes($this->mockSchedule);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'name' => 'Dummy!',
                'description' => 'Dummy description',
                'command' => array(
                    'method' => TransportInterface::METHOD_POST,
                    'address' => "/api/{$this->mockClient->getUsername()}/thing/value",
                    'body' => "Dummy"
                )
            )
        );
        
        // Change name, description
        $setScheduleAttributesCmd->name('Dummy!')
            ->description('Dummy description')
            ->command($this->mockCommand)
            ->send($this->mockClient);
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
                    "/api/{$this->mockClient->getUsername()}/schedules/{$this->mockSchedule->getId()}"
                ),
                $this->equalTo('PUT'),
                $payload
            );
    }
}
