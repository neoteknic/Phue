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
     */
    public function testIsSignedOn(): void
    {
        $this->assertEquals($this->attributes->signedon, $this->portal->isSignedOn());
    }

    /**
     * Test: Is incoming?
     *
     */
    public function testIsIncoming(): void
    {
        $this->assertEquals($this->attributes->incoming, $this->portal->isIncoming());
    }

    /**
     * Test: Is outgoing?
     *
     */
    public function testIsOutgoing(): void
    {
        $this->assertEquals($this->attributes->outgoing, $this->portal->isOutgoing());
    }

    /**
     * Test: Getting communication
     *
     */
    public function testGetCommunication(): void
    {
        $this->assertEquals(
            $this->attributes->communication,
            $this->portal->getCommunication()
        );
    }
}

