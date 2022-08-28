<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test;

use ReflectionObject;

trait AssertHelpersTrait
{
    /**
     * @throws \ReflectionException
     */
    public function assertAttributeEquals(mixed $expect, string $property, object $obj): void
    {
        $r = new ReflectionObject($obj);
        $p = $r->getProperty($property);
        $p->setAccessible(true);

        $this->assertEquals($expect, $p->getValue($obj));
    }
}
