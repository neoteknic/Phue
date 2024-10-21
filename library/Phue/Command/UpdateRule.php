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
 * Update rule command
 */
class UpdateRule extends CreateRule
{
    protected string $ruleId;

    /**
     * Rule attributes
     */
    protected array $attributes = array();

    /**
     * Constructs a command
     *
     * @param mixed $rule Rule Id or Rule object
     */
    public function __construct(mixed $rule)
    {
        $this->ruleId = (string) $rule;
    }

    public function name(string $name): static
    {
        $this->attributes['name'] = $name;
        
        return $this;
    }

    /**
     * Send command
     */
    public function send(Client $client): ?int
    {
        $attributes = $this->attributes;
        
        foreach ($this->conditions as $condition) {
            $attributes['conditions'][] = $condition->export();
        }
        
        foreach ($this->actions as $action) {
            $attributes['actions'][] = $action->getActionableParams($client);
        }
        
        return $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/rules/{$this->ruleId}",
            TransportInterface::METHOD_PUT,
            (object) $attributes
        );
    }
}
