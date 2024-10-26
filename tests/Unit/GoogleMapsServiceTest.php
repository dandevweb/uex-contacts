<?php

use App\Services\GoogleMapsService;
use GuzzleHttp\{Client, HandlerStack};
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

it('returns coordinates for a valid address', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'status'  => 'OK',
            'results' => [
                [
                    'geometry' => [
                        'location' => [
                            'lat' => -23.55052,
                            'lng' => -46.633308,
                        ],
                    ],
                ],
            ],
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client       = new Client(['handler' => $handlerStack]);

    $service = new GoogleMapsService($client, 'fake-api-key');

    $coordinates = $service->getCoordinatesByAddress('Avenida Paulista, São Paulo');

    expect($coordinates)->toBe([
        'lat' => -23.55052,
        'lng' => -46.633308,
    ]);
});

it('returns null for an invalid address', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'status'  => 'ZERO_RESULTS',
            'results' => [],
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client       = new Client(['handler' => $handlerStack]);

    $service = new GoogleMapsService($client, 'fake-api-key');

    $coordinates = $service->getCoordinatesByAddress('Endereço inválido');

    expect($coordinates)->toBeNull();
});
