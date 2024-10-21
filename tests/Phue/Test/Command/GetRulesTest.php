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
use Phue\Command\GetRules;
use Phue\Transport\TransportInterface;
use Phue\Rule;

/**
 * Tests for Phue\Command\GetRules
 */
class GetRulesTest extends AbstractCommandTest
{
    private GetRules $getRules;

    public function setUp(): void
    {
        $this->getRules = new GetRules();
        
        parent::setUp();
    }

    /**
     * Test: Found no rules
     *
     * @covers \Phue\Command\GetRules::send
     */
    public function testFoundNoRules(): void
    {
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/rules"))
            ->willReturn(new \stdClass());
        
        // Send command and get response
        $response = $this->getRules->send($this->mockClient);
        
        // Ensure we have an empty array
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * Test: Found rules
     *
     * @covers \Phue\Command\GetRules::send
     */
    public function testFoundRules(): void
    {
        // Mock transport results
        $mockTransportResults = (object) [
            1 => new \stdClass(),
            2 => new \stdClass()
        ];
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/rules"))
            ->willReturn($mockTransportResults);
        
        // Send command and get response
        $response = $this->getRules->send($this->mockClient);
        
        // Ensure we have an array of Rules
        $this->assertIsArray($response);
        $this->assertContainsOnlyInstancesOf(Rule::class, $response);
    }
}
