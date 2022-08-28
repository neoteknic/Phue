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
use Phue\Command\SetGroupState;

/**
 * Tests for Phue\Command\SetGroupState
 */
class SetGroupStateTest extends TestCase
{
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock('\Phue\Client');
        
        // Mock transport
        $this->mockTransport = $this->createMock('\Phue\Transport\TransportInterface');
        
        // Mock group
        $this->mockGroup = $this->createMock('\Phue\Group', null, 
            array(
                2,
                new \stdClass(),
                $this->mockClient
            ));
        
        // Stub client's getUsername method
        $this->mockClient->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue('abcdefabcdef01234567890123456789'));
        
        // Stub client's getTransport method
        $this->mockClient->expects($this->any())
            ->method('getTransport')
            ->will($this->returnValue($this->mockTransport));
    }

    /**
     * Test: Set scene
     *
     * @covers \Phue\Command\SetGroupState::scene
     * @covers \Phue\Command\SetLightState::send
     */
    public function testSceneSend(): void
    {
        $scene = 'phue-test';
        
        // Build command
        $command = new SetGroupState($this->mockGroup);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'scene' => $scene
            ));
        
        // Ensure instance is returned
        $this->assertEquals($command, $command->scene($scene));
        
        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: Send command
     *
     * @covers \Phue\Command\SetGroupState::__construct
     * @covers \Phue\Command\SetGroupState::send
     */
    public function testSend(): void
    {
        // Build command
        $setGroupStateCmd = new SetGroupState($this->mockGroup);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) [
                'ct' => '300'
            ]);
        
        // Change color temp and set state
        $setGroupStateCmd->colorTemp(300)->send($this->mockClient);
    }

    /**
     * Test: Get actionable params
     *
     * @covers \Phue\Command\SetGroupState::getActionableParams
     */
    public function testGetActionableParams(): void
    {
        // Build command
        $setGroupStateCmd = new SetGroupState($this->mockGroup);
        
        // Change alert
        $setGroupStateCmd->alert('select');
        
        // Ensure schedulable params are expected
        $this->assertEquals(
            [
                'address' => "/groups/{$this->mockGroup->getId()}/action",
                'method' => 'PUT',
                'body' => (object) [
                    'alert' => 'select'
                ]
            ], $setGroupStateCmd->getActionableParams($this->mockClient));
    }

    /**
     * Stub transport's sendRequest with an expected payload
     *
     * @param \stdClass $payload
     *            Payload
     */
    protected function stubTransportSendRequestWithPayload(\stdClass $payload): void
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
            $this->equalTo(
                "/api/{$this->mockClient->getUsername()}/groups/{$this->mockGroup->getId()}/action"), 
            $this->equalTo('PUT'), $payload);
    }
}
