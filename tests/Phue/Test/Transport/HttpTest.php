<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Transport;

use PHPUnit\Framework\TestCase;
use Phue\Transport\Exception\ConnectionException;
use Phue\Transport\Http;

/**
 * Tests for Phue\Transport\Http
 */
class HttpTest extends TestCase
{
    private $mockClient;
    private $mockAdapter;
    private Http $transport;

    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock('\Phue\Client');
        
        // Mock transport adapter
        $this->mockAdapter = $this->createMock(
            '\Phue\Transport\Adapter\AdapterInterface');
        
        // Set transport
        $this->transport = new Http($this->mockClient);
    }

    /**
     * Test: Client property
     *
     * @covers Http::__construct
     */
    public function testClientProperty()
    {
        // Ensure property is set properly
        #this->assertAttributeEquals($this->mockClient, 'client', $this->transport);
    }

    /**
     * Test: Get default adapter
     *
     * @covers Http::getAdapter
     */
    public function testGetDefaultAdapter()
    {
        $this->assertInstanceOf('\Phue\Transport\Adapter\AdapterInterface', 
            $this->transport->getAdapter());
    }

    /**
     * Test: Custom adapter
     *
     * @covers Http::getAdapter
     * @covers Http::setAdapter
     */
    public function testCustomAdapter()
    {
        $this->transport->setAdapter($this->mockAdapter);
        
        $this->assertEquals($this->mockAdapter, $this->transport->getAdapter());
    }

    /**
     * Test: Send request with bad status code
     *
     * @covers Http::sendRequest
     */
    public function testSendRequestBadStatusCode()
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
     *
     *
     */
    public function testSendRequestBadContentType()
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
    public function testSendRequestErrorResponse()
    {
        $this->expectException(\Phue\Transport\Exception\UnauthorizedUserException::class);
        // Mock response
        $mockResponse = array(
            'error' => array(
                'type' => 1,
                'description' => 'Some kind of error'
            )
        );
        
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
    public function testSendRequestArray()
    {
        // Mock response
        // $mockResponse = [
        // 'value 1', 'value 2'
        // ];
        $mockResponse = array(
            'value 1',
            'value 2'
        );
        
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
    public function testSendRequestSuccess()
    {
        // Mock response
        $mockResponse = array(
            'success' => '123'
        );
        
        // Stub adapter methods
        $this->stubMockAdapterResponseMethods($mockResponse, 200, 'application/json');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->assertEquals($mockResponse['success'], 
            $this->transport->sendRequest('dummy', 'GET'));
    }

    /**
     * Test: Throw exception by type
     *
     * @dataProvider providerErrorTypes
     *
     * @covers Http::getExceptionByType
     */
    public function testThrowExceptionByType($type, $exceptionName)
    {
        $this->assertInstanceOf($exceptionName, 
            $this->transport->getExceptionByType($type, null));
    }

    /**
     * Provider: Error types
     *
     * @return array
     */
    public function providerErrorTypes(): array
    {
        $errorTypes = array(
            array(
                - 1,
                'Phue\Transport\Exception\BridgeException'
            )
        );
        
        foreach (Http::$exceptionMap as $errorId => $errorClass) {
            $errorTypes[] = array(
                $errorId,
                $errorClass
            );
        }
        
        return $errorTypes;
    }

    /**
     * Stub adapter response methods
     *
     * @param array $response Response body
     */
    protected function stubMockAdapterResponseMethods(array $response, int $httpStatusCode, string $contentType)
    {
        // Stub send method on transport adapter
        $this->mockAdapter->expects($this->once())
            ->method('send')
            ->will($this->returnValue(json_encode($response)));
        
        // Stub getHttpStatusCode on transport adapter
        $this->mockAdapter->expects($this->once())
            ->method('getHttpStatusCode')
            ->will($this->returnValue($httpStatusCode));
        
        // Stub getContentType on transport adapter
        $this->mockAdapter->expects($this->once())
            ->method('getContentType')
            ->will($this->returnValue($contentType));
    }
}
