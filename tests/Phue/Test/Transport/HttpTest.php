<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Transport;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Phue\Client;
use Phue\Test\AssertHelpersTrait;
use Phue\Transport\Adapter\AdapterInterface;
use Phue\Transport\Exception\ConnectionException;
use Phue\Transport\Exception\UnauthorizedUserException;
use Phue\Transport\Http;
use Phue\Transport\Exception\BridgeException;

/**
 * Tests for Phue\Transport\Http
 */
#[CoversClass(Http::class)]
#[CoversFunction('getExceptionByType')]
class HttpTest extends TestCase
{
    use AssertHelpersTrait;

    /** @var MockObject&Client $mockClient */
    private $mockClient;

    /** @var MockObject&AdapterInterface $mockAdapter */
    private $mockAdapter;

    private Http $transport;

    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Mock transport adapter
        $this->mockAdapter = $this->createMock(AdapterInterface::class);
        
        // Set transport
        $this->transport = new Http($this->mockClient);
    }

    /**
     * Test: Client property
     *
     * @covers Http::__construct
     */
    public function testClientProperty(): void
    {
        // Ensure property is set properly
        $this->assertAttributeEquals($this->mockClient, 'client', $this->transport);
    }

    /**
     * Test: Get default adapter
     *
     * @covers Http::getAdapter
     */
    public function testGetDefaultAdapter(): void
    {
        $this->assertInstanceOf(AdapterInterface::class, $this->transport->getAdapter());
    }

    /**
     * Test: Custom adapter
     *
     * @covers Http::getAdapter
     * @covers Http::setAdapter
     */
    public function testCustomAdapter(): void
    {
        $this->transport->setAdapter($this->mockAdapter);
        
        $this->assertEquals($this->mockAdapter, $this->transport->getAdapter());
    }

    /**
     * Test: Send request with bad status code
     *
     * @covers Http::sendRequest
     */
    public function testSendRequestBadStatusCode(): void
    {
        $this->expectException(ConnectionException::class);

        // Stub adapter methods
        $this->stubMockAdapterResponseMethods([], 500, 'application/json');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->transport->sendRequest('dummy', 'GET');
    }

    /**
     * Test: Send request with bad content type
     *
     * @covers Http::sendRequest
     * @covers Http::getJsonResponse
     */
    public function testSendRequestBadContentType(): void
    {
        $this->expectException(ConnectionException::class);
        // Stub adapter methods
        $this->stubMockAdapterResponseMethods([], 200, 'unknown');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->transport->sendRequest('dummy', 'GET');
    }

    /**
     * Test: Send request with error response
     *
     * @covers Http::sendRequest
     * @covers Http::getJsonResponse
     */
    public function testSendRequestErrorResponse(): void
    {
        $this->expectException(UnauthorizedUserException::class);
        // Mock response
        $mockResponse = [
            'error' => [
                'type' => 1,
                'description' => 'Some kind of error'
            ]
        ];
        
        // Stub adapter methods
        $this->stubMockAdapterResponseMethods($mockResponse, 200, 'application/json');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->transport->sendRequest('dummy', 'GET');
    }

    /**
     * Test: Send request with array response
     *
     * @covers Http::sendRequest
     * @covers Http::getJsonResponse
     * @throws ConnectionException
     */
    public function testSendRequestArray(): void
    {
        // Mock response
        // $mockResponse = [
        // 'value 1', 'value 2'
        // ];
        $mockResponse = [
            'value 1',
            'value 2'
        ];
        
        // Stub adapter methods
        $this->stubMockAdapterResponseMethods($mockResponse, 200, 'application/json');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->assertEquals($mockResponse[0], $this->transport->sendRequest('dummy', 'GET'));
    }

    /**
     * Test: Send request with success resposne
     *
     * @covers Http::sendRequest
     * @covers Http::getJsonResponse
     */
    public function testSendRequestSuccess(): void
    {
        // Mock response
        $mockResponse = ['success' => '123'];
        
        // Stub adapter methods
        $this->stubMockAdapterResponseMethods($mockResponse, 200, 'application/json');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->assertEquals(
            $mockResponse['success'],
            $this->transport->sendRequest('dummy', 'GET')
        );
    }

    /**
     * Test: Throw exception by type
     */
    #[DataProvider('providerErrorTypes')]
    public function testThrowExceptionByType($type, $exceptionName): void
    {
        $this->assertInstanceOf(
            $exceptionName,
            $this->transport->getExceptionByType($type, null)
        );
    }

    /**
     * Provider: Error types
     *
     * @return array
     */
    public static function providerErrorTypes(): array
    {
        $errorTypes = [
            [
                - 1,
                BridgeException::class
            ]
        ];
        
        foreach (Http::$exceptionMap as $errorId => $errorClass) {
            $errorTypes[] = [
                $errorId,
                $errorClass
            ];
        }
        
        return $errorTypes;
    }

    /**
     * Stub adapter response methods
     *
     * @param array $response Response body
     */
    protected function stubMockAdapterResponseMethods(array $response, int $httpStatusCode, string $contentType): void
    {
        // Stub send method on transport adapter
        $this->mockAdapter->expects($this->once())
            ->method('send')
            ->willReturn(json_encode($response, JSON_THROW_ON_ERROR));
        
        // Stub getHttpStatusCode on transport adapter
        $this->mockAdapter->expects($this->once())
            ->method('getHttpStatusCode')
            ->willReturn($httpStatusCode);
        
        // Stub getContentType on transport adapter
        $this->mockAdapter->expects($this->once())
            ->method('getContentType')
            ->willReturn($contentType);
    }
}
