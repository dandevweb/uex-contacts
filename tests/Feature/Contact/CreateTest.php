<?php

use App\Models\{User, Contact};

use function Pest\Laravel\{postJson, actingAs};

it('creates a contact successfully', function () {
    $user = User::factory()->create();

    actingAs($user);

    $contactData = [
        'name'         => 'John Doe',
        'cpf'          => '123.456.789-00',
        'phone'        => '(11) 99999-9999',
        'zip_code'     => '12345-678',
        'address'      => 'Rua do limoeiro',
        'number'       => '123',
        'complement'   => 'Apto 123',
        'neighborhood' => 'Centro',
        'city'         => 'São Paulo',
        'state'        => 'SP',
        'latitude'     => -12.3456,
        'longitude'    => -56.789,
    ];

    $response = postJson(route('contacts.store'), $contactData);

    $response->assertCreated()
             ->assertJson([
                 'data' => [
                     'name'  => 'John Doe',
                     'cpf'   => '123.456.789-00',
                     'city'  => 'São Paulo',
                     'state' => 'SP',
                 ]
             ]);

    $this->assertDatabaseHas('contacts', $contactData);
});

it('fails to create a contact with missing name', function () {
    $user = User::factory()->create();

    actingAs($user);

    $contactData = [
        'cpf'   => '123.456.789-00',
        'city'  => 'São Paulo',
        'state' => 'SP'
    ];

    $response = postJson(route('contacts.store'), $contactData);

    $response->assertUnprocessable()
             ->assertJsonValidationErrors(['name']);
});

it('fails to create a contact with invalid CPF', function () {
    $user = User::factory()->create();

    actingAs($user);

    $contactData = [
        'name'  => 'John Doe',
        'cpf'   => 'invalid-cpf',
        'city'  => 'São Paulo',
        'state' => 'SP'
    ];

    $response = postJson(route('contacts.store'), $contactData);

    $response->assertUnprocessable()
             ->assertJsonValidationErrors(['cpf']);
});

it('fails to create a contact with duplicate CPF', function () {
    $user = User::factory()->create();
    Contact::factory()->create(['user_id' => $user->id, 'cpf' => '123.456.789-00']);

    actingAs($user);

    $contactData = [
        'name'  => 'Jane Doe',
        'cpf'   => '123.456.789-00', // duplicated CPF
        'city'  => 'São Paulo',
        'state' => 'SP'
    ];

    $response = postJson(route('contacts.store'), $contactData);

    $response->assertUnprocessable()
             ->assertJsonValidationErrors(['cpf']);
});

it('creates a contact successfully with optional fields omitted', function () {
    $user = User::factory()->create();

    actingAs($user);

    $contactData = [
        'name'         => 'Alice',
        'cpf'          => '987.654.321-00',
        'phone'        => '(11) 99999-9999',
        'zip_code'     => '12345-678',
        'address'      => 'Rua do limoeiro',
        'number'       => '123',
        'neighborhood' => 'Centro',
        'city'         => 'São Paulo',
        'state'        => 'SP',
        'latitude'     => -12.3456,
        'longitude'    => -56.789,
    ];

    $response = postJson(route('contacts.store'), $contactData);

    $response->assertCreated()
        ->assertJsonMissing(['complement']);

    $this->assertDatabaseHas('contacts', $contactData);
});
