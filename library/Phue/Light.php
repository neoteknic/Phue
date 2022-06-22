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

/**
 * Light object
 */
class Light implements LightInterface {
    /**
     * Id
     *
     * @var int
     */
    protected int $id;

    /**
     * Light attributes
     *
     * @var \stdClass
     */
    protected \stdClass $attributes;

    /**
     * Phue client
     *
     * @var Client
     */
    protected Client $client;

	protected $transition;

    /**
     * Construct a Phue Light object
     *
     * @param int       $id
     *            Id
     * @param \stdClass $attributes
     *            Light attributes
     * @param Client    $client
     *            Phue client
     */
    public function __construct(int $id, \stdClass $attributes, Client $client) {
        $this->id = $id;
        $this->attributes = $attributes;
        $this->client = $client;
	    $this->transition=null;
    }

    /**
     * Get light Id
     *
     * @return int Light id
     */
    public function getId():int {
        return $this->id;
    }

    /**
     * Get assigned name of light
     *
     * @return string Name of light
     */
    public function getName() {
        return $this->attributes->name;
    }

    /**
     * Set name of light
     *
     * @param string $name
     *
     * @return self This object
     */
    public function setName($name):self {
        $this->client->sendCommand(new Command\SetLightName($this, (string) $name));

        $this->attributes->name = (string) $name;

        return $this;
    }

    /**
     * Get type
     *
     * @return string Type
     */
    public function getType():string {
        return $this->attributes->type;
    }

    /**
     * Get model Id
     *
     * @return string Model Id
     */
    public function getModelId():string {
        return $this->attributes->modelid;
    }

    /**
     * Get model
     *
     * @return AbstractLightModel Light model
     */
    public function getModel() {
        return LightModelFactory::build($this->getModelId());
    }

    /**
     * Get unique id
     *
     * @return string Unique Id
     */
    public function getUniqueId() {
        return $this->attributes->uniqueid;
    }

    /**
     * Get software version
     *
     * @return string
     */
    public function getSoftwareVersion() {
        return $this->attributes->swversion;
    }

    /**
     * Is the light on?
     *
     * @return bool True if on, false if not
     */
    public function isOn() {
        return (bool) $this->attributes->state->on;
    }

    /**
     * Set light on/off
     *
     * @param bool $flag
     *            True for on, false for off
     *
     * @return self This object
     */
    public function setOn($flag = TRUE) {
        $x = new SetLightState($this);
        $y = $x->on((bool) $flag);
        $this->updateTransition($x);
        $this->client->sendCommand($y);

        $this->attributes->state->on = (bool) $flag;

        return $this;
    }

    /**
     * Get alert
     *
     * @return string Alert mode
     */
    public function getAlert() {
        return (isset($this->attributes->state->alert) ? $this->attributes->state->alert : null);
    }

    /**
     * Set light alert
     *
     * @param string $mode
     *            Alert mode
     *
     * @return self This object
     */
    public function setAlert($mode = SetLightState::ALERT_LONG_SELECT) {
        $x = new SetLightState($this);
        $y = $x->alert($mode);
	    $this->updateTransition($x);
        $this->client->sendCommand($y);

        $this->attributes->state->alert = $mode;

        return $this;
    }

    /**
     * Get effect mode
     *
     * @return string effect mode
     */
    public function getEffect() {
        return (isset($this->attributes->state->effect) ? $this->attributes->state->effect : null);
    }

    /**
     * Set effect
     *
     * @param string $mode
     *            Effect mode
     *
     * @return self This object
     */
    public function setEffect($mode = SetLightState::EFFECT_NONE) {
        $x = new SetLightState($this);
        $y = $x->effect($mode);
	    $this->updateTransition($x);
        $this->client->sendCommand($y);

        $this->attributes->state->effect = $mode;

        return $this;
    }

    /**
     * Get brightness
     *
     * @return int Brightness level
     */
    public function getBrightness() {
        return (isset($this->attributes->state->bri) ? $this->attributes->state->bri : null);
    }

    /**
     * Set brightness
     *
     * @param int $level
     *            Brightness level
     *
     * @return self This object
     */
    public function setBrightness($level = SetLightState::BRIGHTNESS_MAX) {
        $x = new SetLightState($this);
        $y = $x->brightness((int) $level);
	    $this->updateTransition($x);
        $this->client->sendCommand($y);

        $this->attributes->state->bri = (int) $level;

        return $this;
    }

    /**
     * Get hue
     *
     * @return int Hue value
     */
    public function getHue() {
        return (isset($this->attributes->state->hue) ? $this->attributes->state->hue : null);
    }

