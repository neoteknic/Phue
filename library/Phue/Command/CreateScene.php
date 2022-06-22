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
use Phue\Transport\TransportInterface;

/**
 * Create scene command
 */
class CreateScene implements CommandInterface
{

    /**
     * Name
     *
     * @var string
     */
    protected string $name;

    /**
     * Lights
     *
     * @var array List of light Ids
     */
    protected array $lights = [];

    /**
     * Transition time
     *
     * @var mixed
     */
    protected $transitionTime = null;

    /**
     * Constructs a command
     *
     * @param string $id
     *            Id
     * @param string $name
     *            Name
     * @param array $lights
     *            List of light Ids or Light objects
     */
    public function __construct($id, $name, array $lights = [])
    {
        $this->id($id);
        $this->name($name);
        $this->lights($lights);
    }

    /**
     * Set id
     *
     * @param string $id
     *            Custom scene id
     *
     * @return self This object
     */
    public function id(string $id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *            Name
     *
     * @return self This object
     */
    public function name(string $name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Set lights
     *
     * @param array $lights
     *            List of light Ids or Light objects
     *
     * @return self This object
     */
    public function lights(array $lights = [])
    {
        $this->lights = [];
        // Iterate through each light and append id to scene list
        foreach ($lights as $light) {
            $this->lights[] = (string) $light;
        }
        
        return $this;
    }

    /**
     * Set transition time
     *
     * @param double $seconds
     *            Time in seconds
     *
     * @return self This object
     */
    public function transitionTime(float|int $seconds)
    {
        // Don't continue if seconds is not valid
        if ($seconds < 0) {
            throw new \InvalidArgumentException('Time must be at least 0');
        }
        
        // Value is in 1/10 seconds
        $this->transitionTime = (int) ($seconds * 10);
        
        return $this;
    }

    /**
     * Send command
     *
     * @param Client $client
     *            Phue Client
     *
     * @return string Scene Id
     */
    public function send(Client $client):string
    {
        $body = (object) array(
            'name' => $this->name,
            'lights' => $this->lights
        );
        
        if ($this->transitionTime !== null) {
            $body->transitiontime = $this->transitionTime;
        }
        
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/scenes/{$this->id}",
            TransportInterface::METHOD_PUT,
            $body
        );
        
        return $this->id;
    }
}
