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
use Phue\Scene;

/**
 * Tests for Phue\Scene
 */
class SceneTest extends TestCase
{
    private $mockClient;
    private Scene $scene;
    private object $attributes;

    /**
     * @covers \Phue\Scene::__construct
     */
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock('\Phue\Client');
        
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
    public function testGetId()
    {
        $this->assertEquals('custom-id', $this->scene->getId());
    }

    /**
     * Test: Getting name
     *
     * @covers \Phue\Scene::getName
     */
    public function testGetName()
    {
        $this->assertEquals($this->attributes->name, $this->scene->getName());
    }

    /**
     * Test: Get light ids
     *
     * @covers \Phue\Scene::getLightIds
     */
    public function testGetLightIds()
    {
        $this->assertEquals($this->attributes->lights, $this->scene->getLightIds());
    }

    /**
     * Test: toString
     *
     * @covers \Phue\Scene::__toString
     */
    public function testToString()
    {
        $this->assertEquals($this->scene->getId(), (string) $this->scene);
    }
}
