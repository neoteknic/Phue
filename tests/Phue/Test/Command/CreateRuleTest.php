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
use Mockery\MockInterface;
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
        $condition = Mockery::mock(Condition::class)->makePartial();
        
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
        $action = Mockery::mock(ActionableInterface::class)->makePartial();
        
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
        /** @var Client&MockInterface $mockClient */
        $mockClient = Mockery::mock(Client::class, ['getUsername' => 'abcdefabcdef01234567890123456789'])
            ->makePartial();
        
        // Mock client commands
        $mockClient->shouldReceive('getTransport->sendRequest')->
        andReturn((object) [
            'id' => '5'
        ]);
        
        $x = new CreateRule('test');

        /** @var Condition&MockInterface $condition */
        $condition = Mockery::mock(Condition::class)->makePartial();

        /** @var ActionableInterface&MockInterface $action */
        $action = Mockery::mock(ActionableInterface::class)->shouldIgnoreMissing();

        $command = $x->addCondition($condition)
                     ->addAction($action);
        
        $this->assertEquals('5', $command->send($mockClient));
    }
}
