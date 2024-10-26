<?php

use ReflectionClass;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\{Request, Response};
use App\Services\ViaCepService;
use GuzzleHttp\Exception\RequestException;

it('fetches address by zip code successfully', function () {
    $mockedResponse = new Response(200, [], json_encode([
        'cep'        => '01001-000',
        'logradouro' => 'Praça da Sé',
        'bairro'     => 'Sé',
        'localidade' => 'São Paulo',
        'uf'         => 'SP',
    ]));

    $clientMock = mock(Client::class);
    $clientMock->shouldReceive('get')
        ->with('01001-000/json/')
        ->andReturn($mockedResponse);

    $service        = new ViaCepService();
    $reflection     = new ReflectionClass($service);
    $clientProperty = $reflection->getProperty('client');
    $clientProperty->setAccessible(true);
    $clientProperty->setValue($service, $clientMock);

    $result = $service->getAddressByZipCode('01001-000');

    expect($result)->toEqual([
        'cep'        => '01001-000',
        'logradouro' => 'Praça da Sé',
        'bairro'     => 'Sé',
        'localidade' => 'São Paulo',
        'uf'         => 'SP',
    ]);
});


it('returns empty array when zip code is invalid', function () {
    $mockedResponse = new Response(200, [], json_encode(['erro' => true]));

    $clientMock = mock(Client::class);
    $clientMock->shouldReceive('get')
        ->with('00000-000/json/')
        ->andReturn($mockedResponse);

    $service        = new ViaCepService();
    $reflection     = new ReflectionClass($service);
    $clientProperty = $reflection->getProperty('client');
    $clientProperty->setAccessible(true);
    $clientProperty->setValue($service, $clientMock);

    $result = $service->getAddressByZipCode('00000-000');

    expect($result)->toEqual([]);
});

it('returns empty array when request fails', function () {
    $clientMock = mock(Client::class);
    $clientMock->shouldReceive('get')
        ->with('01001-000/json/')
        ->andThrow(new RequestException('Error', new Request('GET', 'test')));

    $service        = new ViaCepService();
    $reflection     = new ReflectionClass($service);
    $clientProperty = $reflection->getProperty('client');
    $clientProperty->setAccessible(true);
    $clientProperty->setValue($service, $clientMock);

    $result = $service->getAddressByZipCode('01001-000');

    expect($result)->toEqual([]);
});

it('suggests addresses successfully', function () {
    $mockedResponse = new Response(200, [], json_encode([
        [
            'cep'        => '01001-000',
            'logradouro' => 'Praça da Sé',
            'bairro'     => 'Sé',
            'localidade' => 'São Paulo',
            'uf'         => 'SP',
        ],
        [
            'cep'        => '01002-000',
            'logradouro' => 'Praça da Liberdade',
            'bairro'     => 'Liberdade',
            'localidade' => 'São Paulo',
            'uf'         => 'SP',
        ]
    ]));

    $clientMock = mock(Client::class);
    $clientMock->shouldReceive('get')
        ->with('SP/São Paulo/Praça/json/')
        ->andReturn($mockedResponse);

    $service        = new ViaCepService();
    $reflection     = new ReflectionClass($service);
    $clientProperty = $reflection->getProperty('client');
    $clientProperty->setAccessible(true);
    $clientProperty->setValue($service, $clientMock);

    $result = $service->suggestAddresses('SP', 'São Paulo', 'Praça');

    expect($result)->toEqual([
        [
            'cep'        => '01001-000',
            'logradouro' => 'Praça da Sé',
            'bairro'     => 'Sé',
            'localidade' => 'São Paulo',
            'uf'         => 'SP',
        ],
        [
            'cep'        => '01002-000',
            'logradouro' => 'Praça da Liberdade',
            'bairro'     => 'Liberdade',
            'localidade' => 'São Paulo',
            'uf'         => 'SP',
        ]
    ]);
});

it('returns empty array when no addresses are found', function () {
    $mockedResponse = new Response(200, [], json_encode(['erro' => true]));

    $clientMock = mock(Client::class);
    $clientMock->shouldReceive('get')
        ->with('SP/São Paulo/Praça/json/')
        ->andReturn($mockedResponse);

    $service        = new ViaCepService();
    $reflection     = new ReflectionClass($service);
    $clientProperty = $reflection->getProperty('client');
    $clientProperty->setAccessible(true);
    $clientProperty->setValue($service, $clientMock);

    $result = $service->suggestAddresses('SP', 'São Paulo', 'Praça');

    expect($result)->toEqual([]);
});

it('returns empty array when suggest addresses request fails', function () {
    $clientMock = mock(Client::class);
    $clientMock->shouldReceive('get')
        ->with('SP/São Paulo/Praça/json/')
        ->andThrow(new RequestException('Error', new Request('GET', 'test')));

    $service        = new ViaCepService();
    $reflection     = new ReflectionClass($service);
    $clientProperty = $reflection->getProperty('client');
    $clientProperty->setAccessible(true);
    $clientProperty->setValue($service, $clientMock);

    $result = $service->suggestAddresses('SP', 'São Paulo', 'Praça');

    expect($result)->toEqual([]);
});
