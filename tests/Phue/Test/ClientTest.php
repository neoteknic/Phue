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
use Phue\Client;
use Phue\Command\CommandInterface;
use Phue\Transport\TransportInterface;
use Phue\Transport\Http;
use Phue\Rule;
use Phue\Sensor;
use Phue\Scene;
use Phue\Schedule;
use Phue\Group;
use Phue\Light;
use Phue\User;
use Phue\Bridge;

/**
 * Tests for Phue\Client
 */
class ClientTest extends TestCase
{
    private Client $client;

    /**
     * @covers \Phue\Client::__construct
     */
    public function setUp(): void
    {
        $this->client = new Client('127.0.0.1');
    }

    /**
     * Test: Get host
     *
     * @covers \Phue\Client::getHost
     * @covers \Phue\Client::setHost
     */
    public function testGetHost(): void
    {
        $this->client->setHost('127.0.0.2');
        
        $this->assertEquals('127.0.0.2', $this->client->getHost());
    }

    /**
     * Test: Setting non-hashed username
     *
     * @covers \Phue\Client::getUsername
     * @covers \Phue\Client::setUsername
     */
    public function testGetSetUsername(): void
    {
        $this->client->setUsername('dummy');
        
        $this->assertEquals('dummy', $this->client->getUsername());
    }

    /**
     * Test: Get bridge
     *
     * @covers \Phue\Client::getBridge
     */
    public function testGetBridge(): void
    {
        // Mock transport
        $mockTransport = $this->createMock(TransportInterface::class);
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->willReturn(new \stdClass());
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Ensure return type is Bridge
        $this->assertInstanceOf(Bridge::class, $this->client->getBridge());
    }

    /**
     * Test: Get users
     *
     * @covers \Phue\Client::getUsers
     */
    public function testGetUsers(): void
    {
        // Mock transport
        $mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock results for sendRequest
        $mockResults = (object) array(
            'whitelist' => array(
                'someusername' => new \stdClass(),
                'anotherusername' => new \stdClass(),
                'thirdusername' => new \stdClass()
            )
        );
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->willReturn($mockResults);
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get users
        $users = $this->client->getUsers();
        
        // Ensure at least three users
        $this->assertCount(3, $users);
        
        // Ensure return type is an array of users
        $this->assertContainsOnlyInstancesOf(User::class, $users);
    }

    /**
     * Test: Get lights
     *
     * @covers \Phue\Client::getLights
     */
    public function testGetLights(): void
    {
        // Mock transport
        $mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock results for sendRequest
        // $mockResults = (object) [
        // '1' => new \stdClass,
        // '2' => new \stdClass,
        // ];
        $mockResults = (object) [
            '1' => new \stdClass(),
            '2' => new \stdClass()
        ];
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->willReturn($mockResults);
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get lights
        $lights = $this->client->getLights();
        
        // Ensure two lights
        $this->assertCount(2, $lights);
        
        // Ensure return type is an array of lights
        $this->assertContainsOnlyInstancesOf(Light::class, $lights);
    }

    /**
     * Test: Get groups
     *
     * @covers \Phue\Client::getGroups
     */
    public function testGetGroups(): void
    {
        // Mock transport
        $mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock results for sendRequest
        // $mockResults = (object) [
        // '1' => new \stdClass,
        // '2' => new \stdClass,
        // ];
        $mockResults = (object) array(
            '1' => new \stdClass(),
            '2' => new \stdClass()
        );
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->willReturn($mockResults);
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get groups
        $groups = $this->client->getGroups();
        
        // Ensure two groups
        $this->assertCount(2, $groups);
        
        // Ensure return type is an array of groups
        $this->assertContainsOnlyInstancesOf(Group::class, $groups);
    }

