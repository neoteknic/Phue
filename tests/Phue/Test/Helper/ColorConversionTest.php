<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Helper;

use PHPUnit\Framework\TestCase;
use Phue\Helper\ColorConversion;

/**
 * Tests for Phue\Helper\ColorConversion
 */
class ColorConversionTest extends TestCase
{
    /**
     * Test: convert RGB to XY and brightness
     *
     * @covers \Phue\Helper\ColorConversion::convertRGBToXY
     */
    public function testConvertRGBToXY(): void
    {
        // Values from: http://www.developers.meethue.com/documentation/hue-xy-values
        
        // Alice Blue
        $xy = ColorConversion::convertRGBToXY(239, 247, 255);
        $this->assertEqualsWithDelta(0.3088, $xy['x'], 0.0001, '');
        $this->assertEqualsWithDelta(0.3212, $xy['y'], 0.0001, '');
        $this->assertEquals(233, $xy['bri']);
        
        // Firebrick
        $xy = ColorConversion::convertRGBToXY(178, 33, 33);
        $this->assertEqualsWithDelta(0.6622, $xy['x'], 0.0001, '');
        $this->assertEqualsWithDelta(0.3024, $xy['y'], 0.0001, '');
        $this->assertEquals(35, $xy['bri']);
        
        // Medium Sea Green
        $xy = ColorConversion::convertRGBToXY(61, 178, 112);
        $this->assertEqualsWithDelta(0.1979, $xy['x'], 0.0001, '');
        $this->assertEqualsWithDelta(0.5005, $xy['y'], 0.0001, '');
        $this->assertEquals(81, $xy['bri']);
    }
    
    /**
     * Test: convert XY and brightness to RGB
     *
     * @covers \Phue\Helper\ColorConversion::convertXYToRGB
     */
    public function testConvertXYToRGB(): void
    {
        // Conversion back from the test above
        
        // Alice Blue
        $rgb = ColorConversion::convertXYToRGB(0.3088, 0.3212, 233);
        $this->assertEquals(239, $rgb['red']);
        $this->assertEquals(247, $rgb['green']);
        $this->assertEquals(255, $rgb['blue']);
        
        // Firebrick
        $rgb = ColorConversion::convertXYToRGB(0.6622, 0.3024, 35);
        $this->assertEquals(178, $rgb['red']);
        $this->assertEquals(33, $rgb['green']);
        $this->assertEquals(33, $rgb['blue']);
        
        // Medium Sea Green
        $rgb = ColorConversion::convertXYToRGB(0.1979, 0.5005, 81);
        $this->assertEquals(61, $rgb['red']);
        $this->assertEquals(178, $rgb['green']);
        $this->assertEquals(112, $rgb['blue']);
        
        // Test to make sure single RGB values falls within 0..255 range.
        // old situation this was r -18, g 186, b -613.
        $rgb = ColorConversion::convertXYToRGB(0.1979, 1.5005, 81);
        $this->assertEquals(0, $rgb['red']);
        $this->assertEquals(186, $rgb['green']);
        $this->assertEquals(0, $rgb['blue']);
    }
}