<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Command;

use PHPUnit\Framework\TestCase;
use Phue\Command\CreateScene;
use Phue\Transport\TransportInterface;

/**
 * Tests for Phue\Command\CreateScene
 */
class CreateSceneTest extends AbstractCommandTest
{
    /**
     * Test: Set Id
     *
     * @covers \Phue\Command\CreateScene::__construct
     * @covers \Phue\Command\CreateScene::id
     */
    public function testId(): void
    {
        $command = new CreateScene('phue-test', 'Scene test');
        
        // Ensure property is set properly
        $this->assertAttributeEquals('phue-test', 'id', $command);
        
        // Ensure self object is returned
        $this->assertEquals($command, $command->id('phue-test'));
    }

    /**
     * Test: Set name
     *
     * @covers \Phue\Command\CreateScene::__construct
     * @covers \Phue\Command\CreateScene::name
     */
    public function testName(): void
    {
        $command = new CreateScene('phue-test', 'Scene test');
        
        // Ensure property is set properly
        $this->assertEquals('Scene test', $command->getName());

        // Ensure self object is returned
        $this->assertEquals($command, $command->name('Scene test'));
    }

    /**
     * Test: Set lights
     *
     * @covers \Phue\Command\CreateScene::__construct
     * @covers \Phue\Command\CreateScene::lights
     */
    public function testLights(): void
    {
        $command = new CreateScene('phue-test', 'Scene test', [
            1,
            2
        ]);
        
        // Ensure property is set properly
        $this->assertAttributeEquals([1, 2], 'lights', $command);

        // Ensure self object is returned
        $this->assertEquals($command, $command->lights([1]));
    }

    /**
     * Test: Set transition time
     *
     * @covers \Phue\Command\CreateScene::transitionTime
     */
    public function testTransitionTime(): void
    {
        $command = new CreateScene('phue-test', 'Scene test', array(
            1,
            2
        ));
        $command->transitionTime(2);
        
        // Ensure property is set properly
        $this->assertAttributeEquals(20, 'transitionTime', $command);
        
        // Ensure self object is returned
        $this->assertEquals($command, $command->transitionTime(1));
    }

    /**
     * Test: Setting invalid transition time
     *
     * @covers \Phue\Command\CreateScene::transitionTime
     */
    public function testExceptionOnInvalidTransitionTime(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new CreateScene('phue-test', 'Scene test', array(
            1,
            2
        ));
        $command->transitionTime(- 1);
    }

    /**
     * Test: Send command
     *
     * @covers \Phue\Command\CreateScene::__construct
     * @covers \Phue\Command\CreateScene::send
     */
    public function testSend(): void
    {
        $command = new CreateScene('phue-test', 'Scene test', array(
            2,
            3
        ));
        $command->transitionTime(5);
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
            $this->equalTo(
                "/api/{$this->mockClient->getUsername()}/scenes/phue-test"), 
            $this->equalTo(TransportInterface::METHOD_PUT), 
            $this->equalTo(
                (object) [
                    'name' => 'Scene test',
                    'lights' => [
                        2,
                        3
                    ],
                    'transitiontime' => 50
                ]));
        
        // Send command
        $command->send($this->mockClient);
    }
}
