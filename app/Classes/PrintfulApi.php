<?php

namespace App\Classes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;

class PrintfulApi
{
    private readonly string $apiKey;
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


    /**
     * Fetches all catalog variants for a specific catalog product ID.
     *
     * @param int $productId The ID of the catalog product.
     * @return array An array of variant data, empty array if no variants found, or an error array if an error occurs.
     * @throws Exception If client initialization fails.
     */
    public function getProductCatalogById(int $productId): array
    {
        try {

            if (empty($this->client) || !$this->client instanceof Client) {
                throw new \Exception('Initialize client first');
            }

            $response = $this->client->request('GET', "v2/catalog-products/{$productId}/catalog-variants");

            if ($response->getStatusCode() === 200) {

                $data = json_decode($response->getBody(), true);
                
                return $data['data'] ?? [];
            }


            return [
                'error' => [
                    'message' => $response->getBody(),
                    'code' => $response->getStatusCode(),
                ],
            ];

        } catch (RequestException $e) {

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                ]
            ];
        }
    }


    /**
     * Returns an array of unique colors and sizes from an array of catalog variants.
     *
     * @param array $data An array of catalog variant data.
     * @return array array of colors and sizes.
     */
    public function getColorsAndSizes(array $data): array
    {
        $colors = [];
        $sizes  = [];

        $result = [];

        foreach ($data as $row) {

            if (isset($row['color']) && !in_array($row['color'], $colors)) {
                $colors[] = $row['color'];
            }
            
            if (isset($row['size']) && !in_array($row['size'], $sizes)) {
                $sizes[] = $row['size'];
            }
        }


        if (count($colors) > 0) {
            $result['colors'] = $colors;    
        }

        if (count($sizes) > 0) {
            $result['sizes'] = $sizes;    
        }

        return $result;
    }
}
