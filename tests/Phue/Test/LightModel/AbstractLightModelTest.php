<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\LightModel;

use PHPUnit\Framework\TestCase;
use Phue\LightModel\AbstractLightModel;

/**
 * Tests for Phue\LightModel\AbstractLightModel
 */
class AbstractLightModelTest extends TestCase
{
    private AbstractLightModel $mockAbstractLightModel;

    public function setUp(): void
    {
        $this->mockAbstractLightModel = new class extends AbstractLightModel {
        };
    }

    /**
     * Test: Get id
     *
     */
    public function testGetId(): void
    {
        $this->assertEquals(
            AbstractLightModel::MODEL_ID,
            $this->mockAbstractLightModel->getId()
        );
    }

    /**
     * Test: Get name
     *
     */
    public function testGetName(): void
    {
        $this->assertEquals(
            AbstractLightModel::MODEL_NAME,
            $this->mockAbstractLightModel->getName()
        );
    }

    /**
     * Test: Can retain state
     *
     */
    public function testCanRetainState(): void
    {
        $this->assertEquals(
            AbstractLightModel::CAN_RETAIN_STATE,
            $this->mockAbstractLightModel->canRetainState()
        );
    }

    /**
     * Test: To string
     *
     */
    public function testToString(): void
    {
        $this->assertEquals(
            AbstractLightModel::MODEL_NAME,
            (string) $this->mockAbstractLightModel
        );
    }
}

