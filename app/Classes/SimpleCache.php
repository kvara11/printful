<?php

namespace App\Classes;

use App\Interfaces\CacheInterface;
use Exception;


Class SimpleCache implements CacheInterface 
{
    private string $cacheDir;

    public function __construct()
    {
        $this->cacheDir = $_SERVER['DOCUMENT_ROOT'] . '/cache';

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }


    public function set(string $key, $value, int $duration): bool
    {
        try {
            
            $fileName = $this->cacheDir . '/' . md5($key) . '.cache';

            $data = [
                'expiration' => time() + $duration,
                'data' => $value
            ];
    
            return file_put_contents($fileName, serialize($data));

        } catch (Exception $e) {
            echo "Cache error: " . $e->getMessage();
            return false;
        }
    }


    public function get(string $key): mixed
    {
        try {
            
            $fileName = $this->cacheDir . '/' . md5($key) . '.cache';

            if (!file_exists($fileName)) {
                return null;
            }
    
            $data = unserialize(file_get_contents($fileName));

            if ($data['expiration'] < time()) {
                unlink($fileName);
                return null;
            }
    
            return $data['data'];

        } catch (Exception $e) {
            echo "Cache error: " . $e->getMessage();
            return null;
        }
    }    
}