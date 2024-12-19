<?php

namespace App\Imports;

use App\Models\Review;
use App\Models\SkillCategory;
use App\Models\Technician;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserTechImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $skipFirstRow = true;

        foreach ($rows as $index => $row) {

            if ($skipFirstRow) {
                $skipFirstRow = false;
                continue;
            }

            $companyName = $row[0];
            $addressData = [
                'address' => $row[1],
                'country' => $row[2],
                'city' => $row[3],
                'state' => $row[4],
                'zip_code' => $row[5],
            ];
            $email = $row[6];
            $primary_contact_email = $row[7];
            $phone = $row[8];
            $primaryContact = $row[9];
            $title = $row[10];
            $cellPhone = $row[11];
            $rate = $row[12];
            $radius = $row[13];
            $travelFee = $row[14];
            $status = ucfirst($row[15]);
            $preference = $row[16];
            $coi_expire_date = $row[17];
            $msa_expire_date = $row[18];
            $nda = $row[19];
            $terms = $row[20];
            $skills = $row[21];
            $skills = str_replace(',', ',', $skills);
            $skills = preg_replace('/\s*,\s*/', ',', $skills);
            $skillsArray = explode(',', $skills);

            $technician = new Technician();
            $technician->company_name = $companyName;
            $technician->address_data = $addressData;
            $technician->email = $email;
            $technician->primary_contact_email = $primary_contact_email;
            $technician->phone = $phone;
            $technician->primary_contact = $primaryContact;
            $technician->title = $title;
            $technician->cell_phone = $cellPhone;
            $technician->rate = $rate;
            $technician->radius = $radius;
            $technician->travel_fee = $travelFee;
            $technician->status = $status;
            $technician->preference = $preference;
            $technician->coi_expire_date = $coi_expire_date;
            $technician->msa_expire_date = $msa_expire_date;
            $technician->nda = $nda;
            $technician->terms = $terms;
            $technician->save();

            $generatedId = $technician->id;

            if ($generatedId < 10) {
                $technician->technician_id = '5000' . $generatedId;
            } elseif ($generatedId < 100) {
                $technician->technician_id = '500' . $generatedId;
            } elseif ($generatedId < 1000) {
                $technician->technician_id = '50' . $generatedId;
            } elseif ($generatedId < 10000) {
                $technician->technician_id = '5' . $generatedId;
            } elseif ($generatedId < 100000) {
                $technician->technician_id = '5' . $generatedId;
            }
            $technician->save();

            $review = new Review();
            $review->technician_id = $technician->id;
            $review->save();

            foreach ($skillsArray as  $value) {
                $skillSets = SkillCategory::firstOrCreate(['skill_name' => $value]);
                $technician->skills()->attach($skillSets->id);
            }
        }
    }
}
