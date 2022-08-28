<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\SensorModel;

use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use Phue\SensorModel\AbstractSensorModel;

/**
 * Tests for Phue\SensorModel\AbstractSensorModel
 */
class AbstractSensorModelTest extends TestCase
{
    private AbstractSensorModel|Mock $mockAbstractSensorModel;

    public function setUp(): void
    {
        // Mock client
        $this->mockAbstractSensorModel = $this->getMockForAbstractClass(AbstractSensorModel::class);
    }

    /**
     * Test: Get id
     *
     * @covers \Phue\SensorModel\AbstractSensorModel::getId
     */
    public function testGetId(): void
    {
        $this->assertEquals(AbstractSensorModel::MODEL_ID,
            $this->mockAbstractSensorModel->getId());
    }

    /**
     * Test: Get name
     *
     * @covers \Phue\SensorModel\AbstractSensorModel::getName
     */
    public function testGetName(): void
    {
        $this->assertEquals(AbstractSensorModel::MODEL_NAME,
            $this->mockAbstractSensorModel->getName());
    }

    /**
     * Test: To string
     *
     * @covers \Phue\SensorModel\AbstractSensorModel::__toString
     */
    public function testToString(): void
    {
        $this->assertEquals(AbstractSensorModel::MODEL_NAME,
            (string) $this->mockAbstractSensorModel);
    }
}
