<?php

namespace App\Classes;


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

        $colors = array_map(function ($color) {
            return ucfirst(trim($color));
        }, array_column($this->data, 'color'));

        $colors = array_unique($colors) ?? [];
        $colors = array_filter($colors)
        
        $sizes = array_map(function ($size) {
            return strtoupper(trim($size));
        }, array_column($this->data, 'size'));

        $sizes = array_unique($sizes) ?? [];
        $sizes = array_filter($sizes);
        
        if (count($colors) > 0) {
            $result['colors'] = array_values($colors);
        }

        if (count($sizes) > 0) {
            $result['sizes'] = array_values($sizes);
        }

        return $result;
    }
}
