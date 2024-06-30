<?php

namespace App\Classes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;

class PrintfulApi
{
    private string $apiKey;
    private string $url = "https://api.printful.com";
    private $client = null;


    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        try {

            $this->client = new Client([

                'base_uri' => $this->url,
                'timeout' => 2.0,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);
        
        } catch (RequestException $e) {
            throw new \Exception('Request Error: ' . $e->getMessage());

        } catch (ClientException $e) {
            throw new \Exception('Client Error: ' . $e->getMessage());
        
        } catch (ServerException $e) {
            throw new \Exception('Server Error: ' . $e->getMessage());
        
        } catch (ConnectException $e) {
            throw new \Exception('Connection Error: ' . $e->getMessage());
        
        } catch (\Exception $e) {
            throw new \Exception('Error: ' . $e->getMessage());
        }
    }


    public function getCatalogByProductId(int $productId) : array
    {
        try {

            if (empty($this->client) || !$this->client instanceof Client) {
                throw new \Exception('Initialize client first');
            }

            $response = $this->client->request('GET', "v2/catalog-products/{$productId}/catalog-variants");

            if ($response->getStatusCode() === 200) {

                $data = json_decode($response->getBody(), true);
                
                return $this->getColorsAndSizes($data['data']) ?? [];
            }

            return [
                
                'error' => [
                    'message' => $response->getBody(),
                    'code' => $response->getStatusCode(),
                ],
            ];

        } catch (RequestException $e) {
            // throw new \Exception('Request Error: ' . $e->getMessage());
            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                ]
            ];
        }
    }

    private function getColorsAndSizes($data)
    {
        $colors = [];
        $sizes  = [];

        foreach ($data as $row) {

            if (isset($row['color']) && !in_array($row['color'], $colors)) {
                $colors[] = $row['color'];
            }
            
            if (isset($row['size']) && !in_array($row['size'], $sizes)) {
                $sizes[] = $row['size'];
            }
        }

        return [
            'colors' => $colors,
            'sizes' => $sizes,
        ];
    }
}
