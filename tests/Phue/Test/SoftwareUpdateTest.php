<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Phue\Client;
use Phue\SoftwareUpdate;
use Phue\Command\SetBridgeConfig;

/**
 * Tests for Phue\SoftwareUpdate
 */
class SoftwareUpdateTest extends TestCase
{
    /** @var MockObject&Client $mockClient */
    private $mockClient;
    private object $attributes;
    private SoftwareUpdate $softwareUpdate;

    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Build stub attributes
        $this->attributes = (object) [
            'updatestate' => 2,
            'checkforupdate' => false,
            'devicetypes' => (object) [
                'bridge' => true,
                'lights' => [
                    '1',
                    '2',
                    '3'
                ]
            ],
            'url' => '',
            'text' => '010000000',
            'notify' => false
        ];
        
        // Create software update object
        $this->softwareUpdate = new SoftwareUpdate(
            $this->attributes,
            $this->mockClient
        );
    }

    /**
     * Test: Get update state
     *
     */
    public function testGetUpdateState(): void
    {
        $this->assertEquals(
            $this->attributes->updatestate,
            $this->softwareUpdate->getUpdateState()
        );
    }

    /**
     * Test: Installing updates
     *
     */
    public function testInstallUpdates(): void
    {
        // Expect client's sendCommand usage
        $this->mockClient->expects($this->once())
            ->method('sendCommand')
            ->with($this->isInstanceOf(SetBridgeConfig::class));
        
        // Ensure installUpdates returns self
        $this->assertEquals(
            $this->softwareUpdate,
            $this->softwareUpdate->installUpdates()
        );
        
        // Ensure new value can be retrieved by getUpdateState
        $this->assertEquals(3, $this->softwareUpdate->getUpdateState());
    }

    /**
     * Test: Checking for update?
     *
     */
    public function testCheckingForUpdate(): void
    {
        $this->assertEquals(
            $this->attributes->checkforupdate,
            $this->softwareUpdate->checkingForUpdate()
        );
    }

    /**
     * Test: Check for update
     *
     */
    public function testCheckForUpdate(): void
    {
        // Expect client's sendCommand usage
        $this->mockClient->expects($this->once())
            ->method('sendCommand')
            ->with($this->isInstanceOf(SetBridgeConfig::class));
        
        // Ensure checkForUpdate returns self
        $this->assertEquals(
            $this->softwareUpdate,
            $this->softwareUpdate->checkForUpdate()
        );
        
        // Ensure new value can be retrieved by checkingForUpdate
        $this->assertTrue($this->softwareUpdate->checkingForUpdate());
    }

    /**
     * Test: Is bridge updatable?
     *
     */
    public function testIsBridgeUpdatable(): void
    {
        $this->assertEquals(
            $this->attributes->devicetypes->bridge,
            $this->softwareUpdate->isBridgeUpdatable()
        );
    }

    /**
     * Test: Get updatable lights
     *
     */
    public function testGetUpdatableLights(): void
    {
        $this->assertEquals(
            $this->attributes->devicetypes->lights,
            $this->softwareUpdate->getUpdatableLights()
        );
    }

    /**
     * Test: Get release notes URL
     *
     */
    public function testGetReleaseNotesUrl(): void
    {
        $this->assertEquals(
            $this->attributes->url,
            $this->softwareUpdate->getReleaseNotesUrl()
        );
    }

    /**
     * Test: Get release notes brief
     *
     */
    public function testGetReleaseNotesBrief(): void
    {
        $this->assertEquals(
            $this->attributes->text,
            $this->softwareUpdate->getReleaseNotesBrief()
        );
    }

    /**
     * Test: Is install notification enabled?
     *
     */
    public function testIsInstallNotificationEnabled(): void
    {
        $this->assertEquals(
            $this->attributes->notify,
            $this->softwareUpdate->isInstallNotificationEnabled()
        );
    }

    /**
     * Test: Disable install notification
     *
     */
    public function testDisableInstallNotification(): void
    {
        // Expect client's sendCommand usage
        $this->mockClient->expects($this->once())
            ->method('sendCommand')
            ->with($this->isInstanceOf(SetBridgeConfig::class));
        
        // Ensure disableInstallNotification returns self
        $this->assertEquals(
            $this->softwareUpdate,
            $this->softwareUpdate->disableInstallNotification()
        );
        
        // Ensure new value can be retrieved by isInstallNotificationEnabled
        $this->assertFalse(
            $this->softwareUpdate->isInstallNotificationEnabled()
        );
    }
}

