<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\TimePattern;

use PHPUnit\Framework\TestCase;
use Phue\TimePattern\Timer;

/**
 * Tests for Phue\TimePattern\Timer
 */
class TimerTest extends TestCase
{
    /**
     * Test: Creating recurring time
     *
     * @covers \Phue\TimePattern\Timer
     */
    public function testCreateTime(): void
    {
        $timer = new Timer(3925);
        $this->assertMatchesRegularExpression('/^R12\/PT01:05:25$/', (string) $timer->repeat(12));
    }
}
