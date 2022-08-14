<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test;

use PHPUnit\Framework\TestCase;
use Phue\Portal;

/**
 * Tests for Phue\Portal
 */
class PortalTest extends TestCase
{
    private $mockClient;
    private object $attributes;
    private Portal $portal;

    /**
     * @covers \Phue\Portal::__construct
     */
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock('\Phue\Client');
        
        // Build stub attributes
        // $this->attributes = (object) [
        // 'signedon' => true,
        // 'incoming' => false,
        // 'outgoing' => true,
        // 'communication' => 'disconnected'
        // ];
        $this->attributes = (object) array(
            'signedon' => true,
            'incoming' => false,
            'outgoing' => true,
            'communication' => 'disconnected'
        );
        
        // Create portal object
        $this->portal = new Portal($this->attributes, $this->mockClient);
    }

    /**
     * Test: Is signed on?
     *
     * @covers \Phue\Portal::isSignedOn
     */
    public function testIsSignedOn()
    {
        $this->assertEquals($this->attributes->signedon, $this->portal->isSignedOn());
    }

    /**
     * Test: Is incoming?
     *
     * @covers \Phue\Portal::isIncoming
     */
    public function testIsIncoming()
    {
        $this->assertEquals($this->attributes->incoming, $this->portal->isIncoming());
    }

    /**
     * Test: Is outgoing?
     *
     * @covers \Phue\Portal::isOutgoing
     */
    public function testIsOutgoing()
    {
        $this->assertEquals($this->attributes->outgoing, $this->portal->isOutgoing());
    }

    /**
     * Test: Getting communication
     *
     * @covers \Phue\Portal::getCommunication
     */
    public function testGetCommunication()
    {
        $this->assertEquals($this->attributes->communication, 
            $this->portal->getCommunication());
    }
}
