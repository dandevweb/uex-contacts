<?php

use App\Models\User;

use function Pest\Laravel\{assertDatabaseMissing, assertDatabaseHas};

it('deletes the authenticated user account successfully', function () {
    $user = User::factory()->create(['password' => 'Password1']);

    assertDatabaseHas('users', [
        'id'    => $user->id,
        'email' => $user->email,
    ]);

    $this->actingAs($user)
        ->deleteJson(route('users.delete'))
        ->assertStatus(204);

    assertDatabaseMissing('users', [
        'id'    => $user->id,
        'email' => $user->email,
    ]);
});

it('does not allow unauthenticated users to delete an account', function () {
    $this->deleteJson(route('users.delete'))
        ->assertStatus(401);
});


todo('deletes related data when user account is deleted');
