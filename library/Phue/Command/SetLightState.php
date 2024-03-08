<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Command;

use Phue\Client;
use Phue\Helper\ColorConversion;
use Phue\Transport\TransportInterface;

/**
 * Set light alert command
 */
class SetLightState implements CommandInterface, ActionableInterface
{
    const BRIGHTNESS_MIN = 0;

    const BRIGHTNESS_MAX = 255;

    const HUE_MIN = 0;

    const HUE_MAX = 65535;

    const SATURATION_MIN = 0;

    const SATURATION_MAX = 255;

    const XY_MIN = 0.0;

    const XY_MAX = 1.0;

    const RGB_MIN = 0;

    const RGB_MAX = 255;

    const COLOR_TEMP_MIN = 153;

    const COLOR_TEMP_MAX = 500;

    const ALERT_NONE = 'none';

    const ALERT_SELECT = 'select';

    const ALERT_LONG_SELECT = 'lselect';

    const EFFECT_NONE = 'none';

    const EFFECT_COLORLOOP = 'colorloop';

    protected int $lightId;

    /**
     * State parameters
     */
    protected array $params = [];

    /**
     * @return array List of alert modes
     */
    public static function getAlertModes(): array
    {
        return [
            self::ALERT_NONE,
            self::ALERT_SELECT,
            self::ALERT_LONG_SELECT
        ];
    }

    public static function getEffectModes(): array
    {
        return [
            self::EFFECT_NONE,
            self::EFFECT_COLORLOOP
        ];
    }

    /**
     * @param mixed $light Light Id or Light object
     */
    public function __construct(mixed $light)
    {
        $this->lightId = (int) (string) $light;
    }

    /**
     * Set on parameter
     */
    public function on(bool $flag = true): static
    {
        $this->params['on'] = $flag;
        
        return $this;
    }

    /**
     * Set brightness
     *
     *@throws \InvalidArgumentException
     *
     */
    public function brightness(int $level = self::BRIGHTNESS_MAX): static
    {
        // Don't continue if brightness level is invalid
        if (! (self::BRIGHTNESS_MIN <= $level && $level <= self::BRIGHTNESS_MAX)) {
            throw new \InvalidArgumentException(
                "Brightness must be between " . self::BRIGHTNESS_MIN . " and " .
                self::BRIGHTNESS_MAX
            );
        }
        
        $this->params['bri'] = $level;
        
        return $this;
    }

    /**
     * Set hue
     *
     * @param int $value
     *            Hue value
     *
     * @throws \InvalidArgumentException
     *
     */
    public function hue(int $value): static
    {
        // Don't continue if hue value is invalid
        if (! (self::HUE_MIN <= $value && $value <= self::HUE_MAX)) {
            throw new \InvalidArgumentException(
                "Hue value must be between " . self::HUE_MIN . " and " . self::HUE_MAX
            );
        }
        
        $this->params['hue'] = $value;
        
        return $this;
    }

    /**
     * Set saturation
     *@throws \InvalidArgumentException
     *
     */
    public function saturation(int $value): static
    {
        // Don't continue if saturation value is invalid
        if (! (self::SATURATION_MIN <= $value && $value <= self::SATURATION_MAX)) {
            throw new \InvalidArgumentException(
                "Saturation value must be between " . self::SATURATION_MIN . " and " .
                self::SATURATION_MAX
            );
        }
        
        $this->params['sat'] = $value;
        
        return $this;
    }

    /**
     * Set xy
     *
     * @throws \InvalidArgumentException
     */
    public function xy(float $x, float $y): static
    {
        // Don't continue if x or y values are invalid
        foreach (array(
            $x,
            $y
        ) as $value) {
            if (! (self::XY_MIN <= $value && $value <= self::XY_MAX)) {
                throw new \InvalidArgumentException(
                    "x/y value must be between " . self::XY_MIN . " and " .
                    self::XY_MAX
                );
            }
        }
        
        $this->params['xy'] = array(
            $x,
            $y
        );
        
        return $this;
    }

    /**
     * Sets xy and brightness calculated from RGB
     * @throws \InvalidArgumentException
     */
    public function rgb(int $red, int $green, int $blue, ?int $bri=null): SetLightState
    {
        // Don't continue if rgb values are invalid
        foreach (array(
            $red,
            $green,
            $blue
        ) as $value) {
            if (! (self::RGB_MIN <= $value && $value <= self::RGB_MAX)) {
                throw new \InvalidArgumentException(
                    "RGB values must be between " . self::RGB_MIN . " and " .
                    self::RGB_MAX
                );
            }
        }

        $xy = ColorConversion::convertRGBToXY($red, $green, $blue);
        if($bri!==null) {
            if($bri<0) {
                $bri=0;
            } elseif($bri>255) {
                $bri=255;
            }
        } else {
            $bri = $xy['bri'];
        }
        return $this->xy($xy['x'], $xy['y'])->brightness($bri);
    }

    /**
     * Set color temperature
     * @throws \InvalidArgumentException
     */
    public function colorTemp(int $value): static
    {
        // Don't continue if color temperature is invalid
        if (! (self::COLOR_TEMP_MIN <= $value && $value <= self::COLOR_TEMP_MAX)) {
            throw new \InvalidArgumentException(
                "Color temperature value must be between " . self::COLOR_TEMP_MIN .
                " and " . self::COLOR_TEMP_MAX
            );
        }
        
        $this->params['ct'] = $value;
        
        return $this;
    }

    /**
     * Set alert parameter
     * @throws \InvalidArgumentException
     *
     */
    public function alert(string $mode = self::ALERT_LONG_SELECT): static
    {
        // Don't continue if mode is not valid
        if (! in_array($mode, self::getAlertModes())) {
            throw new \InvalidArgumentException("{$mode} is not a valid alert mode");
        }
        
        $this->params['alert'] = $mode;
        
        return $this;
    }

    /**
     * Set effect mode
     *
     * @throws \InvalidArgumentException
     */
    public function effect(string $mode = self::EFFECT_COLORLOOP): static
    {
        // Don't continue if mode is not valid
        if (! in_array($mode, self::getEffectModes())) {
            throw new \InvalidArgumentException("{$mode} is not a valid effect modes");
        }
        
        $this->params['effect'] = $mode;
        
        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function transitionTime(float $seconds): static
    {
        // Don't continue if seconds is not valid
        if ($seconds < 0) {
            throw new \InvalidArgumentException("Time must be at least 0");
        }
        
        // Value is in 1/10 seconds, so convert automatically
        $this->params['transitiontime'] = (int) ($seconds * 10);
        
        return $this;
    }

    /**
     * Send command
     */
    public function send(Client $client): void
    {
        // Get params
        $params = $this->getActionableParams($client);
        
        // Send request
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}" . $params['address'],
            $params['method'],
            $params['body']
        );
    }

    /**
     * @return array <string, mixed>
     */
    public function getActionableParams(Client $client): array
    {
        return [
            'address' => "/lights/{$this->lightId}/state",
            'method' => TransportInterface::METHOD_PUT,
            'body' => (object) $this->params
        ];
    }
}
