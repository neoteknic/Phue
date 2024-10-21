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
use Phue\Command\GetSchedules;
use Phue\Schedule;

/**
 * Tests for Phue\Command\GetSchedules
 */
class GetSchedulesTest extends AbstractCommandTest
{
    private GetSchedules $getSchedules;

    public function setUp(): void
    {
        $this->getSchedules = new GetSchedules();

        parent::setUp();
    }

    /**
     * Test: Found no schedules
     *
     * @covers \Phue\Command\GetSchedules::send
     */
    public function testFoundNoSchedules(): void
    {
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo("/api/{$this->mockClient->getUsername()}/schedules")
            )
            ->willReturn(new \stdClass());
        
        // Send command and get response
        $response = $this->getSchedules->send($this->mockClient);
        
        // Ensure we have an empty array
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * Test: Found schedules
     *
     * @covers \Phue\Command\GetSchedules::send
     */
    public function testFoundSchedules(): void
    {
        // Mock transport results
        $mockTransportResults = (object) [
            '1' => new \stdClass(),
            '2' => new \stdClass()
        ];
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo("/api/{$this->mockClient->getUsername()}/schedules")
            )
            ->willReturn($mockTransportResults);
        
        // Send command and get response
        $response = $this->getSchedules->send($this->mockClient);
        
        // Ensure we have an array of Schedules
        $this->assertIsArray($response);
        $this->assertContainsOnlyInstancesOf(Schedule::class, $response);
    }
}
