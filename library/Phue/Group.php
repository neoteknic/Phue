<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

use Phue\Command\DeleteGroup;
use Phue\Command\SetLightState;
use Phue\Command\SetGroupAttributes;
use Phue\Command\SetGroupState;
use Phue\Helper\ColorConversion;

class Group implements LightInterface
{
    protected float|null $transition;

    public function __construct(protected int $id, protected \stdClass $attributes, protected Client $client)
    {
        $this->transition = null;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getName(): string
    {
        return $this->attributes->name;
    }

    public function getType(): string
    {
        return $this->attributes->type;
    }

    public function setName(string $name): static
    {
        $x = new SetGroupAttributes($this);
        $y = $x->name($name);
        $this->client->sendCommand($y);

        $this->attributes->name = $name;

        return $this;
    }

    /**
     * @return array List of light ids
     */
    public function getLightIds(): array
    {
        return $this->attributes->lights;
    }

    /**
     * @param array $lights Light ids or Light objects
     */
    public function setLights(array $lights): static
    {
        $lightIds = array();

        foreach ($lights as $light) {
            $lightIds[] = (string) $light;
        }

        $x = new SetGroupAttributes($this);
        $y = $x->lights($lightIds);
        $this->client->sendCommand($y);

        $this->attributes->lights = $lightIds;

        return $this;
    }

    public function isOn(): bool
    {
        return (bool) $this->attributes->action->on;
    }

    public function setOn(bool $flag = true): static
    {
        $x = new SetGroupState($this);
        $y = $x->on($flag);
        $this->updateTransition($x);
        $this->client->sendCommand($y);

        $this->attributes->action->on = $flag;

        return $this;
    }

    public function getAlert(): ?string
    {
        return $this->attributes->action->alert;
    }

    public function setAlert(string $mode = SetLightState::ALERT_LONG_SELECT): static
    {
        $x = new SetGroupState($this);
        $this->updateTransition($x);
        $y = $x->alert($mode);
        $this->client->sendCommand($y);
        $this->attributes->action->alert = $mode;
        return $this;
    }

    public function getBrightness(): ?int
    {
        # TODO check
        #return $this->attributes->action->bri ?? 255;
        #return $this->attributes->action->bri ?? 0;
        return $this->attributes->action->bri ?? null;
    }

    public function setBrightness(int $level = SetLightState::BRIGHTNESS_MAX): static
    {
        $x = new SetGroupState($this);
        $this->updateTransition($x);
        $y = $x->brightness($level);
        $this->client->sendCommand($y);

        $this->attributes->action->bri = $level;

        return $this;
    }

    public function getHue(): ?int
    {
        return $this->attributes->action->hue ?? null;
    }

    public function setHue(int $value): static
    {
        $x = new SetGroupState($this);
        $this->updateTransition($x);
        $y = $x->hue($value);
        $this->client->sendCommand($y);

        // Change both hue and color mode state
        $this->attributes->action->hue = $value;
        $this->attributes->action->colormode = 'hs';

        return $this;
    }

    public function getSaturation(): ?int
    {
        return $this->attributes->action->sat ?? null;
    }

    public function setSaturation(int $value): static
    {
        $x = new SetGroupState($this);
        $this->updateTransition($x);
        $y = $x->saturation($value);
        $this->client->sendCommand($y);

        // Change both saturation and color mode state
        $this->attributes->action->sat = $value;
        $this->attributes->action->colormode = 'hs';

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getXY(): array
    {
        return [
            'x' => $this->attributes->action->xy[0],
            'y' => $this->attributes->action->xy[1]
        ];
    }

    public function setXY(float $x, float $y): static
    {
        $_x = new SetGroupState($this);
        $this->updateTransition($_x);
        $_y = $_x->xy($x, $y);
        $this->client->sendCommand($_y);

        // Change both internal xy and colormode state
        $this->attributes->action->xy = [
            $x,
            $y
        ];
        $this->attributes->action->colormode = 'xy';

        return $this;
    }

    /**
     * Get calculated RGB
     *
     * @return array red, green, blue key/value
     */
    public function getRGB(): array
    {
        $xy  = $this->getXY();
        $bri = $this->getBrightness();
        return ColorConversion::convertXYToRGB($xy['x'], $xy['y'], $bri);
    }

    /**
     * Set XY and brightness calculated from RGB
     *
     * @param int $red Red value
     * @param int $green Green value
     * @param int $blue Blue value
     *
     * @param int|null $bri Brightness if needed
     */
    public function setRGB(int $red, int $green, int $blue, ?int $bri=null): static
    {
        $x = new SetGroupState($this);
        $this->updateTransition($x);
        $y = $x->rgb($red, $green, $blue, $bri);
        $this->client->sendCommand($y);

        // Change internal xy, brightness and colormode state
        $xy = ColorConversion::convertRGBToXY($red, $green, $blue);
        $this->attributes->action->xy = array(
            $xy['x'],
            $xy['y']
        );
        if($bri===null) {
            $this->attributes->action->bri = $bri;
        } else {
            $this->attributes->action->bri = max($red, $green, $blue);
        }

        $this->attributes->action->colormode = 'xy';

        return $this;
    }

    public function getColorTemp(): ?int
    {
        return $this->attributes->action->ct??null;
    }

    public function setColorTemp(int $value): static
    {
        $x = new SetGroupState($this);
        $y = $x->colorTemp($value);
        $this->client->sendCommand($y);

        // Change both internal color temp and colormode state
        $this->attributes->action->ct = $value;
        $this->attributes->action->colormode = 'ct';

        return $this;
    }

    public function getEffect(): ?string
    {
        return $this->attributes->action->effect??null;
    }

    public function setEffect(string $mode = SetLightState::EFFECT_NONE): static
    {
        $x = new SetGroupState($this);
        $y = $x->effect($mode);
        $this->client->sendCommand($y);

        $this->attributes->action->effect = $mode;

        return $this;
    }

    public function getColorMode(): ?string
    {
        return $this->attributes->action->colormode ?? null;
    }

    /**
     * Set scene on group
     *
     * @param mixed $scene
     *            Scene id or Scene object
     */
    public function setScene(mixed $scene): static
    {
        $x = new SetGroupState($this);
        $y = $x->scene((string) $scene);
        $this->client->sendCommand($y);

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
        if($this->transition!==null) {
            $x->transitionTime($this->transition);
        }
    }

    /**
     * Delete group
     */
    public function delete(): void
    {
        $this->client->sendCommand((new DeleteGroup($this)));
    }

    /**
     * __toString
     *
     * @return string Group Id
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}
