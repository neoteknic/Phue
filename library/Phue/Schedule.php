<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

use Phue\Command\ActionableInterface;
use Phue\Command\SetScheduleAttributes;

class Schedule
{
    const STATUS_ENABLED = 'enabled';

    const STATUS_DISABLED = 'disabled';

    public function __construct(protected int $id, protected \stdClass $attributes, protected Client $client)
    {}

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
        $x = new SetScheduleAttributes($this);
        $y = $x->name($name);
        $this->client->sendCommand($y);
        
        $this->attributes->name = $name;
        
        return $this;
    }

    public function getDescription(): string
    {
        return $this->attributes->description;
    }

    public function setDescription(string $description): static
    {
        $x = new SetScheduleAttributes($this);
        $y = $x->description($description);
        $this->client->sendCommand($y);
        
        $this->attributes->description = $description;
        
        return $this;
    }

    /**
     * @return array Command attributes
     */
    public function getCommand(): array
    {
        return (array) $this->attributes->command;
    }

    public function setCommand(ActionableInterface $command): static
    {
        $x = new SetScheduleAttributes($this);
        $y = $x->command($command);
        $this->client->sendCommand($y);
        
        $this->attributes->command = $command->getActionableParams($this->client);
        
        return $this;
    }

    public function getStatus(): string
    {
        return $this->attributes->status;
    }

    public function setStatus(string $status): static
    {
        $x = new SetScheduleAttributes($this);
        $y = $x->status($status);
        $this->client->sendCommand($y);
        
        $this->attributes->status = $status;
        
        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->attributes->status == self::STATUS_ENABLED;
    }

    public function getTime(): string
    {
        return $this->attributes->time;
    }

    public function setTime(string $time): static
    {
        // $this->client->sendCommand(
        // (new SetScheduleAttributes($this))
        // ->time($time)
        // );
        $x = new SetScheduleAttributes($this);
        $y = $x->time($time);
        $this->client->sendCommand($y);
        
        $this->attributes->time = $time;
        
        return $this;
    }

    public function isAutoDeleted(): bool
    {
        return (bool) $this->attributes->autodelete;
    }

    /**
     * @param bool $flag True to auto delete, false if not
     */
    public function setAutoDelete(bool $flag): static
    {
        $x = new SetScheduleAttributes($this);
        $y = $x->autodelete($flag);
        $this->client->sendCommand($y);
        
        $this->attributes->autodelete = $flag;
        
        return $this;
    }

    /**
     * Delete schedule
     */
    public function delete(): void
    {
        $this->client->sendCommand((new Command\DeleteSchedule($this)));
    }

    /**
     * @return string Schedule Id
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}
