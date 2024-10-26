<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\{deleteJson, actingAs};

it('should be able to delete the account with the correct password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('correct-password'),
    ]);

    actingAs($user);

    deleteJson(route('users.delete'), [
        'password' => 'correct-password',
    ])
    ->assertNoContent();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

it('should return an error when password is missing', function () {
    $user = User::factory()->create();

    actingAs($user);

    deleteJson(route('users.delete'), [])
        ->assertJsonValidationErrors(['password']);
});

it('should return an error when the password is incorrect', function () {
    $user = User::factory()->create([
        'password' => Hash::make('correct-password'), // Define a senha correta
    ]);

    actingAs($user);

    deleteJson(route('users.delete'), [
        'password' => 'incorrect-password',
    ])
    ->assertStatus(422);
});

it('should return an error when trying to delete an account without being authenticated', function () {
    deleteJson(route('users.delete'), [
        'password' => 'any-password',
    ])
    ->assertUnauthorized();
});

it('deletes all contacts when a user is deleted', function () {
    // Create a user with associated contacts
    $user = User::factory()->hasContacts(3)->create();

    $contacts = $user->contacts;
    // Verify that contacts exist in the database
    foreach ($contacts as $contact) {
        $this->assertDatabaseHas('contacts', ['id' => $contact->id]);
    }

    // Delete the user
    $user->delete();

    // Check that the contacts no longer exist in the database
    foreach ($contacts as $contact) {
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
});
