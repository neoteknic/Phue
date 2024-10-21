<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Transport;

use Phue\Client;
use Phue\Transport\Exception\ConnectionException;
use Phue\Transport\Adapter\AdapterInterface;
use Phue\Transport\Adapter\Curl as DefaultAdapter;
use Phue\Transport\Exception\InternalErrorException;
use Phue\Transport\Exception\ScheduleTimeInPastException;
use Phue\Transport\Exception\InvalidScheduleTagException;
use Phue\Transport\Exception\ScheduleTimeUpdateException;
use Phue\Transport\Exception\InvalidScheduleTimeZoneException;
use Phue\Transport\Exception\ScheduleListFullException;
use Phue\Transport\Exception\RuleActivationException;
use Phue\Transport\Exception\RuleActionException;
use Phue\Transport\Exception\RuleConditionException;
use Phue\Transport\Exception\RuleListFullException;
use Phue\Transport\Exception\SensorListFullException;
use Phue\Transport\Exception\SensorCreationProhibitedException;
use Phue\Transport\Exception\SceneBufferFullException;
use Phue\Transport\Exception\SceneCreationInProgressException;
use Phue\Transport\Exception\GroupUnmodifiableException;
use Phue\Transport\Exception\DeviceUnreachableException;
use Phue\Transport\Exception\LightGroupTableFullException;
use Phue\Transport\Exception\GroupTableFullException;
use Phue\Transport\Exception\DeviceParameterUnmodifiableException;
use Phue\Transport\Exception\InvalidUpdateStateException;
use Phue\Transport\Exception\DisablingDhcpProhibitedException;
use Phue\Transport\Exception\LinkButtonException;
use Phue\Transport\Exception\PortalConnectionRequiredException;
use Phue\Transport\Exception\TooManyItemsInListException;
use Phue\Transport\Exception\ParameterUnmodifiableException;
use Phue\Transport\Exception\InvalidValueException;
use Phue\Transport\Exception\ParameterUnavailableException;
use Phue\Transport\Exception\MissingParameterException;
use Phue\Transport\Exception\MethodUnavailableException;
use Phue\Transport\Exception\ResourceUnavailableException;
use Phue\Transport\Exception\InvalidJsonBodyException;
use Phue\Transport\Exception\UnauthorizedUserException;
use Phue\Transport\Exception\BridgeException;

/**
 * Http transport
 */
class Http implements TransportInterface
{
    protected ?AdapterInterface $adapter;

    public static array $exceptionMap = [
        0 => BridgeException::class,
        1 => UnauthorizedUserException::class,
        2 => InvalidJsonBodyException::class,
        3 => ResourceUnavailableException::class,
        4 => MethodUnavailableException::class,
        5 => MissingParameterException::class,
        6 => ParameterUnavailableException::class,
        7 => InvalidValueException::class,
        8 => ParameterUnmodifiableException::class,
        11 => TooManyItemsInListException::class,
        12 => PortalConnectionRequiredException::class,
        101 => LinkButtonException::class,
        110 => DisablingDhcpProhibitedException::class,
        111 => InvalidUpdateStateException::class,
        201 => DeviceParameterUnmodifiableException::class,
        301 => GroupTableFullException::class,
        302 => LightGroupTableFullException::class,
        304 => DeviceUnreachableException::class,
        305 => GroupUnmodifiableException::class,
        401 => SceneCreationInProgressException::class,
        402 => SceneBufferFullException::class,
        501 => SensorCreationProhibitedException::class,
        502 => SensorListFullException::class,
        601 => RuleListFullException::class,
        607 => RuleConditionException::class,
        608 => RuleActionException::class,
        609 => RuleActivationException::class,
        701 => ScheduleListFullException::class,
        702 => InvalidScheduleTimeZoneException::class,
        703 => ScheduleTimeUpdateException::class,
        704 => InvalidScheduleTagException::class,
        705 => ScheduleTimeInPastException::class,
        901 => InternalErrorException::class
    ];

    /**
     * Construct Http transport
     */
    public function __construct(protected Client $client)
    {
        $this->adapter = null;
    }

    /**
     * Get adapter for transport
     *
     * Auto created adapter if one is not present
     */
    public function getAdapter(): AdapterInterface
    {
        if (! $this->adapter) {
            $this->setAdapter(new DefaultAdapter());
        }
        
        return $this->adapter;
    }

    public function setAdapter(AdapterInterface $adapter): static
    {
        $this->adapter = $adapter;
        
        return $this;
    }

    /**
     * Get exception by type
     *
     * @param string|null $type Error type
     * @param string|null $description Description of error
     *
     * TODO add phpstan annotations
     * @return \Exception Built exception
     */
    public function getExceptionByType(?string $type, ?string $description): \Exception
    {
        // Determine exception
        $exceptionClass = static::$exceptionMap[$type] ?? static::$exceptionMap[0];
        
        return new $exceptionClass($description, $type);
    }

    /**
     * @inheritdoc
     * @throws \Exception
     * @throws ConnectionException
     */
    public function sendRequest(string $address, string $method = self::METHOD_GET, \stdClass $body = null): mixed
    {
        $jsonResults = $this->getJsonResponse($address, $method, $body);
        
        // Get first element in array if it's an array response
        if (is_array($jsonResults)) {
            $jsonResults = $jsonResults[0];
        }
        
        // Get error type
        if (isset($jsonResults->error)) {
            throw $this->getExceptionByType(
                $jsonResults->error->type,
                $jsonResults->error->description
            );
        }
        
        // Get success object only if available
        if (isset($jsonResults->success)) {
            $jsonResults = $jsonResults->success;
        }
        
        return $jsonResults;
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception
     * @throws ConnectionException
     */
    public function sendRequestBypassBodyValidation(
        string    $address,
        string    $method = self::METHOD_GET,
        \stdClass $body = null
    ): \stdClass|array {
    
        return $this->getJsonResponse($address, $method, $body);
    }

    /**
     * Send request
     *
     * @param string $address API address
     * @param string $method Request method
     * @param \stdClass|null $body Post body
     *
     * @return \stdClass|array Json body
     * @throws ConnectionException
     */
    protected function getJsonResponse(string $address, string $method = self::METHOD_GET, \stdClass $body = null): \stdClass|array
    {
        // Build request url
        $url = "http://{$this->client->getHost()}{$address}";
        
        // Open connection
        $this->getAdapter()->open();
        
        // Send and get response
        $results = $this->getAdapter()->send(
            $url,
            $method,
            $body ? json_encode($body) : null
        );
        $status = $this->getAdapter()->getHttpStatusCode();
        $contentType = $this->getAdapter()->getContentType();

        // Throw connection exception if status code isn't 200 or wrong content type
        if ($status != 200 || explode(';', $contentType)[0] != 'application/json') {
            throw new ConnectionException('Connection failure');
        }
        
        // Parse json results
        return json_decode($results);
    }
}
