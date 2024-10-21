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
use Phue\Command\DeleteRule;
use Phue\Transport\TransportInterface;

/**
 * Tests for Phue\Command\DeleteRule
 */
class DeleteRuleTest extends AbstractCommandTest
{
    /**
     * Test: Send command
     *
     * @covers \Phue\Command\DeleteRule::__construct
     * @covers \Phue\Command\DeleteRule::send
     */
    public function testSend(): void
    {
        $command = new DeleteRule(5);
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo("/api/{$this->mockClient->getUsername()}/rules/5"),
                $this->equalTo(TransportInterface::METHOD_DELETE)
            );
        
        // Send command
        $command->send($this->mockClient);
    }
}
