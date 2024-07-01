<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Controller;

class ControllerTest extends TestCase
{

    public function testSuccessfulDataRetrieval()
    {
        $config = require_once realpath('app/Configs/config.php');

        $controller = new Controller($config['api_key']);

        $response = $controller->fetchData();

        $this->assertEquals(200, $response['code']);
        $this->assertArrayHasKey('colors', $response['data']);
        $this->assertArrayHasKey('sizes', $response['data']);
    

    }
}
