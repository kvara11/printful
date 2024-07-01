<?php

namespace Tests\App\Services;

use App\Services\ApiService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ApiServiceTest extends TestCase
{

    public function testFetchProductCatalogById()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['data' => ['red', 'black']])),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $guzzleClient = new Client(['handler' => $handlerStack]);

        $apiService = new ApiService('api_key');

        $reflection = new ReflectionClass($apiService);
        $property = $reflection->getProperty('client');
        
        $property->setAccessible(true);
        $property->setValue($apiService, $guzzleClient);

        $result = $apiService->fetchProductCatalogById(12);

        $this->assertEquals(['red', 'black'], $result);
    }
   
   
    public function testFetchProductCatalogByIdNotFound()
    {
        $mockHandler = new MockHandler([
            new Response(404),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $guzzleClient = new Client(['handler' => $handlerStack]);

        $apiService = new ApiService('api_key', $guzzleClient);

        $reflection = new ReflectionClass($apiService);
        $property = $reflection->getProperty('client');

        $property->setAccessible(true);
        $property->setValue($apiService, $guzzleClient);

        $result = $apiService->fetchProductCatalogById(999);

        $this->assertArrayHasKey('error', $result);
        $this->assertEquals(404, $result['error']['code']);
    }
}