    /**
     * Test: Get schedules
     *
     * @covers \Phue\Client::getSchedules
     */
    public function testGetSchedules(): void
    {
        // Mock transport
        $mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock results for sendRequest
        $mockResults = (object) [
            '1' => new \stdClass(),
            '2' => new \stdClass(),
            '3' => new \stdClass()
        ];
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->willReturn($mockResults);
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get schedules
        $schedules = $this->client->getSchedules();
        
        // Ensure three schedules
        $this->assertCount(3, $schedules);
        
        // Ensure return type is an array of schedules
        $this->assertContainsOnlyInstancesOf(Schedule::class, $schedules);
    }

    /**
     * Test: Get scenes
     *
     * @covers \Phue\Client::getScenes
     */
    public function testGetScenes(): void
    {
        // Mock transport
        $mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock results for sendRequest
        $mockResults = (object) [
            '1' => new \stdClass(),
            '2' => new \stdClass(),
            '3' => new \stdClass()
        ];
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->willReturn($mockResults);
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get scenes
        $scenes = $this->client->getScenes();
        
        // Ensure three scenes
        $this->assertCount(3, $scenes);
        
        // Ensure return type is an array of scenes
        $this->assertContainsOnlyInstancesOf(Scene::class, $scenes);
    }

    /**
     * Test: Get sensors
     *
     * @covers \Phue\Client::getSensors
     */
    public function testGetSensors(): void
    {
        // Mock transport
        $mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock results for sendRequest
        $mockResults = (object) array(
            '1' => new \stdClass(),
            '2' => new \stdClass()
        );
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->willReturn($mockResults);
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get sensors
        $sensors = $this->client->getSensors();
        
        // Ensure two sensors
        $this->assertCount(2, $sensors);
        
        // Ensure return type is an array of sensors
        $this->assertContainsOnlyInstancesOf(Sensor::class, $sensors);
    }

    /**
     * Test: Get rules
     *
     * @covers \Phue\Client::getRules
     */
    public function testGetRules(): void
    {
        // Mock transport
        $mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock results for sendRequest
        $mockResults = (object) [
            '1' => new \stdClass(),
            '2' => new \stdClass()
        ];
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->willReturn($mockResults);
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get rules
        $rules = $this->client->getRules();
        
        // Ensure two rules
        $this->assertCount(2, $rules);
        
        // Ensure return type is an array of rules
        $this->assertContainsOnlyInstancesOf(Rule::class, $rules);
    }

    /**
     * Test: Get timezones
     *
     * @covers \Phue\Client::getTimezones
     */
    public function testGetTimezones(): void
    {
        // Mock transport
        $mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock results for sendRequestBypassBodyValidation
        $mockResults = array();
        
        // Stub transports sendRequestBypassBodyValidation method
        $mockTransport->expects($this->once())
            ->method('sendRequestBypassBodyValidation')
            ->willReturn($mockResults);
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get timezones
        $timezones = $this->client->getTimezones();
        
        // Ensure we get an array
        $this->assertSame($mockResults, $timezones);
    }

    /**
     * Test: Not passing in Transport dependency will yield default
     *
     * @covers \Phue\Client::getTransport
     * @covers \Phue\Client::setTransport
     */
    public function testInstantiateDefaultTransport(): void
    {
        $this->assertInstanceOf(
            Http::class,
            $this->client->getTransport()
        );
    }

    /**
     * Test: Passing custom Transport to client
     *
     * @covers \Phue\Client::getTransport
     * @covers \Phue\Client::setTransport
     */
    public function testPassingTransportDependency(): void
    {
        // Mock transport
        $mockTransport = $this->createMock(TransportInterface::class);
        
        $this->client->setTransport($mockTransport);
        
        $this->assertEquals($mockTransport, $this->client->getTransport());
    }

    /**
     * Test: Sending a command
     *
     * @covers \Phue\Client::sendCommand
     */
    public function testSendCommand(): void
    {
        // Mock command
        $mockCommand = $this->createMock(CommandInterface::class);
        
        // Stub command's send method
        $mockCommand->expects($this->once())
            ->method('send')
            ->with($this->equalTo($this->client))
            ->willReturn('sample response');
        
        $this->assertEquals(
            'sample response',
            $this->client->sendCommand($mockCommand)
        );
    }
}
