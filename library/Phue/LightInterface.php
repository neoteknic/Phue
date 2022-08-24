<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Karim Geiger <geiger@karim.email>
 * @copyright Copyright (c) 2016 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

use Phue\Command\SetLightState;

/**
 * Interface for lights and groups.
 */
interface LightInterface
{
    public function getId(): int;

    public function getName(): string;

    public function setName(string $name): LightInterface;

    public function getType(): string;

    public function isOn(): bool;

    public function setOn(bool $flag = true): LightInterface;

    public function getAlert(): ?string;

    public function setAlert(string $mode = SetLightState::ALERT_LONG_SELECT): LightInterface;

    public function getEffect(): ?string;

    public function setEffect(string $mode = SetLightState::EFFECT_NONE): LightInterface;

    public function getBrightness(): ?int;

    public function setBrightness(int $level = SetLightState::BRIGHTNESS_MAX): LightInterface;

    public function getHue(): ?int;

    public function setHue(int $value): LightInterface;

    public function getSaturation(): ?int;

    public function setSaturation(int $value): LightInterface;

    /**
     * Get XY
     *
     * @return array X, Y key/value
     */
    public function getXY(): array;

    public function setXY(float $x, float $y): LightInterface;

    public function getColorTemp(): ?int;

    public function setColorTemp(int $value): LightInterface;

    public function getColorMode(): ?string;
}
