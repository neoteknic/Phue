<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\SensorModel;

use PHPUnit\Framework\TestCase;
use Phue\SensorModel\AbstractSensorModel;

/**
 * Tests for Phue\SensorModel\AbstractSensorModel
 */
class AbstractSensorModelTest extends TestCase
{
    private $mockAbstractSensorModel;

    public function setUp(): void
    {
        // Mock client
        $this->mockAbstractSensorModel = $this->getMockForAbstractClass(
            '\Phue\SensorModel\AbstractSensorModel');
    }

    /**
     * Test: Get id
     *
     * @covers \Phue\SensorModel\AbstractSensorModel::getId
     */
    public function testGetId()
    {
        $this->assertEquals(AbstractSensorModel::MODEL_ID,
            $this->mockAbstractSensorModel->getId());
    }

    /**
     * Test: Get name
     *
     * @covers \Phue\SensorModel\AbstractSensorModel::getName
     */
    public function testGetName()
    {
        $this->assertEquals(AbstractSensorModel::MODEL_NAME,
            $this->mockAbstractSensorModel->getName());
    }

    /**
     * Test: To string
     *
     * @covers \Phue\SensorModel\AbstractSensorModel::__toString
     */
    public function testToString()
    {
        $this->assertEquals(AbstractSensorModel::MODEL_NAME,
            (string) $this->mockAbstractSensorModel);
    }
}
