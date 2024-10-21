<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test;

use Mockery\Mock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Phue\Client;
use Phue\Rule;
use Phue\Command\DeleteRule;
use Phue\Condition;

/**
 * Tests for Phue\Rule
 */
#[CoversClass(Rule::class)]
#[CoversFunction('getId')]
class RuleTest extends TestCase
{
    /** @var MockObject&Client $mockClient */
    private $mockClient;
    private object $attributes;
    private Rule $rule;

    /**
     * @covers \Phue\Rule::__construct
     */
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Build stub attributes
        // $this->attributes = (object) [
        // 'name' => 'Wall Switch Rule',
        // 'lasttriggered' => '2013-10-17T01:23:20',
        // 'created' => '2013-10-10T21:11:45',
        // 'timestriggered' => 27,
        // 'owner' => '78H56B12BA',
        // 'status' => 'enabled',
        // 'conditions' => [
        // (object) [
        // 'address' => '/sensors/2/state/buttonevent',
        // 'operator' => 'eq',
        // 'value' => '16'
        // ],
        // (object) [
        // 'address' => '/sensors/2/state/lastupdated',
        // 'operator' => 'dx'
        // ]
        // ],
        // 'actions' => [
        // (object) [
        // 'address' => '/groups/0/action',
        // 'method' => 'PUT',
        // 'body' => [
        // 'scene' => 'S3'
        // ]
        // ]
        // ]
        // ];
        $this->attributes = (object) [
            'name' => 'Wall Switch Rule',
            'lasttriggered' => '2013-10-17T01:23:20',
            'created' => '2013-10-10T21:11:45',
            'timestriggered' => 27,
            'owner' => '78H56B12BA',
            'status' => 'enabled',
            'conditions' => [
                (object) [
                    'address' => '/sensors/2/state/buttonevent',
                    'operator' => 'eq',
                    'value' => '16'
                ],
                (object) [
                    'address' => '/sensors/2/state/lastupdated',
                    'operator' => 'dx'
                ]
            ],
            'actions' => [
                (object) [
                    'address' => '/groups/0/action',
                    'method' => 'PUT',
                    'body' => [
                        'scene' => 'S3'
                    ]
                ]
            ]
        ];
        
        // Create rule object
        $this->rule = new Rule(4, $this->attributes, $this->mockClient);
    }

    public function testGetId(): void
    {
        $this->assertEquals(4, $this->rule->getId());
    }

    /**
     * Test: Getting name
     *
     * @covers \Phue\Rule::getName
     */
    public function testGetName(): void
    {
        $this->assertEquals($this->attributes->name, $this->rule->getName());
    }

    /**
     * Test: Getting last triggered time
     *
     * @covers \Phue\Rule::getLastTriggeredTime
     */
    public function testGetLastTriggeredTime(): void
    {
        $this->assertEquals(
            $this->attributes->lasttriggered,
            $this->rule->getLastTriggeredTime()
        );
    }

    /**
     * Test: Getting create date
     *
     * @covers \Phue\Rule::getCreateDate
     */
    public function testGetCreateDate(): void
    {
        $this->assertEquals($this->attributes->created, $this->rule->getCreateDate());
    }

    /**
     * Test: Getting triggered count
     *
     * @covers \Phue\Rule::getTriggeredCount
     */
    public function testGetTriggeredCount(): void
    {
        $this->assertEquals(
            $this->attributes->timestriggered,
            $this->rule->getTriggeredCount()
        );
    }

    /**
     * Test: Get owner
     *
     * @covers \Phue\Rule::getOwner
     */
    public function testGetOwner(): void
    {
        $this->assertEquals($this->attributes->owner, $this->rule->getOwner());
    }

    /**
     * Test: Is enabled?
     *
     * @covers \Phue\Rule::isEnabled
     */
    public function testIsEnabled(): void
    {
        $this->assertTrue($this->rule->isEnabled());
    }

    /**
     * Test: Get conditions
     *
     * @covers \Phue\Rule::getConditions
     */
    public function testGetConditions(): void
    {
        $conditions = $this->rule->getConditions();
        
        $this->assertCount(2, $conditions);
        
        $this->assertContainsOnlyInstancesOf(Condition::class, $conditions);
    }

    /**
     * Test: Get actions
     *
     * @covers \Phue\Rule::getActions
     */
    public function testGetActions(): void
    {
        $actions = $this->rule->getActions();
        
        $this->assertCount(1, $actions);
        
        $this->assertContainsOnlyInstancesOf(\stdClass::class, $actions);
    }

    /**
     * Test: Delete
     *
     * @covers \Phue\Rule::delete
     */
    public function testDelete(): void
    {
        $this->mockClient->expects($this->once())
            ->method('sendCommand')
            ->with($this->isInstanceOf(DeleteRule::class));
        
        $this->rule->delete();
    }

    /**
     * Test: toString
     *
     * @covers \Phue\Rule::__toString
     */
    public function testToString(): void
    {
        $this->assertEquals($this->rule->getId(), (string) $this->rule);
    }
}
