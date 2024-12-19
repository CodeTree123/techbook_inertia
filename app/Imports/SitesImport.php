<?php

namespace App\Imports;

use App\Models\CustomerSite;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SitesImport implements ToCollection
{
    /**
     * @param Collection $collection
     */

    protected $customerID;

    public function __construct($id)
    {
        $this->customerID = $id;
    }
    public function collection(Collection $collection)
    {
        $skipFirstRow = true;
        foreach ($collection as $key => $row) {
            if ($skipFirstRow) {
                $skipFirstRow = false;
                continue;
            }

            $site = new CustomerSite();
            $site->site_id = $row[0];
            $site->customer_id = $this->customerID;
            $site->description = $row[1];
            $site->location = $row[2];
            $site->address_1 = $row[3];
            $site->address_2 = $row[4];
            $site->city = $row[5];
            $site->state = $row[6];
            $site->zipcode = $row[7];
            $site->time_zone = $row[8];
            $site->store_phone = $row[9];
            $site->store_hour = $row[10];
            $site->save();
        }
    }
}
