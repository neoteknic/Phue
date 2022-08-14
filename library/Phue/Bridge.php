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

class Bridge {

    /**
     * @param \stdClass $attributes
     * @param Client $client Phue client
     */
    public function __construct(protected \stdClass $attributes, protected Client $client) {
    }

    public function getName(): string
    {
        return $this->attributes->name;
    }

    public function setName(string $name): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'name' => $name
                ]
            )
        );

        $this->attributes->name = $name;

        return $this;
    }

    public function getZigBeeChannel(): int
    {
        return $this->attributes->zigbeechannel;
    }

    public function setZigBeeChannel(int $channel): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                ['zigbeechannel' => $channel]
            )
        );

        $this->attributes->zigbeechannel = $channel;

        return $this;
    }

    public function getMacAddress(): string
    {
        return $this->attributes->mac;
    }

    public function isDhcpEnabled(): bool
    {
        return (bool) $this->attributes->dhcp;
    }

    public function enableDhcp(bool $state = TRUE): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'dhcp' => $state,
                ]
            )
        );

        $this->attributes->dhcp = $state;

        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->attributes->ipaddress;
    }

    public function setIpAddress(string $ipAddress): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'ipaddress' => $ipAddress,
                ]
            )
        );

        $this->attributes->ipaddress = $ipAddress;

        return $this;
    }

    public function getNetmask(): string
    {
        return $this->attributes->netmask;
    }

    public function setNetmask(string $netmask): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'netmask' => $netmask,
                ]
            )
        );

        $this->attributes->netmask = $netmask;

        return $this;
    }

    public function getGateway(): string
    {
        return $this->attributes->gateway;
    }

    public function setGateway(string $gateway): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'gateway' => $gateway,
                ]
            )
        );

        $this->attributes->gateway = $gateway;

        return $this;
    }

    public function getProxyAddress(): string
    {
        return $this->attributes->proxyaddress;
    }

    public function setProxyAddress(
        string $proxyAddress = SetBridgeConfig::DEFAULT_PROXY_ADDRESS
    ): static
    {

        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'proxyaddress' => $proxyAddress,
                ]
            )
        );

        $this->attributes->proxyaddress = $proxyAddress;

        return $this;
    }

    public function getProxyPort(): string
    {
        return $this->attributes->proxyport;
    }

    public function setProxyPort(int $proxyPort = SetBridgeConfig::DEFAULT_PROXY_PORT): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'proxyport' => $proxyPort,
                ]
            )
        );

        $this->attributes->proxyport = $proxyPort;

        return $this;
    }

    public function getUtcTime(): string
    {
        return $this->attributes->UTC;
    }

    public function getLocalTime(): string
    {
        return $this->attributes->localtime;
    }

    public function getTimezone(): string
    {
        return $this->attributes->timezone;
    }

    public function setTimezone(string $timezone): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'timezone' => $timezone,
                ]
            )
        );

        $this->attributes->timezone = $timezone;

        return $this;
    }

    public function getSoftwareVersion(): string
    {
        return $this->attributes->swversion;
    }

    public function getApiVersion(): string
    {
        return $this->attributes->apiversion;
    }

    public function getSoftwareUpdate(): SoftwareUpdate
    {
        return new SoftwareUpdate($this->attributes->swupdate, $this->client);
    }

    public function isLinkButtonOn(): bool
    {
        return (bool) $this->attributes->linkbutton;
    }

    public function setLinkButtonOn(bool $state = TRUE): static
    {
        $this->client->sendCommand(
            new SetBridgeConfig(
                [
                    'linkbutton' => $state,
                ]
            )
        );

        $this->attributes->linkbutton = $state;

        return $this;
    }

    public function arePortalServicesEnabled(): bool
    {
        return (bool) $this->attributes->portalservices;
    }

    public function isPortalConnected(): bool
    {
        return $this->attributes->portalconnection == 'connected';
    }

    public function getPortal(): Portal
    {
        return new Portal($this->attributes->portalstate, $this->client);
    }
}
