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
use Phue\Light;
use Phue\Command\SetLightState;
use Phue\LightModel\AbstractLightModel;
use Phue\Command\SetLightName;

/**
 * Tests for Phue\Light
 */
class LightTest extends TestCase
{
    private object $attributes;
    /** @var MockObject&Client $mockClient */
    private $mockClient;
    private Light $light;

    public function setUp(): void
    {
        $this->mockClient = $this->createMock(Client::class);
        
        // Build stub attributes
        $this->attributes = (object) [
            'name' => 'Hue light',
            'type' => 'Dummy type',
            'modelid' => 'LCT001',
            'uniqueid' => '00:17:88:01:00:bd:d6:54-0d',
            'swversion' => '12345',
            'state' => (object) [
                'on' => false,
                'bri' => '66',
                'hue' => '60123',
                'sat' => 213,
                'xy' => [
                    .5,
                    .4
                ],
                'ct' => 300,
                'alert' => 'none',
                'effect' => 'none',
                'colormode' => 'hs',
                'reachable' => true
            ]
        ];
        
        // Create light object
        $this->light = new Light(5, $this->attributes, $this->mockClient);
    }

    /**
     * Test: Getting Id
     *
     */
    public function testGetId(): void
    {
        $this->assertEquals(5, $this->light->getId());
    }

    /**
     * Test: Getting name
     *
     */
    public function testGetName(): void
    {
        $this->assertEquals($this->attributes->name, $this->light->getName());
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
            ->with($this->isInstanceOf(SetLightName::class))
            ->willReturn($this->light);
        
        // Ensure setName returns self
        $this->assertEquals($this->light, $this->light->setName('new name'));
        
        // Ensure new name can be retrieved by getName
        $this->assertEquals('new name', $this->light->getName());
    }

    /**
     * Test: Get type
     *
     */
    public function testGetType(): void
    {
        $this->assertEquals($this->attributes->type, $this->light->getType());
    }

    /**
     * Test: Get model Id
     *
     */
    public function testGetModelId(): void
    {
        $this->assertEquals($this->attributes->modelid, $this->light->getModelId());
    }

    /**
     * Test: Get model
     *
     */
    public function testGetModel(): void
    {
        $this->assertInstanceOf(
            AbstractLightModel::class,
            $this->light->getModel()
        );
    }

    /**
     * Test: Get unique id
     *
     */
    public function testGetUniqueId(): void
    {
        $this->assertEquals($this->attributes->uniqueid, $this->light->getUniqueId());
    }

    /**
     * Test: Get software version
     *
     */
    public function testGetSoftwareVersion(): void
    {
        $this->assertEquals(
            $this->attributes->swversion,
            $this->light->getSoftwareVersion()
        );
    }

    /**
     * Test: Is/Set on
     *
     */
    public function testIsSetOn(): void
    {
        $this->stubMockClientSendSetLightStateCommand();
        
        // Make sure original on state is retrievable
        $this->assertFalse($this->light->isOn());
        
        // Ensure setOn returns self
        $this->assertEquals($this->light, $this->light->setOn(true));
        
        // Make sure light attributes are updated
        $this->assertTrue($this->light->isOn());
    }

    /**
     * Test: Get/Set brightness
     *
     */
    public function testGetSetBrightness(): void
    {
        $this->stubMockClientSendSetLightStateCommand();
        
        // Make sure original brightness is retrievable
        $this->assertEquals(
            $this->attributes->state->bri,
            $this->light->getBrightness()
        );
        
        // Ensure setBrightness returns self
        $this->assertEquals($this->light, $this->light->setBrightness(254));
        
        // Make sure light attributes are updated
        $this->assertEquals(254, $this->light->getBrightness());
    }

    /**
     * Test: Get/Set hue
     *
     */
    public function testGetSetHue(): void
    {
        $this->stubMockClientSendSetLightStateCommand();
        
        // Make sure original hue is retrievable
        $this->assertEquals($this->attributes->state->hue, $this->light->getHue());
        
        // Ensure setHue returns self
        $this->assertEquals($this->light, $this->light->setHue(30000));
        
        // Make sure light attributes are updated
        $this->assertEquals(30000, $this->light->getHue());
    }

    /**
     * Test: Get/Set saturation
     *
     */
    public function testGetSetSaturation(): void
    {
        $this->stubMockClientSendSetLightStateCommand();
        
        // Make sure original saturation is retrievable
        $this->assertEquals(
            $this->attributes->state->sat,
            $this->light->getSaturation()
        );
        
        // Ensure setSaturation returns self
        $this->assertEquals($this->light, $this->light->setSaturation(200));
        
        // Make sure light attributes are updated
        $this->assertEquals(200, $this->light->getSaturation());
    }

