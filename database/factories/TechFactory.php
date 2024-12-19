<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class TechFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create();
        $address = [
            'address' => $faker->address,
            'country' => $faker->country,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip_code' => $faker->postcode,
        ];

        return [
            //
        ];
    }
}
