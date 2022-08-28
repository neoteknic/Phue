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
use PHPUnit\Framework\TestCase;
use Phue\Command\UpdateRule;

/**
 * Tests for Phue\Command\UpdateRule
 */
class UpdateRuleTest extends TestCase
{
    /**
     * Test: Instantiating UpdateRule command
     *
     * @covers \Phue\Command\UpdateRule::__construct
     */
    public function testInstantiation(): void
    {
        $command = new UpdateRule('4');
    }

    /**
     * Test: Set name
     *
     * @covers \Phue\Command\UpdateRule::name
     */
    public function testName(): void
    {
        $command = new UpdateRule('4');
        
        $this->assertEquals($command, $command->name('dummy name'));
    }

    /**
     * Test: Send
     *
     * @covers \Phue\Command\UpdateRule::send
     */
    public function testSend(): void
    {
        // Mock client
        $mockClient = Mockery::mock('\Phue\Client', 
            ['getUsername' => 'abcdefabcdef01234567890123456789'])->makePartial();
        
        // Mock client commands
        $mockClient->shouldReceive('getTransport->sendRequest');
        
        $rule = new UpdateRule('5');

        $command = $rule->addCondition(
            Mockery::mock('\Phue\Condition')->makePartial())
            ->addAction(
            Mockery::mock('\Phue\Command\ActionableInterface')->shouldIgnoreMissing())
            ->send($mockClient);
    }
}