    /**
     * Set hue
     *
     * @param int $value
     *            Hue value
     *
     * @return self This object
     */
    public function setHue($value) {
        $x = new SetLightState($this);
        $y = $x->hue((int) $value);
	    $this->updateTransition($x);
        $this->client->sendCommand($y);

        // Change both hue and color mode state
        $this->attributes->state->hue = (int) $value;
        $this->attributes->state->colormode = 'hs';

        return $this;
    }

    /**
     * Get saturation
     *
     * @return int Saturation value
     */
    public function getSaturation() {
        return (isset($this->attributes->state->sat) ? $this->attributes->state->sat : null);
    }

    /**
     * Set saturation
     *
     * @param int $value
     *            Saturation value
     *
     * @return self This object
     */
    public function setSaturation($value) {
        $x = new SetLightState($this);
        $y = $x->saturation((int) $value);
	    $this->updateTransition($x);
        $this->client->sendCommand($y);

        // Change both saturation and color mode state
        $this->attributes->state->sat = (int) $value;
        $this->attributes->state->colormode = 'hs';

        return $this;
    }

    /**
     * Get XY
     *
     * @return array X, Y key/value
     */
    public function getXY() {
        return [
            'x' => (isset($this->attributes->state->xy) ? $this->attributes->state->xy[0] : 1),
            'y' => (isset($this->attributes->state->xy) ? $this->attributes->state->xy[1] : 1),
        ];
    }

    /**
     * Set XY
     *
     * @param float $x
     *            X value
     * @param float $y
     *            Y value
     *
     * @return self This object
     */
    public function setXY($x, $y) {
        $_x = new SetLightState($this);
        $_y = $_x->xy((float) $x, (float) $y);
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

    /**
     * Get calculated RGB
     *
     * @return array red, green, blue key/value
     */
    public function getRGB() {
        $xy = $this->getXY();
        $bri = $this->getBrightness();
	    return ColorConversion::convertXYToRGB($xy['x'], $xy['y'], $bri);
    }

    /**
     * Set XY and brightness calculated from RGB
     *
     * @param int $red   Red value
     * @param int $green Green value
     * @param int $blue  Blue value
     * @param int $blue  Brightness value
     *
     * @return self This object
     */
    public function setRGB($red, $green, $blue,$bri=null)
    {
        $x = new SetLightState($this);
        $y = $x->rgb((int) $red, (int) $green, (int) $blue,$bri);
        $this->updateTransition($x);
        $this->client->sendCommand($y);

        // Change internal xy, brightness and colormode state
        $xy = ColorConversion::convertRGBToXY($red, $green, $blue);
        $this->attributes->state->xy = [
            $xy['x'],
            $xy['y']
        ];
        if($bri!==null){
        	if($bri<0)
        		$bri=0;
        	elseif($bri>255)
		        $bri=255;
	        $this->attributes->state->bri = $bri;
        }
        else{
	        $this->attributes->state->bri = max($red, $green, $blue);
        }
        $this->attributes->state->colormode = 'xy';

        return $this;
    }

    /**
    * @param $time float Seconds
    */
    public function setTransition($time)
    {
    	$this->transition=$time;
    }

    private function updateTransition(SetLightState $x)
    {
    	if($this->transition!==null){
    		$x->transitionTime($this->transition);
    	}
    }

    /**
     * Get Color temperature
     *
     * @return int Color temperature value
     */
    public function getColorTemp() {
        return (isset($this->attributes->state->ct) ? $this->attributes->state->ct : null);
    }

    /**
     * Set Color temperature
     *
     * @param int $value Color temperature value
     *
     * @return self This object
     */
    public function setColorTemp($value) {
        $x = new SetLightState($this);
        $y = $x->colorTemp((int) $value);
        $this->client->sendCommand($y);

        // Change both internal color temp and colormode state
        $this->attributes->state->ct = (int) $value;
        $this->attributes->state->colormode = 'ct';

        return $this;
    }

    /**
     * Get color mode of light
     *
     * @return string Color mode
     */
    public function getColorMode() {
        return property_exists($this->attributes->state, 'colormode')
            ? $this->attributes->state->colormode
            : NULL;
    }

    /**
     * Get whether or not the bulb is reachable.
     *
     * @return bool
     */
    public function isReachable() {
        return $this->attributes->state->reachable;
    }

    /**
     * __toString
     *
     * @return string Light Id
     */
    public function __toString() {
        return (string) $this->getId();
    }
}
