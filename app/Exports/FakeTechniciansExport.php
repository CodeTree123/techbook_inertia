<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Faker\Factory as Faker;
use Illuminate\Support\Collection;

class FakeTechniciansExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $faker = Faker::create();
        $technicians = [];
        $uniquePhoneNumbers = [];
        $uniqueCellPhoneNumbers = [];
        $uniqueEmails = [];

        for ($i = 0; $i < 5000; $i++) {

            $phone = $faker->numerify('017########');
            while (in_array($phone, $uniquePhoneNumbers)) {
                $phone = $faker->numerify('017########');
            }
            $uniquePhoneNumbers[] = $phone;

            $cell_phone = $faker->numerify('018########');
            while (in_array($cell_phone, $uniqueCellPhoneNumbers)) {
                $cell_phone = $faker->numerify('018########');
            }
            $uniqueCellPhoneNumbers[] = $cell_phone;

            $email = $faker->companyEmail;
            while (in_array($email, $uniqueEmails)) {
                $email = $faker->companyEmail;
            }
            $uniqueEmails[] = $email;


            $skills = [];
            for ($j = 0; $j < 5; $j++) {
                $words = $faker->randomElement(['Cable Certifier', 'Fiber', 'Fiber Tester', 'Wi-Fi', 'Heat Mapping', 'Air Magnet']);
                $skills[] = $words;
            }

            $skillsetsString = implode(', ', $skills);

            $technicians[] = [
                'company_name' => $faker->company,
                'address' => $faker->streetAddress,
                'country' => $faker->country,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip_code' => $faker->randomElement(['10001', '90001', '58324', '60001']),
                'email' => $email,
                'primary_contact_email' => $faker->email,
                'phone' => $phone,
                'primary_contact' => $faker->name,
                'title' => $faker->randomElement(['title1', 'title2', 'title3', 'title4']),
                'cell_phone' => $cell_phone,
                'rate' => $faker->randomElement(["Std: 50.0 / 50.0,EM: 60.0 / 60.0,OT: 65.0 / 65.0,S/H: 65.0 / 65.0"]),
                'radius' => $faker->randomElement(['50', '80', '90']),
                'travel_fee' => $faker->randomElement(['50', '80', '90']),
                'status' => $faker->randomElement(['Active', 'Inactive', 'Pending']),
                'preference' => $faker->randomElement(['Yes', 'No']),
                'coi_expire_date' => $faker->randomElement(['21-10-23', '22-10-23', '23-10-23', '24-10-23']),
                'msa_expire_date' => $faker->randomElement(['21-10-23', '22-10-23', '23-10-23', '24-10-23']),
                'nda' => $faker->randomElement(['Yes', 'No']),
                'terms' => $faker->randomElement(['30', '40', '60', '90']),
                'skillsets' => $skillsetsString,
            ];
        }

        return new Collection($technicians);
    }
}
