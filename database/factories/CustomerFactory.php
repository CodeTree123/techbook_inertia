<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */

class CustomerFactory extends Factory
{
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

        $lastCustomer = Customer::latest('id')->first();

        if ($lastCustomer == null) {
            $firstReg = 0;
            $customerId = $firstReg + 1;
        } else {
            $lastCustomerId = intval(substr($lastCustomer->customer_id, 4));
            $customerId = $lastCustomerId + 1;
        }

        $id = '';
        if ($customerId < 10) {
            $id = '6000' . $customerId;
        } elseif ($customerId < 100) {
            $id = '600' . $customerId;
        } elseif ($customerId < 1000) {
            $id = '60' . $customerId;
        } elseif ($customerId < 10000) {
            $id = '6' . $customerId;
        } elseif ($customerId < 100000) {
            $id = '6' . $customerId;
        }

        return [
            "customer_id" => $id,
            "company_name" => $faker->company,
            "address" => json_encode($address),
            "email" => $faker->unique()->safeEmail,
            "customer_type" => $faker->randomElement(['Customer', 'Prospect', 'Lead']),
            "phone" => $faker->phoneNumber,
            "s_rate" => $faker->randomElement(['40', '50', '60']),
            "e_rate" => $faker->randomElement(['40', '50', '60']),
            "travel" => $faker->randomElement(['50', '60', '80']),
            "billing_term" => $faker->randomElement(['NET30', 'NET40']),
            "type_phone" => $faker->randomElement(["Type1", "Type2", "Type3"]),
            "type_pos" => $faker->randomElement(['postype1', 'postype2', 'postype3']),
            "type_wireless" => $faker->randomElement(['wireless1', 'wireless2', 'wireless3']),
            "type_cctv" => $faker->randomElement(["cctv1", "cctv2", "cctv3"]),
            "team" => $faker->randomElement(["BlueTeam", "RedTeam"]),
            "sales_person" => $faker->randomElement(["40", "50", "60"]),
            "project_manager" => $faker->randomElement(["PM1", "PM2", "PM3"]),
        ];
    }
}
