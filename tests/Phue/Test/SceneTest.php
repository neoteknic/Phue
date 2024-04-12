<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test;

use Mockery\Mock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Phue\Client;
use Phue\Scene;

/**
 * Tests for Phue\Scene
 */
class SceneTest extends TestCase
{
    /** @var MockObject&Client $mockClient */
    private $mockClient;
    private Scene $scene;
    private object $attributes;

    /**
     * @covers \Phue\Scene::__construct
     */
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Build stub attributes
        $this->attributes = (object) [
            'name' => 'Dummy scene',
            'lights' => [
                2,
                3,
                5
            ]
        ];
        
        // Create scene object
        $this->scene = new Scene('custom-id', $this->attributes, $this->mockClient);
    }

    /**
     * Test: Getting Id
     *
     * @covers \Phue\Scene::getId
     */
    public function testGetId(): void
    {
        $this->assertEquals('custom-id', $this->scene->getId());
    }

    /**
     * Test: Getting name
     *
     * @covers \Phue\Scene::getName
     */
    public function testGetName(): void
    {
        $this->assertEquals($this->attributes->name, $this->scene->getName());
    }

    /**
     * Test: Get light ids
     *
     * @covers \Phue\Scene::getLightIds
     */
    public function testGetLightIds(): void
    {
        $this->assertEquals($this->attributes->lights, $this->scene->getLightIds());
    }

    /**
     * Test: toString
     *
     * @covers \Phue\Scene::__toString
     */
    public function testToString(): void
    {
        $this->assertEquals($this->scene->getId(), (string) $this->scene);
    }
}
