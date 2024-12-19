<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomersImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $skipFirstRow = true;

        foreach ($collection as $key => $row) {
            if ($skipFirstRow) {
                $skipFirstRow = false;
                continue;
            }

            $addressData = [
                'address' => $row[1],
                'country' => $row[2],
                'city' => $row[3],
                'state' => $row[4],
                'zip_code' => $row[5],
            ];

            $customer = new Customer();
            $customer->company_name = $row[0];
            $customer->address = $addressData;
            $customer->email = $row[6];
            $customer->customer_type = $row[7];
            $customer->phone = $row[8];
            $customer->s_rate = $row[9];
            $customer->e_rate = $row[10];
            $customer->travel = $row[11];
            $customer->billing_term = $row[12];
            $customer->type_phone = $row[13];
            $customer->type_pos = $row[14];
            $customer->type_wireless = $row[15];
            $customer->type_cctv = $row[16];
            $customer->team = $row[17];
            $customer->sales_person = $row[18];
            $customer->project_manager = $row[19];
            $customer->save();

            $generatedId = $customer->id;

            if ($generatedId < 10) {
                $customer->customer_id = '6000' . $generatedId;
            } elseif ($generatedId < 100) {
                $customer->customer_id = '600' . $generatedId;
            } elseif ($generatedId < 1000) {
                $customer->customer_id = '60' . $generatedId;
            } elseif ($generatedId < 10000) {
                $customer->customer_id = '6' . $generatedId;
            } elseif ($generatedId < 100000) {
                $customer->customer_id = '6' . $generatedId;
            }
            $customer->save();
        }
    }
}
