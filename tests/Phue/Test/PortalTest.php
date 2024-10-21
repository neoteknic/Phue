<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Phue\Client;
use Phue\Portal;

/**
 * Tests for Phue\Portal
 */
class PortalTest extends TestCase
{
    /** @var Client&MockObject $mockClient */
    private $mockClient;
    private object $attributes;
    private Portal $portal;

    /**
     * @covers \Phue\Portal::__construct
     */
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Build stub attributes
        // $this->attributes = (object) [
        // 'signedon' => true,
        // 'incoming' => false,
        // 'outgoing' => true,
        // 'communication' => 'disconnected'
        // ];
        $this->attributes = (object) [
            'signedon' => true,
            'incoming' => false,
            'outgoing' => true,
            'communication' => 'disconnected'
        ];
        
        // Create portal object
        $this->portal = new Portal($this->attributes, $this->mockClient);
    }

    /**
     * Test: Is signed on?
     *
     * @covers \Phue\Portal::isSignedOn
     */
    public function testIsSignedOn(): void
    {
        $this->assertEquals($this->attributes->signedon, $this->portal->isSignedOn());
    }

    /**
     * Test: Is incoming?
     *
     * @covers \Phue\Portal::isIncoming
     */
    public function testIsIncoming(): void
    {
        $this->assertEquals($this->attributes->incoming, $this->portal->isIncoming());
    }

    /**
     * Test: Is outgoing?
     *
     * @covers \Phue\Portal::isOutgoing
     */
    public function testIsOutgoing(): void
    {
        $this->assertEquals($this->attributes->outgoing, $this->portal->isOutgoing());
    }

    /**
     * Test: Getting communication
     *
     * @covers \Phue\Portal::getCommunication
     */
    public function testGetCommunication(): void
    {
        $this->assertEquals(
            $this->attributes->communication,
            $this->portal->getCommunication()
        );
    }
}
