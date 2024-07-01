<?php

use PHPUnit\Framework\TestCase;
use App\Classes\SimpleCache;

class SimpleCacheTest extends TestCase
{

    public function testSetCache()
    {
        $cache = new SimpleCache();
        
        $this->assertTrue($cache->set('test', 'value', 1));
    }


    public function testGetCache()
    {
        $cache = new SimpleCache();
        $cache->set('test', 'value', 2);

        $this->assertEquals('value', $cache->get('test'));
    }


    public function testCacheExpiration()
    {
        $cache = new SimpleCache();
        $cache->set('test', 'value', 1);
        sleep(2);

        $this->assertNull($cache->get('test'));
    }


    public function testCacheForNonExistentKey()
    {
        $cache = new SimpleCache();

        $this->assertNull($cache->get('some_key'));
    }
}
