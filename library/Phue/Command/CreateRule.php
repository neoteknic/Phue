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
use Phue\Condition;
use Phue\Transport\TransportInterface;

/**
 * Create rule command
 */
class CreateRule implements CommandInterface
{
    protected string $name;

    protected array $conditions = [];

    protected array $actions = [];

    public function __construct(string $name = '')
    {
        $this->name($name);
    }

    public function name(string $name): static
    {
        $this->name = $name;
        
        return $this;
    }

    public function addCondition(Condition $condition): static
    {
        $this->conditions[] = $condition;
        
        return $this;
    }

    public function addAction(ActionableInterface $command): static
    {
        $this->actions[] = $command;
        
        return $this;
    }

    public function send(Client $client): ?int
    {
        $response = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/rules",
            TransportInterface::METHOD_POST,
            (object) array(
                'name' => $this->name,
                'conditions' => array_map(
                    function ($condition) {
                        return $condition->export();
                    },
                    $this->conditions
                ),
                'actions' => array_map(
                    function ($action) use ($client) {
                        return $action->getActionableParams($client);
                    },
                    $this->actions
                )
            )
        );
        
        return $response->id;
    }
}
