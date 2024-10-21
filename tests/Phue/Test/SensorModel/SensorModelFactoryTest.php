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
use Phue\SensorModel\SensorModelFactory;
use Phue\SensorModel\ZgpswitchModel;
use Phue\SensorModel\UnknownModel;

/**
 * Tests for Phue\SensorModel\SensorModelFactory
 */
class SensorModelFactoryTest extends TestCase
{
    /**
     * Test: Getting unknown model
     *
     * @covers \Phue\SensorModel\SensorModelFactory::build
     */
    public function testGetUnknownModel(): void
    {
        $this->assertInstanceOf(
            UnknownModel::class,
            SensorModelFactory::build('whatever')
        );
    }

    /**
     * Test:: Getting known model
     *
     * @covers \Phue\SensorModel\SensorModelFactory::build
     */
    public function testGetKnownModel(): void
    {
        $this->assertInstanceOf(
            ZgpswitchModel::class,
            SensorModelFactory::build('ZGPSWITCH')
        );
    }
}
