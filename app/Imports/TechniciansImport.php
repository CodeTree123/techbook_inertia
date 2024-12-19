<?php

namespace App\Imports;

use App\Models\Review;
use App\Models\Technician;
use App\Models\SkillCategory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class TechniciansImport implements

    ToModel,
    WithValidation,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading
{
    use Importable;

    public function model(array $row)
    {
        $companyName = $row['company_name'];
        $row['country'] = isset($row['country']) ? $row['country'] : "USA";

        $addressData = [
            'address' => $row['address'],
            'country' => $row['country'],
            'city' => $row['city'],
            'state' => $row['state'],
            'zip_code' => $row['zip_code'],
        ];

        $rateData = [
            'STD' => $row['std_rate'],
            'EM'  => $row['em_rate'],
            'OT'  => $row['ot_rate'],
            'SH'  => $row['sh_rate'],
        ];

        $email = $row['email'];
        $primary_contact_email = $row['primary_contact_email'];
        $phone = $row['phone'];
        $primaryContact = $row['primary_contact'];
        $title = $row['title'];
        $cellPhone = $row['cell_phone'];
        $radius = $row['radius'];
        $travelFee = $row['travel_fee'];
        $status = ucfirst($row['status']);
        $preference = $row['preference'];
        $coi_expire_date = $row['coi_expire_date'];
        $msa_expire_date = $row['msa_expire_date'];
        $nda = $row['nda'];
        $terms = $row['terms'];
        $cWOct = $row['c_wo_ct'];
        $skills = $row['skillsets'];
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
        $technician->rate = $rateData;
        $technician->radius = $radius;
        $technician->travel_fee = $travelFee;
        $technician->status = $status;
        $technician->preference = $preference;
        $technician->coi_expire_date = $coi_expire_date;
        $technician->msa_expire_date = $msa_expire_date;
        $technician->nda = $nda;
        $technician->terms = $terms;
        $technician->c_wo_ct = $cWOct;
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

    public function rules(): array
    {
        return [
            '*.company_name' => 'required',
            '*.address' => 'required',
            '*.country' => 'nullable',
            '*.city' => 'nullable',
            '*.state' => 'required',
            '*.zip_code' => 'required',
            // '*.phone' => ['unique:technicians,phone', 'nullable'],
            // '*.cell_phone' => ['unique:technicians,cell_phone', 'nullable'],
            // '*.rate' => 'nullable',
            // '*.radius' => 'nullable',
            // '*.travel_fee' => 'nullable',
            '*.status' => 'nullable',
            '*.coi_expire_date' => 'date|nullable',
            '*.msa_expire_date' => 'date|nullable',
            // '*.email' => ['unique:technicians,email'],
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    // public function onFailure(Failure ...$failures)
    // {

    // }
}
