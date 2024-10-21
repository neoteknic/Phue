<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

class Condition
{
    const OPERATOR_EQUALS = 'eq';

    const OPERATOR_GREATER_THAN = 'gt';

    const OPERATOR_LESS_THAN = 'lt';

    const OPERATOR_CHANGED = 'dx';

    protected ?string $sensorId = null;

    /**
     * Attribute to target condition
     */
    protected ?string $attribute = null;

    protected ?string $operator = null;

    protected ?string $value = null;

    public function __construct(\stdClass $condition = null)
    {
        $condition !== null && $this->import($condition);
    }

    public function getSensorId(): ?string
    {
        return $this->sensorId;
    }

    public function setSensorId(mixed $sensorId): static
    {
        $this->sensorId = (string) $sensorId;
        
        return $this;
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setAttribute(string $attribute): static
    {
        $this->attribute = $attribute;
        
        return $this;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function setOperator(string $operator): static
    {
        $this->operator = $operator;
        
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;
        
        return $this;
    }

    /**
     * Import from API response
     *
     * @param \stdClass $condition Condition values
     */
    public function import(\stdClass $condition): static
    {
        $x = explode('/', $condition->address);
        $this->setSensorId($x[2]);
        $y = explode('/', $condition->address);
        $this->setAttribute($y[4]);
        $this->setOperator((string) $condition->operator);
        isset($condition->value) && $this->setValue((string) $condition->value);
        
        return $this;
    }

    /**
     * Export for API request
     *
     * @return \stdClass Result object
     */
    public function export(): \stdClass
    {
        $result = [
            'address' => "/sensors/{$this->getSensorId()}/state/{$this->getAttribute()}",
            'operator' => $this->getOperator()
        ];
        
        if ($this->value !== null) {
            $result['value'] = $this->getValue();
        }
        
        return (object) $result;
    }

    public function equals(): static
    {
        $this->operator = self::OPERATOR_EQUALS;
        
        return $this;
    }

    public function greaterThan(): static
    {
        $this->operator = self::OPERATOR_GREATER_THAN;
        
        return $this;
    }

    public function lessThan(): static
    {
        $this->operator = self::OPERATOR_LESS_THAN;
        
        return $this;
    }

    public function changed(): static
    {
        $this->operator = self::OPERATOR_CHANGED;
        
        return $this;
    }
}
