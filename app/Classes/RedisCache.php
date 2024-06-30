<?php

namespace App\Classes;

use App\Interfaces\CacheInterface;
use Redis;
use RedisException;

Class RedisCache implements CacheInterface 
{
    private readonly Redis $redis;


    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
    }


    public function set(string $key, $value, int $duration): bool
    {
        try {

            $value = serialize($value);
            return $this->redis->setex($key, $duration, $value);

        } catch (RedisException $e) {
            echo "Redis error: " . $e->getMessage();
            return false;
        }
    }


    public function get(string $key): mixed
    {
        try {
            
            $value = $this->redis->get($key);

            if ($value === false) {
                return null;
            }
            
            return unserialize($value);
            
        } catch (RedisException $e) {
            echo "Redis error: " . $e->getMessage();
            return null;
        }
    }    
}