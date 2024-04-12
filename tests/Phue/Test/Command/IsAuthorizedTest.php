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
use Phue\Command\IsAuthorized;
use Phue\Transport\Exception\UnauthorizedUserException;

/**
 * Tests for Phue\Command\IsAuthorized
 */
class IsAuthorizedTest extends AbstractCommandTest
{
    /**
     * Test: Is authorized
     *
     * @covers \Phue\Command\IsAuthorized::send
     */
    public function testIsAuthorized(): void
    {
        // Stub transport's sendRequest method
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}"));
        
        $auth = new IsAuthorized();
        $this->assertTrue($auth->send($this->mockClient));
    }

    /**
     * Test: Is not authorized
     *
     * @covers \Phue\Command\IsAuthorized::send
     */
    public function testIsNotAuthorized(): void
    {
        // Stub transport's sendRequest
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}"))
            ->will($this->throwException(
                $this->createMock(UnauthorizedUserException::class)
            ));
        
        $auth = new IsAuthorized();
        $this->assertFalse($auth->send($this->mockClient));
    }
}