    /**
     * Test: Get/Set XY
     *
     */
    public function testGetSetXY(): void
    {
        $this->stubMockClientSendSetLightStateCommand();
        
        // Make sure original xy is retrievable
        $this->assertEquals(
            array(
                'x' => $this->attributes->state->xy[0],
                'y' => $this->attributes->state->xy[1]
            ),
            $this->light->getXY()
        );
        
        // Ensure setXY returns self
        $this->assertEquals($this->light, $this->light->setXY(0.1, 0.2));
        
        // Make sure light attributes are updated
        $this->assertEquals(
            array(
                'x' => 0.1,
                'y' => 0.2
            ),
            $this->light->getXY()
        );
    }

    /**
     * Test: Get/Set RGB
     *
     */
    public function testGetSetRGB(): void
    {
        $this->stubMockClientSendSetLightStateCommand();

        // Make sure original rgb is retrievable
        $rgb = ColorConversion::convertXYToRGB(
            $this->attributes->state->xy[0],
            $this->attributes->state->xy[1],
            $this->attributes->state->bri
        );
        $this->assertEquals(
            array(
                'red' => $rgb['red'],
                'green' => $rgb['green'],
                'blue' => $rgb['blue']
            ),
            $this->light->getRGB()
        );

        // Ensure setRGB returns self
        $this->assertEquals($this->light, $this->light->setRGB(50, 50, 50));

        // Make sure light attributes are updated
        $this->assertEquals(
            array(
                'red' => 50,
                'green' => 50,
                'blue' => 50
            ),
            $this->light->getRGB()
        );
    }

    /**
     * Test: Get/Set Color temp
     *
     */
    public function testGetSetColorTemp(): void
    {
        $this->stubMockClientSendSetLightStateCommand();
        
        // Make sure original color temp is retrievable
        $this->assertEquals(
            $this->attributes->state->ct,
            $this->light->getColorTemp()
        );
        
        // Ensure setColorTemp returns self
        $this->assertEquals($this->light, $this->light->setColorTemp(412));
        
        // Make sure light attributes are updated
        $this->assertEquals(412, $this->light->getColorTemp());
    }

    /**
     * Test: Get/Set alert
     *
     */
    public function testGetSetAlert(): void
    {
        $this->stubMockClientSendSetLightStateCommand();
        
        // Make sure original alert is retrievable
        $this->assertEquals(
            $this->attributes->state->alert,
            $this->light->getAlert()
        );
        
        // Ensure setAlert returns self
        $this->assertEquals($this->light, $this->light->setAlert('lselect'));
        
        // Make sure light attributes are updated
        $this->assertEquals('lselect', $this->light->getAlert());
    }

    /**
     * Test: Get/Set effect
     *
     */
    public function testGetSetEffect(): void
    {
        $this->stubMockClientSendSetLightStateCommand();
        
        // Make sure original effect is retrievable
        $this->assertEquals(
            $this->attributes->state->effect,
            $this->light->getEffect()
        );
        
        // Ensure setEffect returns self
        $this->assertEquals($this->light, $this->light->setEffect('colorloop'));
        
        // Make sure light attributes are updated
        $this->assertEquals('colorloop', $this->light->getEffect());
    }

    /**
     * Test: Get color mode
     *
     */
    public function testGetColormode(): void
    {
        $this->assertEquals(
            $this->attributes->state->colormode,
            $this->light->getColorMode()
        );
    }

    /**
     * Test: Get color mode (missing)
     *
     */
    public function testGetColormodeMissing(): void
    {
        $reflection = new \ReflectionClass($this->light);
        $property = $reflection->getProperty('attributes');

        $property->setValue(
            $this->light,
            (object) array(
                'state' => (object) array()
            )
        );

        $this->assertNull($this->light->getColorMode());
    }

    /**
     * Test: Is reachable
     *
     */
    public function testIsReachable(): void
    {
        $this->assertEquals(
            $this->attributes->state->reachable,
            $this->light->isReachable()
        );
    }

    /**
     * Test: toString
     *
     */
    public function testToString(): void
    {
        $this->assertEquals($this->light->getId(), (string) $this->light);
    }

    /**
     * Stub mock client's send command
     */
    protected function stubMockClientSendSetLightStateCommand(): void
    {
        $this->mockClient->expects($this->once())
            ->method('sendCommand')
            ->with($this->isInstanceOf(SetLightState::class));
    }
}

