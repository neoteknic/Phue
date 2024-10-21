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
use Phue\Command\GetTimezones;

/**
 * Tests for Phue\Command\GetTimezones
 */
class GetTimezonesTest extends AbstractCommandTest
{
    public function setUp(): void
    {
        $this->getTimezones = new GetTimezones();
        
        parent::setUp();
    }

    /**
     * Test: Get Bridge
     *
     * @covers \Phue\Command\GetTimezones::send
     */
    public function testGetTimezones(): void
    {
        // Mock transport results
        $mockTransportResults = ['UTC'];
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequestBypassBodyValidation')
            ->with(
                $this->equalTo("/api/{$this->mockClient->getUsername()}/info/timezones")
            )
            ->willReturn($mockTransportResults);
        
        // Send command and get response
        $response = $this->getTimezones->send($this->mockClient);
        
        // Ensure we have a bridge object
        $this->assertIsArray($response);
    }
}
