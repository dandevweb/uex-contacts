<?php

use App\Models\{Contact, User};

use function Pest\Laravel\{getJson, actingAs};

it('lists paginated contacts for the authenticated user', function () {
    $user = User::factory()->hasContacts(15)->create();

    actingAs($user);

    $response = getJson(route('contacts.index', ['page' => 1, 'per_page' => 10]));

    $response->assertOk()
             ->assertJsonCount(10, 'data')  // Confirm that the first page shows 10 contacts
             ->assertJsonPath('meta.total', 15)  // Total contacts is 15
             ->assertJsonPath('meta.current_page', 1)  // Current page is 1
             ->assertJsonPath('meta.per_page', 10);  // Contacts per page is 10
});

it('fetches the second page of contacts', function () {
    $user = User::factory()->hasContacts(15)->create();

    actingAs($user);

    $response = getJson(route('contacts.index', ['page' => 2, 'per_page' => 10]));

    $response->assertOk()
             ->assertJsonCount(5, 'data')  // The second page shows 5 contacts
             ->assertJsonPath('meta.total', 15)  // Total contacts is 15
             ->assertJsonPath('meta.current_page', 2)  // Current page is 2
             ->assertJsonPath('meta.per_page', 10);  // Contacts per page is 10
});

it('returns contacts in default alphabetical order when no filters are applied', function () {
    $user = User::factory()->create();
    actingAs($user);

    // Create a random set of contacts with names in different orders
    $names = collect(['Carlos', 'Ana', 'Beatriz', 'Eduardo', 'Daniel', 'Fátima'])->shuffle();

    $names->each(fn ($name) => Contact::factory()->create([
        'user_id' => $user->id,
        'name'    => $name,
    ]));

    $response = getJson(route('contacts.index'));

    $response->assertOk()
             ->assertJsonPath('meta.total', $names->count());

    // Verify that the returned contacts are in alphabetical order
    $sortedNames   = $names->sort()->values()->all();
    $returnedNames = collect($response->json('data'))->pluck('name')->all();

    expect($returnedNames)->toEqual($sortedNames);
});

it('does not list contacts for other users in paginated response', function () {
    $user      = User::factory()->hasContacts(10)->create();
    $otherUser = User::factory()->hasContacts(5)->create();

    actingAs($user);

    $response = getJson(route('contacts.index', ['page' => 1, 'per_page' => 10]));

    $response->assertOk()
             ->assertJsonCount(10, 'data')  // The first page shows 10 contacts
             ->assertJsonMissing(['user_id' => $otherUser->id]);  // Verify that the other user's contacts are not listed
});

it('filters contacts by name', function () {
    $user     = User::factory()->create();
    $contact1 = Contact::factory()->for($user)->create(['name' => 'Alice']);
    $contact2 = Contact::factory()->for($user)->create(['name' => 'Bob']);

    actingAs($user);

    $response = getJson(route('contacts.index', ['name' => 'Alice']));

    $response->assertOk()
             ->assertJsonCount(1, 'data')  // Only one contact should be listed
             ->assertJsonPath('data.0.name', 'Alice');  // The contact with name 'Alice' should be listed
});

it('filters contacts by CPF', function () {
    $user     = User::factory()->create();
    $contact1 = Contact::factory()->for($user)->create(['cpf' => '123.456.789-01']);
    $contact2 = Contact::factory()->for($user)->create(['cpf' => '098.765.432-10']);

    actingAs($user);

    $response = getJson(route('contacts.index', ['cpf' => '123.456.789-01']));

    $response->assertOk()
             ->assertJsonCount(1, 'data')  // Only one contact should be listed
             ->assertJsonPath('data.0.cpf', '123.456.789-01');  // The contact with CPF '123.456.789-01' should be listed
});

it('sorts contacts by name in ascending order', function () {
    $user = User::factory()->create();
    Contact::factory()->for($user)->create(['name' => 'Charlie']);
    Contact::factory()->for($user)->create(['name' => 'Alice']);
    Contact::factory()->for($user)->create(['name' => 'Bob']);

    actingAs($user);

    $response = getJson(route('contacts.index', ['sort_by' => 'name', 'sort_order' => 'asc']));

    $response->assertOk()
             ->assertJsonPath('data.0.name', 'Alice')
             ->assertJsonPath('data.1.name', 'Bob')
             ->assertJsonPath('data.2.name', 'Charlie');  // verify that the contacts are sorted by name in ascending order
});

it('sorts contacts by name in descending order', function () {
    $user = User::factory()->create();
    Contact::factory()->for($user)->create(['name' => 'Charlie']);
    Contact::factory()->for($user)->create(['name' => 'Alice']);
    Contact::factory()->for($user)->create(['name' => 'Bob']);

    actingAs($user);

    $response = getJson(route('contacts.index', ['sort_by' => 'name', 'sort_order' => 'desc']));

    $response->assertOk()
             ->assertJsonPath('data.0.name', 'Charlie')
             ->assertJsonPath('data.1.name', 'Bob')

             ->assertJsonPath('data.2.name', 'Alice');  // verify that the contacts are sorted by name in descending order
});

it('returns validation error for invalid CPF format', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = getJson(route('contacts.index', ['cpf' => '123456789']));  // invalid CPF

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['cpf']);
});

it('returns validation error for invalid sort_by field', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = getJson(route('contacts.index', ['sort_by' => 'invalid_field']));

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['sort_by']);
});

it('returns validation error for invalid sort_order direction', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = getJson(route('contacts.index', ['sort_order' => 'invalid_order']));

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['sort_order']);
});

it('returns validation error for per_page below the minimum value', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = getJson(route('contacts.index', ['per_page' => 0]));  // per_page below the minimum value

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['per_page']);
});
