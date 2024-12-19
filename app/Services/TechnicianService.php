<?php

namespace App\Services;

use App\Models\Technician;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;

class TechnicianService
{
    public function registerTechnician($request)
    {
        $request->validate([
            'company_name' => 'required|max:100',
            'address' => 'nullable|max:100',
            'city' => 'nullable|max:100',
            'state' => 'nullable|max:100',
            'zip_code' => 'nullable|max:5',
            'email' => 'nullable|email',
            // 'rate' => 'required|numeric',
            'radius' => 'nullable|max:100',
            'travel_fee' => 'nullable|numeric',
            // 'terms' => 'required',
            'phone' => 'nullable|max:15',
            'primary_contact_email' => 'email|nullable',
            'primary_contact' => 'nullable|max:255',
            'country' => 'nullable|max:100',
            'title' => 'nullable|max:255',
            'cell_phone' => 'nullable|max:15',
            'status' => 'nullable|max:40',
            'coi_expire_date' => 'date|nullable',
            'msa_expire_date' => 'date|nullable',
            'coi_file' => 'mimes:pdf|max:2048',
            'msa_file' => 'mimes:pdf|max:2048',
            'nda_file' => 'mimes:pdf|max:2048'
        ]);
        // generating unique 5 digit id starting with 5000
        // $fTech = Technician::latest('id')->first();
        // if ($fTech == null) {
        //     $firstReg = 0;
        //     $fTechId = $firstReg + 1;
        //     if ($fTechId < 10) {
        //         $id = '5000' . $fTechId;
        //     } elseif ($fTechId < 100) {
        //         $id = '500' . $fTechId;
        //     } elseif ($fTechId < 1000) {
        //         $id = '50' . $fTechId;
        //     } elseif ($fTechId < 10000) {
        //         $id = '5' . $fTechId;
        //     } elseif ($fTechId < 100000) {
        //         $id = '5' . $fTechId;
        //     }
        // } else {
        //     $id = $fTech->id;
        //     $fTechId = $id + 1;
        //     if ($fTechId < 10) {
        //         $id = '5000' . $fTechId;
        //     } elseif ($fTechId < 100) {
        //         $id = '500' . $fTechId;
        //     } elseif ($fTechId < 1000) {
        //         $id = '50' . $fTechId;
        //     } elseif ($fTechId < 10000) {
        //         $id = '5' . $fTechId;
        //     } elseif ($fTechId < 100000) {
        //         $id = '5' . $fTechId;
        //     }
        // }

        $addressData = [
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code
        ];

        $skillsIds = $request->skill_id;
        $technician = new Technician();
        $technician->rate = [
            'STD' => $request->std_rate,
            'EM' => $request->em_rate,
            'OT' => $request->ot_rate,
            'SH' => $request->sh_rate
        ];
        $technician->company_name = $request->company_name;
        $technician->address_data = $addressData;
        $technician->email = $request->email;
        $technician->primary_contact_email = $request->primary_contact_email;
        $technician->phone = $request->phone;
        $technician->primary_contact = $request->primary_contact;
        $technician->title = $request->title;
        $technician->cell_phone = $request->cell_phone;
        $technician->radius = $request->radius;
        $technician->travel_fee = $request->travel_fee;
        $technician->status = $request->status;
        $technician->coi_expire_date = $request->coi_expire_date;
        $technician->msa_expire_date = $request->msa_expire_date;
        $technician->nda = $request->nda;
        $technician->terms = $request->terms;
        $technician->preference = $request->preference;
        $technician->c_wo_ct = $request->c_wo_ct;
        $technician->save();
        $technician->technician_id = sprintf('5%04d', $technician->id);
        $technician->save();
        // $technician->technician_id = $id;
        //pdf store here
        if ($request->coi_file) {
            $pdfFile = $request->file('coi_file');
            $pdfFileNameCoi = $technician->technician_id . '_' . $pdfFile->getClientOriginalName();
            $pdfFile->storeAs('pdfs', $pdfFileNameCoi, 'public');
            $technician->coi_file = $pdfFileNameCoi;
        }
        if ($request->msa_file) {
            $pdfFile = $request->file('msa_file');
            $pdfFileNameMsa = $technician->technician_id . '_' . $pdfFile->getClientOriginalName();
            $pdfFile->storeAs('pdfs', $pdfFileNameMsa, 'public');
            $technician->msa_file = $pdfFileNameMsa;
        }
        if ($request->nda_file) {
            $pdfFile = $request->file('nda_file');
            $pdfFileNameNda = $technician->technician_id . '_' . $pdfFile->getClientOriginalName();
            $pdfFile->storeAs('pdfs', $pdfFileNameNda, 'public');
            $technician->nda_file = $pdfFileNameNda;
        }
        //end pdf
        $technician->save();

        $review = new Review();
        $review->technician_id = $technician->id;
        $review->save();
        $technician->skills()->attach($skillsIds);

        $data = ['technician' => $technician, 'review' => $review];
        return $data;
    }

