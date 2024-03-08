<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Command;

use PHPUnit\Framework\MockObject\MockObject;
use Phue\Command\ActionableInterface;
use Phue\Command\CreateSchedule;
use Phue\Schedule;
use Phue\Transport\TransportInterface;
use ReflectionObject;
use Phue\TimePattern\TimePatternInterface;

/**
 * Tests for Phue\Command\CreateSchedule
 */
class CreateScheduleTest extends AbstractCommandTest
{
    /**
     * @var MockObject&ActionableInterface
     */
    private $mockCommand;

    public function setUp(): void
    {
        // Ensure proper timezone
        date_default_timezone_set('UTC');
        
        parent::setUp();
        
        // Mock actionable command
        $this->mockCommand = $this->createMock(ActionableInterface::class);

        // Stub command's getActionableParams method
        $this->mockCommand->expects($this->any())
            ->method('getActionableParams')
            ->willReturn(
                array(
                    'address' => '/thing/value',
                    'method' => 'POST',
                    'body' => 'Dummy'
                )
            );
    }

    /**
     * Test: Set name
     *
     * @covers \Phue\Command\CreateSchedule::name
     */
    public function testName(): void
    {
        $x = new CreateSchedule();
        $command = $x->name('Dummy!');
        
        // Ensure property is set properly
        $r = new ReflectionObject($command);
        $p = $r->getProperty('attributes');
        $p->setAccessible(true);

        $this->assertSame('Dummy!', $p->getValue($command)["name"]);

        // Ensure self object is returned
        $this->assertEquals($command, $command->name('Dummy!'));
    }

    /**
     * Test: Set description
     *
     * @covers \Phue\Command\CreateSchedule::description
     */
    public function testDescription(): void
    {
        $x = new CreateSchedule();
        $command = $x->description('Description!');
        
        // Ensure property is set properly
        $r = new ReflectionObject($command);
        $p = $r->getProperty('attributes');
        $p->setAccessible(true);

        $this->assertSame('Description!', $p->getValue($command)["description"]);
        
        // Ensure self object is returned
        $this->assertEquals($command, $command->name('Description!'));
    }

    /**
     * Test: Set time
     *
     * @covers \Phue\Command\CreateSchedule::time
     */
    public function testTime(): void
    {
        $x = new CreateSchedule();
        $command = $x->time('2010-10-20T10:11:12');
        
        // Ensure property is set properly
        $this->assertInstanceOf(TimePatternInterface::class, $command->getTime());
        
        // Ensure self object is returned
        $this->assertEquals($command, $command->time('+10 seconds'));
    }

    /**
     * Test: Set command
     *
     * @covers \Phue\Command\CreateSchedule::command
     */
    public function testCommand(): void
    {
        $x = new CreateSchedule();
        $command = $x->command($this->mockCommand);
        
        // Ensure properties are set properly
        $this->assertAttributeEquals($this->mockCommand, 'command', $command);
        
        // Ensure self object is returned
        $this->assertEquals($command, $command->command($this->mockCommand));
    }

    /**
     * Test: Set status
     *
     * @covers \Phue\Command\CreateSchedule::status
     */
    public function testStatus(): void
    {
        $x = new CreateSchedule();
        $command = $x->status(Schedule::STATUS_ENABLED);
        
        // Ensure property is set properly
        $r = new ReflectionObject($command);
        $p = $r->getProperty('attributes');
        $p->setAccessible(true);

        $this->assertSame(Schedule::STATUS_ENABLED, $p->getValue($command)["status"]);
        
        // Ensure self object is returned
        $this->assertEquals($command, $command->status(Schedule::STATUS_ENABLED));
    }

    /**
     * Test: Auto delete
     *
     * @covers \Phue\Command\CreateSchedule::autodelete
     */
    public function testAutoDelete(): void
    {
        $x = new CreateSchedule();
        $command = $x->autodelete(true);

        // Ensure property is set properly
        $r = new ReflectionObject($command);
        $p = $r->getProperty('attributes');
        $p->setAccessible(true);

        $this->assertTrue($p->getValue($command)["autodelete"]);
        
        // Ensure self object is returned
        $this->assertEquals($command, $command->autodelete(true));
    }

    /**
     * Test: Send command
     *
     * @covers \Phue\Command\CreateSchedule::__construct
     * @covers \Phue\Command\CreateSchedule::send
     * @throws \Exception
     */
    public function testSend(): void
    {
        $command = new CreateSchedule(
            'Dummy!',
            '2012-12-30T10:11:12',
            $this->mockCommand
        );
        $command->description('Description!');
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo("/api/{$this->mockClient->getUsername()}/schedules"),
                $this->equalTo(TransportInterface::METHOD_POST),
                $this->equalTo(
                    (object) array(
                        'name' => 'Dummy!',
                        'description' => 'Description!',
                        'time' => '2012-12-30T10:11:12',
                        'command' => array(
                            'method' => TransportInterface::METHOD_POST,
                            'address' => "/api/{$this->mockClient->getUsername()}/thing/value",
                            'body' => "Dummy"
                        )
                    )
                )
            )
            ->willReturn(4);
        
        // Send command and get response
        $scheduleId = $command->send($this->mockClient);
        
        // Ensure we have a schedule id
        $this->assertEquals(4, $scheduleId);
    }
}
