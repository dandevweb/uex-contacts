<?php

use App\Models\{User, Contact};

use function Pest\Laravel\{putJson, actingAs};

it('updates a contact successfully', function () {
    $user    = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    $updatedData = [
        'name'         => 'Jane Doe',
        'cpf'          => '987.654.321-00',
        'phone'        => '(11) 88888-8888',
        'zip_code'     => '87654-321',
        'address'      => 'Avenida das Flores',
        'number'       => '456',
        'complement'   => 'Apto 456',
        'neighborhood' => 'Jardim',
        'city'         => 'Rio de Janeiro',
        'state'        => 'RJ',
        'latitude'     => -22.3456,
        'longitude'    => -43.7890,
    ];

    $response = putJson(route('contacts.update', $contact->id), $updatedData);

    $response->assertOk()
             ->assertJson([
                 'data' => [
                     'name'  => 'Jane Doe',
                     'cpf'   => '987.654.321-00',
                     'city'  => 'Rio de Janeiro',
                     'state' => 'RJ',
                 ]
             ]);

    $this->assertDatabaseHas('contacts', $updatedData + ['id' => $contact->id]);
});

it('fails to update a contact with missing name', function () {
    $user    = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    $updatedData = [
        'cpf'   => '987.654.321-00',
        'city'  => 'Rio de Janeiro',
        'state' => 'RJ'
    ];

    $response = putJson(route('contacts.update', $contact->id), $updatedData);

    $response->assertUnprocessable()
             ->assertJsonValidationErrors(['name']);
});

it('fails to update a contact with invalid CPF', function () {
    $user    = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    $updatedData = [
        'name'  => 'Jane Doe',
        'cpf'   => 'invalid-cpf',
        'city'  => 'Rio de Janeiro',
        'state' => 'RJ'
    ];

    $response = putJson(route('contacts.update', $contact->id), $updatedData);

    $response->assertUnprocessable()
             ->assertJsonValidationErrors(['cpf']);
});

it('fails to update a contact with duplicate CPF', function () {
    $user            = User::factory()->create();
    $existingContact = Contact::factory()->create(['user_id' => $user->id, 'cpf' => '123.456.789-00']);
    $contact         = Contact::factory()->create(['user_id' => $user->id, 'cpf' => '987.654.321-00']);

    actingAs($user);

    $updatedData = [
        'name'  => 'Jane Doe',
        'cpf'   => '123.456.789-00', // CPF duplicado
        'city'  => 'Rio de Janeiro',
        'state' => 'RJ'
    ];

    $response = putJson(route('contacts.update', $contact->id), $updatedData);

    $response->assertUnprocessable()
             ->assertJsonValidationErrors(['cpf']);
});

it('returns a 404 error when trying to update a contact of another user', function () {
    $user      = User::factory()->create();
    $otherUser = User::factory()->create();
    $contact   = Contact::factory()->create(['user_id' => $otherUser->id]);

    actingAs($user);

    $updatedData = Contact::factory()->make()->toArray();

    $response = putJson(route('contacts.update', $contact->id), $updatedData);

    $response->assertNotFound()
             ->assertJson([
                 'message' => __('Record not found.')
             ]);
});
