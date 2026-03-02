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
use Phue\Helper\ColorConversion;
use Phue\Group;
use Phue\Command\SetGroupState;
use Phue\Command\DeleteGroup;
use Phue\Command\SetGroupAttributes;

/**
 * Tests for Phue\Group
 */
class GroupTest extends TestCase
{
    /** @var MockObject&Client $mockClient */
    private $mockClient;
    private object $attributes;
    private Group $group;

    public function setUp(): void
    {
        $this->mockClient = $this->createMock(Client::class);
        
        // Build stub attributes
        $this->attributes = (object) [
            'name' => 'Dummy group',
            'action' => (object) [
                'on' => false,
                'bri' => '66',
                'hue' => '60123',
                'sat' => 213,
                'xy' => [
                    0.5,
                    0.4
                ],
                'ct' => 300,
                'colormode' => 'hs',
                'effect' => 'none'
            ],
            'lights' => [
                2,
                3,
                5
            ],
            'type' => 'LightGroup'
        ];
        
        // Create group object
        $this->group = new Group(6, $this->attributes, $this->mockClient);
    }

    /**
     * Test: Getting Id
     *
     */
    public function testGetId(): void
    {
        $this->assertEquals(6, $this->group->getId());
    }

    /**
     * Test: Getting name
     *
     */
    public function testGetName(): void
    {
        $this->assertEquals($this->attributes->name, $this->group->getName());
    }

    /**
     * Test: Setting name
     *
     */
    public function testSetName(): void
    {
        // Stub client's sendCommand method
        $this->mockClient->expects($this->once())
            ->method('sendCommand')
            ->with($this->isInstanceOf(SetGroupAttributes::class))
            ->willReturn($this->group);
        
        // Ensure setName returns self
        $this->assertEquals($this->group, $this->group->setName('new name'));
        
        // Ensure new name can be retrieved by getName
        $this->assertEquals('new name', $this->group->getName());
    }

    /**
     * Test: Get type
     *
     */
    public function testGetType(): void
    {
        $this->assertEquals($this->attributes->type, $this->group->getType());
    }

    /**
     * Test: Get light ids
     *
     */
    public function testGetLightIds(): void
    {
        $this->assertEquals($this->attributes->lights, $this->group->getLightIds());
    }

    /**
     * Test: Set lights
     *
     */
    public function testSetLights(): void
    {
        // Stub client's sendCommand method
        $this->mockClient->expects($this->once())
            ->method('sendCommand')
            ->with($this->isInstanceOf(SetGroupAttributes::class))
            ->willReturn($this->group);
        
        // Ensure setLights return self
        $this->assertEquals(
            $this->group,
            $this->group->setLights(array(
                1,
                2,
                3,
                4
            ))
        );
        
        // Ensure lights can be retrieved by getLights
        $this->assertEquals(
            array(
                1,
                2,
                3,
                4
            ),
            $this->group->getLightIds()
        );
    }

    /**
     * Test: Is/Set on
     *
     */
    public function testIsSetOn(): void
    {
        $this->stubMockClientSendSetGroupStateCommand();
        
        // Make sure original on action is retrievable
        $this->assertFalse($this->group->isOn());
        
        // Ensure setOn returns self
        $this->assertEquals($this->group, $this->group->setOn(true));
        
        // Make sure group attributes are updated
        $this->assertTrue($this->group->isOn());
    }

    /**
     * Test: Get/Set brightness
     *
     */
    public function testGetSetBrightness(): void
    {
        $this->stubMockClientSendSetGroupStateCommand();
        
        // Make sure original brightness is retrievable
        $this->assertEquals(
            $this->attributes->action->bri,
            $this->group->getBrightness()
        );
        
        // Ensure setBrightness returns self
        $this->assertEquals($this->group, $this->group->setBrightness(254));
        
        // Make sure group attributes are updated
        $this->assertEquals(254, $this->group->getBrightness());
    }

    /**
     * Test: Get/Set hue
     *
     */
    public function testGetSetHue(): void
    {
        $this->stubMockClientSendSetGroupStateCommand();
        
        // Make sure original hue is retrievable
        $this->assertEquals($this->attributes->action->hue, $this->group->getHue());
        
        // Ensure setHue returns self
        $this->assertEquals($this->group, $this->group->setHue(30000));
        
        // Make sure group attributes are updated
        $this->assertEquals(30000, $this->group->getHue());
    }

