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
use Phue\Test\AssertHelpersTrait;
use Phue\Transport\TransportInterface;
use ReflectionObject;

/**
 *
 */
class AbstractCommandTest extends TestCase
{
    use AssertHelpersTrait;

    /** @var Client&MockObject $mockClient */
    protected $mockClient;

    /** @var TransportInterface&MockObject $mockTransport */
    public $mockTransport;

    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Mock transport
        $this->mockTransport = $this->createMock(TransportInterface::class);
        
        // Stub client's getUsername method
        $this->mockClient->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue('abcdefabcdef01234567890123456789'));
        
        // Stub client's getTransport method
        $this->mockClient->expects($this->any())
            ->method('getTransport')
            ->will($this->returnValue($this->mockTransport));
    }
}
