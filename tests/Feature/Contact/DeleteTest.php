<?php

use App\Models\{User, Contact};

use function Pest\Laravel\{actingAs, deleteJson};

it('deletes a contact successfully', function () {
    $user    = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    $response = deleteJson(route('contacts.destroy', $contact->id));

    $response->assertNoContent();

    $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
});

it('returns a 404 error when trying to delete a contact that does not exist', function () {
    $user = User::factory()->create();

    actingAs($user);

    $response = deleteJson(route('contacts.destroy', 999)); // Assuming 999 does not exist

    $response->assertNotFound()
             ->assertJson([
                 'message' => __('Record not found.')
             ]);
});

it('returns a 404 error when trying to delete a contact of another user', function () {
    $user      = User::factory()->create();
    $otherUser = User::factory()->create();
    $contact   = Contact::factory()->create(['user_id' => $otherUser->id]);

    actingAs($user);

    $response = deleteJson(route('contacts.destroy', $contact->id));

    $response->assertNotFound()
             ->assertJson([
                 'message' => __('Record not found.')
             ]);
});
