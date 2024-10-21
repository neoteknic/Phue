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
use Phue\Sensor;
use Phue\SensorModel\AbstractSensorModel;

/**
 * Tests for Phue\Sensor
 */
class SensorTest extends TestCase
{
    /** @var MockObject&Client $mockClient */
    private $mockClient;
    private object $attributes;
    private Sensor $sensor;

    /**
     * @covers \Phue\Sensor::__construct
     */
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Build stub attributes
        $this->attributes = (object) [
            'state' => [
                'daylight' => false,
                'lastupdated' => '2014-06-27T07:38:51'
            ],
            'config' => [
                'on' => true,
                'long' => 'none',
                'lat' => 'none',
                'sunriseoffset' => 50,
                'sunsetoffset' => 50
            ],
            'name' => 'Daylight',
            'type' => 'Daylight',
            'modelid' => 'PHDL00',
            'manufacturername' => 'Philips',
            'swversion' => '1.0',
            'uniqueid' => '00:00:00:00:00:40:03:50-f2'
        ];
        
        // Create sensor object
        $this->sensor = new Sensor(7, $this->attributes, $this->mockClient);
    }

    /**
     * Test: Getting Id
     *
     * @covers \Phue\Sensor::getId
     */
    public function testGetId(): void
    {
        $this->assertEquals(7, $this->sensor->getId());
    }

    /**
     * Test: Getting name
     *
     * @covers \Phue\Sensor::getName
     */
    public function testGetName(): void
    {
        $this->assertEquals($this->attributes->name, $this->sensor->getName());
    }

    /**
     * Test: Get type
     *
     * @covers \Phue\Sensor::getType
     */
    public function testGetType(): void
    {
        $this->assertEquals($this->attributes->type, $this->sensor->getType());
    }

    /**
     * Test: Get model id
     *
     * @covers \Phue\Sensor::getModelId
     */
    public function testGetModelId(): void
    {
        $this->assertEquals($this->attributes->modelid, $this->sensor->getModelId());
    }

    /**
     * Test: Get model
     *
     * @covers \Phue\Sensor::getModel
     */
    public function testGetModel(): void
    {
        $this->assertInstanceOf(
            AbstractSensorModel::class,
            $this->sensor->getModel()
        );
    }

    /**
     * Test: Get manufacturer name
     *
     * @covers \Phue\Sensor::getManufacturerName
     */
    public function testGetManufacturerName(): void
    {
        $this->assertEquals(
            $this->attributes->manufacturername,
            $this->sensor->getManufacturerName()
        );
    }

    /**
     * Test: Get software version
     *
     * @covers \Phue\Sensor::getSoftwareVersion
     */
    public function testGetSoftwareVersion(): void
    {
        $this->assertEquals(
            $this->attributes->swversion,
            $this->sensor->getSoftwareVersion()
        );
    }

    /**
     * Test: Get null software version
     *
     * @covers \Phue\Sensor::getSoftwareVersion
     */
    public function testGetNullSoftwareVersion(): void
    {
        unset($this->attributes->swversion);
        
        $this->assertNull($this->sensor->getSoftwareVersion());
    }

    /**
     * Test: Get unique id
     *
     * @covers \Phue\Sensor::getUniqueId
     */
    public function testGetUniqueId(): void
    {
        $this->assertEquals(
            $this->attributes->uniqueid,
            $this->sensor->getUniqueId()
        );
    }

    /**
     * Test: Get null unique id
     *
     * @covers \Phue\Sensor::getUniqueId
     */
    public function testGetNullUniqueId(): void
    {
        unset($this->attributes->uniqueid);
        
        $this->assertNull($this->sensor->getUniqueId());
    }

    /**
     * Test: Get state
     *
     * @covers \Phue\Sensor::getState
     */
    public function testGetState(): void
    {
        $this->assertInstanceOf(\stdClass::class, $this->sensor->getState());
    }

    /**
     * Test: Get config
     *
     * @covers \Phue\Sensor::getConfig
     */
    public function testGetConfig(): void
    {
        $this->assertInstanceOf(\stdClass::class, $this->sensor->getConfig());
    }

    /**
     * Test: toString
     *
     * @covers \Phue\Sensor::__toString
     */
    public function testToString(): void
    {
        $this->assertEquals($this->sensor->getId(), (string) $this->sensor);
    }
}
