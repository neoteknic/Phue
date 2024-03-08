<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

use Phue\Command\SetBridgeConfig;

class SoftwareUpdate
{
    const STATE_NO_UPDATE = 0;

    const STATE_DOWNLOADING = 1;

    const STATE_READY_TO_INSTALL = 2;

    const STATE_INSTALLING = 3;

    public function __construct(protected \stdClass $attributes, protected Client $client)
    {
    }

    public function getUpdateState(): int
    {
        return $this->attributes->updatestate;
    }

    public function installUpdates(): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'swupdate' => [
                        'updatestate' => self::STATE_INSTALLING
                    ]
                ]
            )
        );
        
        $this->attributes->updatestate = self::STATE_INSTALLING;
        
        return $this;
    }

    public function checkingForUpdate(): bool
    {
        return $this->attributes->checkforupdate;
    }

    public function checkForUpdate(): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'swupdate' => [
                        'checkforupdate' => true
                    ]
                ]
            )
        );
        
        $this->attributes->checkforupdate = true;
        
        return $this;
    }

    public function isBridgeUpdatable(): bool
    {
        return (bool) $this->attributes->devicetypes->bridge;
    }

    /**
     * @return array List of updatable light ids
     */
    public function getUpdatableLights(): array
    {
        return (array) $this->attributes->devicetypes->lights;
    }

    public function getReleaseNotesUrl(): string
    {
        return $this->attributes->url;
    }

    public function getReleaseNotesBrief(): string
    {
        return $this->attributes->text;
    }

    public function isInstallNotificationEnabled(): bool
    {
        return (bool) $this->attributes->notify;
    }

    public function disableInstallNotification(): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'swupdate' => [
                        'notify' => false
                    ]
                ]
            )
        );
        
        $this->attributes->notify = false;

        return $this;
    }
}
