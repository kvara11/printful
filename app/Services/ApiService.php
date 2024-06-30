<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use App\Traits\ApiResponses;

class ApiService
{
    use ApiResponses;

    private $client;
    private string $url = "https://api.printful.com";
    private readonly string $apiKey;

    public function __construct(string $apiKey)
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
     * @param int $id The ID of the catalog product.
     * @return array An array of variant data, empty array if no variants found, or an error array if an error occurs.
     * @throws Exception If client initialization fails.
     */
    public function fetchProductCatalogById(int $id): array
    {
        try {

            if (empty($this->client) || !$this->client instanceof Client) {
                throw new \Exception('Initialize client first');
            }

            $response = $this->client->request('GET', "v2/catalog-products/{$id}/catalog-variants");

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
}
