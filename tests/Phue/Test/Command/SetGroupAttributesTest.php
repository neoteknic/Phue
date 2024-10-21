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
use Phue\Client;
use Phue\Command\SetGroupAttributes;
use Phue\Group;
use Phue\Transport\TransportInterface;

/**
 * Tests for Phue\Command\SetGroupAttributes
 */
class SetGroupAttributesTest extends TestCase
{
    private $mockClient;
    private $mockTransport;
    private $mockGroup;

    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->getMockBuilder(Client::class)
            ->setConstructorArgs(['127.0.0.1'])
            ->getMock()
        ;
        
        // Mock transport
        $this->mockTransport = $this->createMock(TransportInterface::class);

        $this->mockGroup = $this->getMockBuilder(Group::class)
            #->disableOriginalConstructor()
            ->setConstructorArgs([2, new \stdClass(), $this->mockClient])
            ->getMock()
        ;

        $this->mockGroup->setBrightness(30);
        
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
     * @covers \Phue\Command\SetGroupAttributes::__construct
     * @covers \Phue\Command\SetGroupAttributes::name
     * @covers \Phue\Command\SetGroupAttributes::lights
     * @covers \Phue\Command\SetGroupAttributes::send
     */
    public function testSend(): void
    {
        // Build command
        $setGroupAttributesCmd = new SetGroupAttributes($this->mockGroup);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) [
                'name' => 'Dummy!',
                'lights' => [3]
            ]
        );
        
        // Change name and lights
        $setGroupAttributesCmd->name('Dummy!')
        ->lights([3])
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
                $this->equalTo("/api/{$this->mockClient->getUsername()}/groups/{$this->mockGroup->getId()}"),
                $this->equalTo('PUT'),
                $payload
            );
    }
}
