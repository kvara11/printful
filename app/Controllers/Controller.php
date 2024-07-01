<?php

namespace App\Controllers;

use App\Classes\Printful;
use App\Services\ApiService;
use App\Classes\SimpleCache;
use App\Traits\ApiResponses;

Class Controller
{
    use ApiResponses;

    private $apiService;
    private $cache;

    public function __construct(string $key)
    {
        $this->apiService = new ApiService($key);
        $this->cache = new SimpleCache();
    }

    public function fetchData()
    {
        try {

            $cacheKey = 'catalog_12';
            $data = $this->cache->get($cacheKey);

            if (empty($data)) {

                $data = $this->apiService->fetchProductCatalogById(12);
                $this->cache->set($cacheKey, $data, 300);
            }

            $printful = new Printful($data);
            $result = $printful->getColorsAndSizes();


            if (isset($result['error']) || empty($result)) {
                return $this->error([], $result['error']['message'] ?? 'Empty data', $result['error']['code'] ?? 404);
            }

            return $this->success($result, "Data received successfully", 200);

        } catch (Exception $e) {
            
            return $this->error(null, "Server error: " . $e->getMessage(), 500);
        }
    }

}