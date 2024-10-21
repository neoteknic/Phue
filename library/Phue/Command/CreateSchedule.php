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
use Phue\TimePattern\AbsoluteTime;
use Phue\TimePattern\TimePatternInterface;
use Phue\Transport\TransportInterface;

/**
 * Create schedule command
 */
class CreateSchedule implements CommandInterface
{
    /**
     * Schedule attributes
     */
    protected array $attributes = [];

    protected ?ActionableInterface $command;

    protected ?TimePatternInterface $time;

    /**
     * @throws \Exception
     */
    public function __construct(
        ?string             $name = null,
        mixed               $time = null,
        ActionableInterface $command = null
    ) {
        // Set name, time, command if passed
        $name !== null && $this->name($name);
        $time !== null && $this->time($time);
        $command !== null && $this->command($command);
    }

    public function name(string $name): static
    {
        $this->attributes['name'] = $name;
        
        return $this;
    }

    public function description(string $description): static
    {
        $this->attributes['description'] = $description;
        
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function time(string|TimePatternInterface $time): static
    {
        if (! ($time instanceof TimePatternInterface)) {
            $time = new AbsoluteTime($time);
        }
        
        $this->time = $time;
        
        return $this;
    }

    public function command(ActionableInterface $command): static
    {
        $this->command = $command;
        
        return $this;
    }

    public function status(string $status): static
    {
        $this->attributes['status'] = $status;
        
        return $this;
    }

    public function autodelete(bool $flag): static
    {
        $this->attributes['autodelete'] = $flag;
        
        return $this;
    }

    public function getTime(): TimePatternInterface
    {
        return $this->time;
    }

    /**
     * Send command
     */
    public function send(Client $client): ?int
    {
        // Set command attribute if passed
        if ($this->command) {
            $params = $this->command->getActionableParams($client);
            $params['address'] = "/api/{$client->getUsername()}" . $params['address'];
            
            $this->attributes['command'] = $params;
        }
        
        // Set time attribute if passed
        if ($this->time) {
            $this->attributes['time'] = (string) $this->time;
        }
        
        // Create schedule
        $scheduleId = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/schedules",
            TransportInterface::METHOD_POST,
            (object) $this->attributes
        );
        
        return $scheduleId;
    }
}
