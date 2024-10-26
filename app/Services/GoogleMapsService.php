<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;

class GoogleMapsService
{
    private Client $client;
    private string $apiKey;
    private const BASE_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    public function __construct(Client $client = null, string $apiKey = null)
    {
        $this->client = $client ?: new Client();
        $this->apiKey = $apiKey ?: config('services.google.maps.key');
    }

    public function getCoordinatesByAddress(string $address): ?array
    {
        $response = $this->client->get(self::BASE_URL, [
            'query' => [
                'address' => $address,
                'key'     => $this->apiKey,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if ($data['status'] === 'OK') {
            return [
                'lat' => $data['results'][0]['geometry']['location']['lat'],
                'lng' => $data['results'][0]['geometry']['location']['lng'],
            ];
        }

        return null;
    }
}
