<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ViaCepService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://viacep.com.br/ws/']);
    }

    public function getAddressByZipCode(string $zipCode): ?array
    {
        try {
            $response = $this->client->get("{$zipCode}/json/");

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['erro'])) {
                return [];
            }

            return $data;
        } catch (RequestException $e) {
            return [];
        }
    }

    public function suggestAddresses(string $state, string $city, string $street): array
    {
        try {
            $url      = "{$state}/{$city}/{$street}/json/";
            $response = $this->client->get($url);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['erro']) || empty($data)) {
                return [];
            }

            return $data;
        } catch (RequestException $e) {
            return [];
        }
    }
}
