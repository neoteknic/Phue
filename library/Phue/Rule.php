<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

use Phue\Command\DeleteRule;

class Rule
{
    public const STATUS_ENABLED = 'enabled';

    public const STATUS_DISABLED = 'disabled';

    public function __construct(protected int $id, protected \stdClass $attributes, protected Client $client)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->attributes->name;
    }

    public function getLastTriggeredTime(): string
    {
        return $this->attributes->lasttriggered;
    }

    public function getCreateDate(): string
    {
        return $this->attributes->created;
    }

    public function getTriggeredCount(): int
    {
        return $this->attributes->timestriggered;
    }

    public function getOwner(): string
    {
        return $this->attributes->owner;
    }

    public function isEnabled(): bool
    {
        return $this->attributes->status == self::STATUS_ENABLED;
    }

    /**
     * @return Condition[]
     */
    public function getConditions(): array
    {
        $conditions = array();
        
        foreach ($this->attributes->conditions as $condition) {
            $conditions[] = new Condition($condition);
        }
        
        return $conditions;
    }

    /**
     * TODO type of return values?
     * @return array List of actions
     */
    public function getActions(): array
    {
        $actions = array();
        
        foreach ($this->attributes->actions as $action) {
            $actions[] = $action;
        }
        
        return $actions;
    }

    public function delete(): void
    {
        $this->client->sendCommand((new DeleteRule($this)));
    }

    /**
     * @return string Rule Id
     */
    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
