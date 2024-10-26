<?php

use App\Models\{Contact, User};

use function Pest\Laravel\{actingAs, getJson};

it('retrieves a contact successfully with a valid ID', function () {
    $user    = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    $response = getJson(route('contacts.show', $contact->id));

    $response->assertOk()
             ->assertJson([
                 'data' => [
                     'id'    => $contact->id,
                     'name'  => $contact->name,
                     'cpf'   => $contact->cpf,
                     'city'  => $contact->city,
                     'state' => $contact->state,
                 ]
             ]);
});

it('returns a 404 error for a non-existing contact ID', function () {
    $user = User::factory()->create();

    actingAs($user);

    $response = getJson(route('contacts.show', 99999)); // ID que não existe

    $response->assertNotFound()
        ->assertJson([
            'message' => __('Record not found.')
        ]);
});

it('returns a 404 error when trying to access a contact of another user', function () {
    $user      = User::factory()->create();
    $otherUser = User::factory()->create();
    $contact   = Contact::factory()->create(['user_id' => $otherUser->id]);

    actingAs($user);

    $response = getJson(route('contacts.show', $contact->id));

    $response->assertNotFound()
             ->assertJson([
                 'message' => __('Record not found.')
             ]);
});

it('retrieves a contact with all fields populated', function () {
    $user    = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    $response = getJson(route('contacts.show', $contact->id));

    $response->assertOk()
             ->assertJson([
                 'data' => [
                     'id'           => $contact->id,
                     'name'         => $contact->name,
                     'cpf'          => $contact->cpf,
                     'phone'        => $contact->phone,
                     'zip_code'     => $contact->zip_code,
                     'address'      => $contact->address,
                     'number'       => $contact->number,
                     'complement'   => $contact->complement,
                     'neighborhood' => $contact->neighborhood,
                     'city'         => $contact->city,
                     'state'        => $contact->state,
                     'latitude'     => $contact->latitude,
                     'longitude'    => $contact->longitude,
                 ]
             ]);
});
