<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'name'         => $this->faker->name(),
            'cpf'          => $this->faker->cpf(),
            'phone'        => $this->faker->phoneNumber(),
            'address'      => $this->faker->streetAddress(),
            'number'       => $this->faker->numberBetween(1, 100),
            'complement'   => $this->faker->secondaryAddress(),
            'neighborhood' => $this->faker->citySuffix(),
            'city'         => $this->faker->city(),
            'state'        => $this->faker->stateAbbr(),
            'zip_code'     => $this->faker->postcode(),
            'latitude'     => $this->faker->latitude(),
            'longitude'    => $this->faker->longitude(),
        ];
    }
}