    public function updateTechnician($request, $id)
    {
        $request->validate([
            'company_name' => 'required|max:100',
            'address' => 'nullable|max:100',
            'city' => 'nullable|max:100',
            'state' => 'nullable|max:100',
            'zip_code' => 'nullable|max:5',
            'email' => 'nullable|email',
            // 'rate' => 'required|numeric',
            'radius' => 'nullable|max:100',
            'travel_fee' => 'nullable|numeric',
            'terms' => 'nullable',
            'phone' => 'nullable|max:15',
            'primary_contact_email' => 'email|nullable',
            'primary_contact' => 'max:255',
            'country' => 'max:100',
            'title' => 'max:255',
            'cell_phone' => 'max:15',
            'status' => 'max:40',
            'coi_expire_date' => 'date|nullable',
            'msa_expire_date' => 'date|nullable',
            'coi_file' => 'mimes:pdf|max:2048',
            'msa_file' => 'mimes:pdf|max:2048',
            'nda_file' => 'mimes:pdf|max:2048'
        ]);

        $update = Technician::find($id);
        $addressData = [
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code
        ];

        $update->rate = [
            'STD' => $request->std_rate,
            'EM' => $request->em_rate,
            'OT' => $request->ot_rate,
            'SH' => $request->sh_rate
        ];
        $skillsIds = $request->skill_id;
        $update->company_name = $request->company_name;
        $update->address_data = $addressData;
        $update->email = $request->email;
        $update->phone = $request->phone;
        $update->primary_contact = $request->primary_contact;
        $update->title = $request->title;
        $update->cell_phone = $request->cell_phone;
        $update->radius = $request->radius;
        $update->travel_fee = $request->travel_fee;
        $update->status = $request->status;
        $update->coi_expire_date = $request->coi_expire_date;
        $update->msa_expire_date = $request->msa_expire_date;
        $update->nda = $request->nda;
        $update->terms = $request->terms;
        $update->preference = $request->preference;
        $update->c_wo_ct = $request->c_wo_ct;
        $update->wo_ct = $request->wo_ct;

        // update pdf
        if ($request->hasFile('coi_file')) {
            Storage::disk('public')->delete('pdfs/' . $update->coi_file);
            $newCoiFile = $request->file('coi_file');
            $pdfFileNameCoi = $update->technician_id . '_' . $newCoiFile->getClientOriginalName();
            $newCoiFile->storeAs('pdfs', $pdfFileNameCoi, 'public');
            $update->coi_file = $pdfFileNameCoi;
        }
        if ($request->hasFile('msa_file')) {
            Storage::disk('public')->delete('pdfs/' . $update->msa_file);
            $newMsaFile = $request->file('msa_file');
            $pdfFileNameMsa = $update->technician_id . '_' . $newMsaFile->getClientOriginalName();
            $newMsaFile->storeAs('pdfs', $pdfFileNameMsa, 'public');
            $update->msa_file = $pdfFileNameMsa;
        }
        if ($request->hasFile('nda_file')) {
            Storage::disk('public')->delete('pdfs/' . $update->nda_file);
            $newNdaFile = $request->file('nda_file');
            $pdfFileNameNda = $update->technician_id . '_' . $newNdaFile->getClientOriginalName();
            $newNdaFile->storeAs('pdfs', $pdfFileNameNda, 'public');
            $update->nda_file = $pdfFileNameNda;
        }
        //end update pdf
        $update->save();
        if ($skillsIds) {
            $update->skills()->detach();
            $update->skills()->attach($skillsIds);
        }

        return $update;
    }
}
