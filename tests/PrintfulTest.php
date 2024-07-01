<?php

use PHPUnit\Framework\TestCase;
use App\Classes\Printful;

class PrintfulTest extends TestCase
{

    public function testGetColorsAndSizesForActualData()
    {

        $data = [
            [
                'id' => 598,
                'catalog_product_id' => 12,
                'name' => 'Gildan 64000 Unisex Softstyle T-Shirt with Tear Away (Black / 2XL)',
                'size' => '2XL',
                'color' => 'Black',
                'color_code' => '#0e0e0e',
                'availability' => [
                    [
                        'region' => 'United States',
                        'status' => 'in_stock'
                    ]
                ],
                '_links' => [
                    'self' => [
                        'href' => 'https://api.printful.com/v2/catalog-variants/598'
                    ],
                    'product_details' => [
                        'href' => 'https://api.printful.com/v2/catalog-products/12'
                    ]
                ]
            ],
            [
                'id' => 629,
                'catalog_product_id' => 12,
                'name' => 'Gildan 64000 Unisex Softstyle T-Shirt with Tear Away (Black / 3XL)',
                'size' => '3XL',
                'color' => 'Red',
                'image' => 'https://files.cdn.printful.com/products/12/629_1653477235.jpg',
                'availability' => [
                    [
                        'region' => 'United States',
                        'status' => 'in_stock'
                    ],
                    [
                        'region' => 'Europe',
                        'status' => 'in_stock'
                    ]
                ]
            ]
        ];

        $printful = new Printful($data);
        $result = $printful->getColorsAndSizes();

        $expected = [
            'colors' => ['Black', 'Red'],
            'sizes' => ['2XL', '3XL'],
        ];

        $this->assertEquals($expected, $result);
    }


    public function testGetColorsAndSizesForEmptyData()
    {
        $printful = new Printful([]);
        $result = $printful->getColorsAndSizes();
        
        $this->assertEmpty($result);
    }
}
