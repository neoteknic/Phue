<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Command;

use Mockery;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Phue\Client;
use Phue\Command\ActionableInterface;
use Phue\Command\CreateRule;
use Phue\Condition;

/**
 * Tests for Phue\Command\CreateRule
 */
class CreateRuleTest extends TestCase
{
    /**
     * Test: Instantiating CreateRule command
     *
     * @covers \Phue\Command\CreateRule::__construct
     */
    public function testInstantiation(): void
    {
        $command = new CreateRule('dummy name');
    }

    /**
     * Test: Set name
     *
     * @covers \Phue\Command\CreateRule::name
     */
    public function testName(): void
    {
        $command = new CreateRule();
        
        $this->assertEquals($command, $command->name('dummy name'));
    }

    /**
     * Test: Set name
     *
     * @covers \Phue\Command\CreateRule::addCondition
     */
    public function testAddCondition(): void
    {
        /** @var Condition $condition */
        $condition = Mockery::mock('\Phue\Condition')->makePartial();
        
        $command = new CreateRule();
        
        $this->assertEquals($command, $command->addCondition($condition));
    }

    /**
     * Test: Set name
     *
     * @covers \Phue\Command\CreateRule::addAction
     */
    public function testAddAction(): void
    {
        /** @var ActionableInterface $action */
        $action = Mockery::mock('\Phue\Command\ActionableInterface')->makePartial();
        
        $command = new CreateRule();
        
        $this->assertEquals($command, $command->addAction($action));
    }

    /**
     * Test: Send
     *
     * @covers \Phue\Command\CreateRule::send
     */
    public function testSend(): void
    {
        /** @var Client|MockObject $mockClient */
        $mockClient = Mockery::mock('\Phue\Client', 
            [
                'getUsername' => 'abcdefabcdef01234567890123456789'
            ])->makePartial();
        
        // Mock client commands
        $mockClient->shouldReceive('getTransport->sendRequest')->
        andReturn((object) [
            'id' => '5'
        ]);
        
        $x = new CreateRule('test');
        $command = $x->addCondition(Mockery::mock('\Phue\Condition')->makePartial())
            ->addAction(
            Mockery::mock('\Phue\Command\ActionableInterface')->shouldIgnoreMissing());
        
        $this->assertEquals('5', $command->send($mockClient));
    }
}
