<?php

namespace App\Classes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;

class Printful
{
    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * Returns an array of unique colors and sizes from an array of catalog variants.
     * @return array array of colors and sizes.
     */
    public function getColorsAndSizes(): array
    {
        if (empty($this->data)) {
            return [];
        }

        $result = [];

        $colors = array_unique(array_column($this->data, 'color')) ?? [];
        $sizes = array_unique(array_column($this->data, 'size')) ?? [];

        if (count($colors) > 0) {
            $result['colors'] = $colors;
        }

        if (count($sizes) > 0) {
            $result['sizes'] = $sizes;
        }

        return $result;
    }
}
