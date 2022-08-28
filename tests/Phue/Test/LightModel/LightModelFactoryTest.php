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
use Phue\LightModel\LightModelFactory;
use Phue\LightModel\Lst001Model;
use Phue\LightModel\UnknownModel;

/**
 * Tests for Phue\LightModel\LightModelFactory
 */
class LightModelFactoryTest extends TestCase
{
    /**
     * Test: Getting unknown model
     *
     * @covers \Phue\LightModel\LightModelFactory::build
     */
    public function testGetUnknownModel(): void
    {
        $this->assertInstanceOf(UnknownModel::class, LightModelFactory::build('whatever'));
    }

    /**
     * Test:: Getting known model
     *
     * @covers \Phue\LightModel\LightModelFactory::build
     */
    public function testGetKnownModel(): void
    {
        $this->assertInstanceOf(Lst001Model::class, LightModelFactory::build('LST001'));
    }
}
