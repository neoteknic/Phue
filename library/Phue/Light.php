<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

namespace Phue;

use Phue\Command\SetLightState;
use Phue\Helper\ColorConversion;
use Phue\LightModel\AbstractLightModel;
use Phue\LightModel\LightModelFactory;

class Light implements LightInterface
{

    protected float|null $transition;

    public function __construct(protected int $id, protected \stdClass $attributes, protected Client $client)
    {
        $this->transition = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->attributes->name;
    }

    public function setName(string $name): static
    {
        $this->client->sendCommand(new Command\SetLightName($this, $name));

        $this->attributes->name = $name;

        return $this;
    }

    public function getType(): string
    {
        return $this->attributes->type;
    }

    public function getModelId(): string
    {
        return $this->attributes->modelid;
    }

    public function getModel(): AbstractLightModel
    {
        return LightModelFactory::build($this->getModelId());
    }

    public function getUniqueId(): string
    {
        return $this->attributes->uniqueid;
    }

    public function getSoftwareVersion(): string
    {
        return $this->attributes->swversion;
    }

    public function isOn(): bool
    {
        return (bool) $this->attributes->state->on;
    }

    public function setOn(bool $flag = true): static
    {
        $x = new SetLightState($this);
        $y = $x->on($flag);
        $this->updateTransition($x);
        $this->client->sendCommand($y);

        $this->attributes->state->on = $flag;

        return $this;
    }

    public function getAlert(): ?string
    {
        return ($this->attributes->state->alert ?? null);
    }

    public function setAlert(string $mode = SetLightState::ALERT_LONG_SELECT): static
    {
        $x = new SetLightState($this);
        $y = $x->alert($mode);
        $this->updateTransition($x);
        $this->client->sendCommand($y);

        $this->attributes->state->alert = $mode;

        return $this;
    }

    public function getEffect(): ?string
    {
        return ($this->attributes->state->effect ?? null);
    }

    public function setEffect(string $mode = SetLightState::EFFECT_NONE): static
    {
        $x = new SetLightState($this);
        $y = $x->effect($mode);
        $this->updateTransition($x);
        $this->client->sendCommand($y);

        $this->attributes->state->effect = $mode;

        return $this;
    }

    public function getBrightness(): ?int
    {
        return ($this->attributes->state->bri ?? null);
    }

    public function setBrightness(int $level = SetLightState::BRIGHTNESS_MAX): static
    {
        $x = new SetLightState($this);
        $y = $x->brightness($level);
        $this->updateTransition($x);
        $this->client->sendCommand($y);

        $this->attributes->state->bri = $level;

        return $this;
    }

    public function getHue(): ?int
    {
        return ($this->attributes->state->hue ?? null);
    }

    public function setHue(int $value): static
    {
        $x = new SetLightState($this);
        $y = $x->hue($value);
        $this->updateTransition($x);
        $this->client->sendCommand($y);

        // Change both hue and color mode state
        $this->attributes->state->hue = $value;
        $this->attributes->state->colormode = 'hs';

        return $this;
    }

    public function getSaturation(): ?int
    {
        return ($this->attributes->state->sat ?? null);
    }

    public function setSaturation(int $value): static
    {
        $x = new SetLightState($this);
        $y = $x->saturation($value);
        $this->updateTransition($x);
        $this->client->sendCommand($y);

        // Change both saturation and color mode state
        $this->attributes->state->sat = $value;
        $this->attributes->state->colormode = 'hs';

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getXY(): array
    {
        return [
            'x' => (isset($this->attributes->state->xy) ? $this->attributes->state->xy[0] : 1),
            'y' => (isset($this->attributes->state->xy) ? $this->attributes->state->xy[1] : 1),
        ];
    }

    public function setXY(float $x, float $y): static
    {
        $_x = new SetLightState($this);
        $_y = $_x->xy($x, $y);
        $this->updateTransition($_x);
        $this->client->sendCommand($_y);

        // Change both internal xy and colormode state
        $this->attributes->state->xy = [
            $x,
            $y,
        ];
        $this->attributes->state->colormode = 'xy';

        return $this;
    }

    public function getRGB(): array
    {
        $xy = $this->getXY();
        $bri = $this->getBrightness();

        return ColorConversion::convertXYToRGB($xy['x'], $xy['y'], $bri);
    }

    /**
     * Set XY and brightness calculated from RGB
     */
    public function setRGB(int $red, int $green, int $blue, int $bri=null): static
    {
        $x = new SetLightState($this);
        $y = $x->rgb($red, $green, $blue, $bri);
        $this->updateTransition($x);
        $this->client->sendCommand($y);

        // Change internal xy, brightness and colormode state
        $xy = ColorConversion::convertRGBToXY($red, $green, $blue);
        $this->attributes->state->xy = [
            $xy['x'],
            $xy['y']
        ];
        if($bri!==null) {
            if($bri<0) {
                $bri=0;
            } elseif($bri>255) {
                $bri=255;
            }
            $this->attributes->state->bri = $bri;
        } else {
            $this->attributes->state->bri = max($red, $green, $blue);
        }
        $this->attributes->state->colormode = 'xy';

        return $this;
    }

    /**
    * @param $time float Seconds
    */
    public function setTransition(float $time): void
    {
        $this->transition = $time;
    }

    private function updateTransition(SetLightState $x): void
    {
        if($this->transition !== null) {
            $x->transitionTime($this->transition);
        }
    }

    public function getColorTemp(): ?int
    {
        return ($this->attributes->state->ct ?? null);
    }

    public function setColorTemp(int $value): static
    {
        $x = new SetLightState($this);
        $y = $x->colorTemp($value);
        $this->client->sendCommand($y);

        // Change both internal color temp and colormode state
        $this->attributes->state->ct = $value;
        $this->attributes->state->colormode = 'ct';

        return $this;
    }

    public function getColorMode(): ?string
    {
        return property_exists($this->attributes->state, 'colormode')
            ? $this->attributes->state->colormode
            : null;
    }

    public function isReachable(): bool
    {
        return $this->attributes->state->reachable;
    }

    /**
     * @return string Light Id
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}
