<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test;

use PHPUnit\Framework\TestCase;
use Phue\Condition;

/**
 * Tests for Phue\Condition
 */
class ConditionTest extends TestCase
{
    private Condition $condition;

    /**
     * Set up
     *
     */
    public function setUp(): void
    {
        $this->condition = new Condition(
            (object) [
                'address' => '/sensors/2/state/buttonevent',
                'operator' => 'eq',
                'value' => '16'
            ]
        );
    }

    /**
     * Test: Get/set sensor id
     *
     */
    public function testGetSetSensorId(): void
    {
        $this->assertEquals('2', $this->condition->getSensorId());
        
        $this->condition->setSensorId('3');
        
        $this->assertEquals('3', $this->condition->getSensorId());
    }

    /**
     * Test: Get/set attribute
     *
     */
    public function testGetSetAttribute(): void
    {
        $this->assertEquals('buttonevent', $this->condition->getAttribute());
        
        $this->condition->setAttribute('dummy');
        
        $this->assertEquals('dummy', $this->condition->getAttribute());
    }

    /**
     * Test: Get/set operator
     *
     */
    public function testGetSetOperator(): void
    {
        $this->assertEquals('eq', $this->condition->getOperator());
        
        $this->condition->setOperator('dx');
        
        $this->assertEquals('dx', $this->condition->getOperator());
    }

    /**
     * Test: Get/set value
     *
     */
    public function testGetSetValue(): void
    {
        $this->assertEquals('16', $this->condition->getValue());
        
        $this->condition->setValue('20');
        
        $this->assertEquals('20', $this->condition->getValue());
    }

    /**
     * Test: Export
     *
     */
    public function testExport(): void
    {
        $this->assertEquals(
            (object) array(
                'address' => '/sensors/2/state/buttonevent',
                'operator' => 'eq',
                'value' => '16'
            ),
            $this->condition->export()
        );
    }

    /**
     * Test: Equals
     *
     */
    public function testEquals(): void
    {
        $this->condition->equals();
        
        $this->assertEquals(
            Condition::OPERATOR_EQUALS,
            $this->condition->getOperator()
        );
    }

    /**
     * Test: Greater than
     *
     */
    public function testGreaterThan(): void
    {
        $this->condition->greaterThan();
        
        $this->assertEquals(
            Condition::OPERATOR_GREATER_THAN,
            $this->condition->getOperator()
        );
    }

    /**
     * Test: Less than
     *
     */
    public function testLessThan(): void
    {
        $this->condition->lessThan();
        
        $this->assertEquals(
            Condition::OPERATOR_LESS_THAN,
            $this->condition->getOperator()
        );
    }

    /**
     * Test: Changed
     *
     */
    public function testChanged(): void
    {
        $this->condition->changed();
        
        $this->assertEquals(
            Condition::OPERATOR_CHANGED,
            $this->condition->getOperator()
        );
    }
}

