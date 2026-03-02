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
    private AbstractSensorModel $mockAbstractSensorModel;

    public function setUp(): void
    {
        $this->mockAbstractSensorModel = new class extends AbstractSensorModel {
        };
    }

    /**
     * Test: Get id
     *
     */
    public function testGetId(): void
    {
        $this->assertEquals(
            AbstractSensorModel::MODEL_ID,
            $this->mockAbstractSensorModel->getId()
        );
    }

    /**
     * Test: Get name
     *
     */
    public function testGetName(): void
    {
        $this->assertEquals(
            AbstractSensorModel::MODEL_NAME,
            $this->mockAbstractSensorModel->getName()
        );
    }

    /**
     * Test: To string
     *
     */
    public function testToString(): void
    {
        $this->assertEquals(
            AbstractSensorModel::MODEL_NAME,
            (string) $this->mockAbstractSensorModel
        );
    }
}

