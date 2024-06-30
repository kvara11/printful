<?php

namespace App\Services;

use App\Classes\PrintfulApi;
use App\Classes\RedisCache;
use App\Traits\ApiResponses;

class ProductService
{
    use ApiResponses;

    private readonly PrintfulApi $printfulApi;
    private readonly RedisCache $cache;


    public function __construct(PrintfulApi $printfulApi, RedisCache $cache)
    {
        $this->printfulApi = $printfulApi;
        $this->cache = $cache;
    }


    public function getProductColorsAndSizes(int $id)
    {
        try {
            
            $products = $this->printfulApi->getProductCatalogById($id);

            if (empty($products) || isset($products['error'])) {
                return $this->error(null, $products['error']['message'], $products['error']['code']);    
            }


            $result = $this->printfulApi->getColorsAndSizes($products);
            
            if (empty($result)) {
                return $this->error(null, "Data not found", 404);
            }

            $this->cache->set($id, $result, 3600);
            return $this->success($result, "Data received successfully", 200);

        } catch (\Exception $e) {

            return $this->error(null, $e->getMessage(), $e->getCode());
        }
    }
}