    /**
     * Test: Get/Set saturation
     *
     */
    public function testGetSetSaturation(): void
    {
        $this->stubMockClientSendSetGroupStateCommand();
        
        // Make sure original saturation is retrievable
        $this->assertEquals(
            $this->attributes->action->sat,
            $this->group->getSaturation()
        );
        
        // Ensure setSaturation returns self
        $this->assertEquals($this->group, $this->group->setSaturation(200));
        
        // Make sure group attributes are updated
        $this->assertEquals(200, $this->group->getSaturation());
    }

    /**
     * Test: Get/Set XY
     *
     */
    public function testGetSetXY(): void
    {
        $this->stubMockClientSendSetGroupStateCommand();
        
        // Make sure original xy is retrievable
        $this->assertEquals(
            // [
            // 'x' => $this->attributes->action->xy[0],
            // 'y' => $this->attributes->action->xy[1]
            // ],
            array(
                'x' => $this->attributes->action->xy[0],
                'y' => $this->attributes->action->xy[1]
            ),
            $this->group->getXY()
        );
        
        // Ensure setXY returns self
        $this->assertEquals($this->group, $this->group->setXY(0.1, 0.2));
        
        // Make sure group attributes are updated
        $this->assertEquals(
            array(
                'x' => 0.1,
                'y' => 0.2
            ),
            $this->group->getXY()
        );
    }

    /**
     * Test: Get/Set RGB
     *
     */
    public function testGetSetRGB(): void
    {
        $this->stubMockClientSendSetGroupStateCommand();

        // Make sure original rgb is retrievable
        $rgb = ColorConversion::convertXYToRGB(
            $this->attributes->action->xy[0],
            $this->attributes->action->xy[1],
            $this->attributes->action->bri
        );
        $this->assertEquals(
            array(
                'red' => $rgb['red'],
                'green' => $rgb['green'],
                'blue' => $rgb['blue']
            ),
            $this->group->getRGB()
        );

        // Ensure setRGB returns self
        $this->assertEquals($this->group, $this->group->setRGB(50, 50, 50));

        // Make sure group attributes are updated
        $this->assertEquals(
            array(
                'red' => 50,
                'green' => 50,
                'blue' => 50
            ),
            $this->group->getRGB()
        );
    }

    /**
     * Test: Get/Set Color temp
     *
     */
    public function testGetSetColorTemp(): void
    {
        $this->stubMockClientSendSetGroupStateCommand();
        
        // Make sure original color temp is retrievable
        $this->assertEquals(
            $this->attributes->action->ct,
            $this->group->getColorTemp()
        );
        
        // Ensure setColorTemp returns self
        $this->assertEquals($this->group, $this->group->setColorTemp(412));
        
        // Make sure group attributes are updated
        $this->assertEquals(412, $this->group->getColorTemp());
    }

    /**
     * Test: Get/Set effect
     *
     */
    public function testGetSetEffect(): void
    {
        $this->stubMockClientSendSetGroupStateCommand();
        
        // Make sure original effect is retrievable
        $this->assertEquals(
            $this->attributes->action->effect,
            $this->group->getEffect()
        );
        
        // Ensure setEffect returns self
        $this->assertEquals($this->group, $this->group->setEffect('colorloop'));
        
        // Make sure group attributes are updated
        $this->assertEquals('colorloop', $this->group->getEffect());
    }

    /**
     * Test: Get color mode
     *
     */
    public function testGetColormode(): void
    {
        $this->assertEquals(
            $this->attributes->action->colormode,
            $this->group->getColorMode()
        );
    }

    /**
     * Test: Set scene
     *
     */
    public function testSetScene(): void
    {
        $this->stubMockClientSendSetGroupStateCommand();
        
        // Ensure setScene returns self
        $this->assertEquals($this->group, $this->group->setScene('phue-test'));
    }

    /**
     * Test: Delete
     *
     */
    public function testDelete(): void
    {
        $this->mockClient->expects($this->once())
            ->method('sendCommand')
            ->with($this->isInstanceOf(DeleteGroup::class));
        
        $this->group->delete();
    }

    /**
     * Test: toString
     *
     */
    public function testToString(): void
    {
        $this->assertEquals($this->group->getId(), (string) $this->group);
    }

    /**
     * Stub mock client's send command
     */
    protected function stubMockClientSendSetGroupStateCommand(): void
    {
        $this->mockClient->expects($this->once())
            ->method('sendCommand')
            ->with($this->isInstanceOf(SetGroupState::class));
    }
}

