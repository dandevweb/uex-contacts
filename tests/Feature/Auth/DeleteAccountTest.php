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
