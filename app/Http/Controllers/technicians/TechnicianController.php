<?php

namespace App\Http\Controllers\technicians;

use App\CustomClass\DistanceMatrixService;
use App\Exports\FakeTechniciansExport;
use App\Http\Controllers\Controller;
use App\Imports\TechniciansImport;
use App\Models\AssignedEngineer;
use App\Models\CheckInOut;
use App\Models\Engineer;
use App\Models\Review;
use App\Models\SkillCategory;
use App\Models\Technician;
use App\Models\WorkOrder;
use App\Services\GeocodingService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\TechnicianService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Spatie\DbDumper\Databases\MySql;
use Exception;

class TechnicianController extends Controller
{
    protected $technicianService;
    protected $geocodingService;
    public function __construct(TechnicianService $technicianService, GeocodingService $geocodingService)
    {
        $this->technicianService = $technicianService;
        $this->geocodingService = $geocodingService;
    }

    public function index()
    {
        $pageTitle = 'Technician Details';
        $fTechData = $this->techData();
        $details = $fTechData['data'];
        return $this->techView($pageTitle, $details);
    }
    private function techData()
    {
        $fTechs = Technician::with(['skills']);
        $fTechs = $fTechs->searchable(['technician_id', 'company_name', 'address_data->zip_code']);
        return [
            'data' => $fTechs->orderBy('id', 'desc')->paginate(getPaginate()),
        ];
    }
    private function DocUnverifiedFtech()
    {
        $fTechs = Technician::DocUnverifiedFtech()->with(['skills']);
        $fTechs = $fTechs->searchable(['technician_id', 'company_name']);
        return [
            'data' => $fTechs->orderBy('id', 'desc')->paginate(getPaginate()),
        ];
    }
    private function DocVerifiedFtech()
    {
        $fTechs = Technician::DocVerifiedFtech()->with(['skills']);
        $fTechs = $fTechs->searchable(['technician_id', 'company_name']);
        return [
            'data' => $fTechs->orderBy('id', 'desc')->paginate(getPaginate()),
        ];
    }
    private function AvailableFtech()
    {
        $fTechs = Technician::AvailableFtech()->with(['skills']);
        $fTechs = $fTechs->searchable(['technician_id', 'company_name']);
        return [
            'data' => $fTechs->orderBy('id', 'desc')->paginate(getPaginate()),
        ];
    }
    private function AssignedFtech()
    {
        $fTechs = Technician::AssignedFtech()->with(['skills']);
        $fTechs = $fTechs->searchable(['technician_id', 'company_name']);
        return [
            'data' => $fTechs->orderBy('id', 'desc')->paginate(getPaginate()),
        ];
    }
    private function techView($pageTitle, $details)
    {
        return view('admin.technicians.index', compact('pageTitle', 'details'));
    }
    public function dUnverifiedTech()
    {
        $pageTitle = "Documents Unverified Technician";
        $fTechData = $this->DocUnverifiedFtech();
        $details = $fTechData['data'];
        return $this->techView($pageTitle, $details);
    }
    public function dVerifiedTech()
    {
        $pageTitle = "Documents Verified Technician";
        $fTechData = $this->DocVerifiedFtech();
        $details = $fTechData['data'];
        return $this->techView($pageTitle, $details);
    }
    public function availableTech()
    {
        $pageTitle = "Available Technician";
        $fTechData = $this->AvailableFtech();
        $details = $fTechData['data'];
        return $this->techView($pageTitle, $details);
    }
    public function disableTech()
    {
        $pageTitle = "Disable Technician";
        $fTechData = $this->AssignedFtech();
        $details = $fTechData['data'];
        return $this->techView($pageTitle, $details);
    }
    public function create()
    {
        $pageTitle = 'Technician Registration';
        $skills = SkillCategory::all();
        return view('admin.technicians.create', compact('pageTitle', 'skills'));
    }

    public function allSkill()
    {
        $skills = SkillCategory::all();
        return response()->json([
            'skills' => $skills,
        ]);
    }
    public function skills(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skill_name' => 'required|unique:skill_categories|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $skill_category = new SkillCategory();
        $skill_category->skill_name = $request->skill_name;
        $skill_category->save();

        return response()->json(['message' => 'Skill added successfully'], 200);
    }
    public function storeSkills(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'skill_name.*' => 'required|unique:skill_categories,skill_name|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        } else {
            for ($i = 0; $i < count($request->skill_name); $i++) {
                $skill = new SkillCategory();
                $skill->skill_name = $request->skill_name[$i];
                $skill->save();
            }
            return response()->json(['success' => 'Skills added successfully'], 200);
        }
    }
    public function storeTech(Request $request)
    {
        $result = $this->technicianService->registerTechnician($request);
        if (isset($result['technician']) && $result['review']) {
            $notify[] = ['success', 'Technician registration successful'];
            return redirect()->route('technician.index')->withNotify($notify);
        }
    }
    //begin skill List
    public function skillList()
    {
        $pageTitle = 'Technician Skill List';
        $skills = SkillCategory::all();
        if (request()->ajax()) {
            return response()->json(['skills' => $skills], 200);
        } else {
            return view('admin.technicians.skill.skill', compact('pageTitle', 'skills'));
        }
    }
    //end skill List

    //begin edit function
    public function techEdit($id)
    {
        $pageTitle = "Edit Technician";
        $edit = Technician::with('skills')->get()->find($id);
        $skill_sets = $edit->skills->pluck('skill_name')->toArray();
        $skill_sets_string = implode(", ", $skill_sets);
        return view('admin.technicians.edit_technician', compact('pageTitle', 'edit', 'skill_sets_string'));
    }
    public function techDelete($id)
    {
        $delete = Technician::findOrFail($id);
        $delete->delete();
        $notify[] = ['success', 'Data deleted successfully!'];
        return back()->withNotify($notify);
    }
    public function techEditPost(Request $request, $id)
    {
        $result = $this->technicianService->updateTechnician($request, $id);
        if ($result) {
            $notify[] = ['success', 'Technician record updated succesfully'];
            return redirect()->route('technician.index')->withNotify($notify);
        }
    }

    public function catEdit($id)
    {
        $pageTitle = "Edit Category";
        $edit = SkillCategory::find($id);
        return view('admin.technicians.skill.edit.cat_edit', compact('pageTitle', 'edit'));
    }

    //update skill sets
    public function catEditPost(Request $request, $id)
    {
        $update = SkillCategory::find($id);
        $update->skill_name = $request->skill_name;
        $update->save();
        $notify[] = ['success', 'Skill updated succesfully'];
        return to_route('technician.skillList')->withNotify($notify);
    }

    //delete skill sets
    public function catDelete($id)
    {
        try {
            $delete = SkillCategory::findOrFail($id);
            $delete->delete();
            $notify[] = ['success', 'Skill deleted successfully'];
            return back()->withNotify($notify);
        } catch (QueryException $e) {
            if (Str::contains($e->getMessage(), 'foreign key constraint')) {
                $notify[] = ['error', 'You cannot delete this skill because it is currently in use.'];
                return back()->withNotify($notify);
            }
        }
    }

    // getting technicians data from database
    public function getTech(Request $request)
    {
        $id = $request->tech_id;
        $technician = Technician::with('skills', 'review')->findOrFail($id);
        $this->replaceNullWithEmptyString($technician);
        $tech = collect($technician)->except('co_ordinates');
        $skill_sets = $technician->skills->pluck('skill_name')->toArray();
        $skill_sets_string = implode(", ", $skill_sets);

        return response()->json(
            [
                'technician' => $tech,
                'skill_sets' => $skill_sets_string,
            ],
            200
        );
    }

    private function replaceNullWithEmptyString($object)
    {
        if (is_object($object)) {
            foreach ($object->getAttributes() as $key => $value) {
                if (is_null($value)) {
                    $object->$key = '';
                } elseif (is_object($value)) {
                    $this->replaceNullWithEmptyString($value);
                } elseif (is_array($value)) {
                    foreach ($value as $item) {
                        $this->replaceNullWithEmptyString($item);
                    }
                }
            }
        } elseif (is_array($object)) {
            foreach ($object as &$item) {
                $this->replaceNullWithEmptyString($item);
            }
        }
    }

    public function updateStar(Request $request)
    {

        $review = Review::where('technician_id', $request->tech_id)->first();
        if ($review) {
            $review->star_value = $request->star_value;
            $review->save();
            $technician = Technician::findOrFail($request->tech_id);
            $technician->review_id = $review->id;
            $technician->save();
            return response()->json([
                'success' => 'Technician updated',
            ]);
        } else {
            return response()->json([
                'error' => 'Review not found for the specified technician',
            ]);
        }
    }

    public function updateComment(Request $request)
    {
        $review = Review::where('technician_id', $request->tech_id)->first();
        if ($review) {
            $review->comments = $request->comment;
            $review->save();
            return response()->json([
                'success' => 'comments updated',
            ]);
        } else {
            return response()->json([
                'error' => 'Review not found for the specified technician',
            ]);
        }
    }
    //begin pdf view
    public function viewPdf_coi($id_coi)
    {
        $tech = Technician::find($id_coi);

        $filePath = 'public/pdfs/' . $tech->coi_file;
        if (Storage::exists($filePath)) {
            return response()->file(storage_path('app/' . $filePath));
        } else {
            abort(404, 'File not found');
        }
    }
    public function viewPdf_msa($id_msa)
    {
        $tech = Technician::find($id_msa);

        $filePath = 'public/pdfs/' . $tech->msa_file;
        if (Storage::exists($filePath)) {
            return response()->file(storage_path('app/' . $filePath));
        } else {
            abort(404, 'File not found');
        }
    }
    public function viewPdf_nda($id_nda)
    {
        $tech = Technician::find($id_nda);

        $filePath = 'public/pdfs/' . $tech->nda_file;
        if (Storage::exists($filePath)) {
            return response()->file(storage_path('app/' . $filePath));
        } else {
            abort(404, 'File not found');
        }
    }
    //end pdf view

    //return bulk import view
    public function importView()
    {
        $pageTitle = "Bulk Import Technicians";
        return view('admin.technicians.techImport', compact('pageTitle'));
    }

    public function import(Request $request)
    {
        $max_exec_time = ini_get('max_execution_time');

        ini_set('max_execution_time', 300);

        $excelFile = $request->all();

        $rules = [
            'excel_file' => 'required|max:5120',
        ];

        $message = [
            'excel_file.required' => 'Please select a file to upload.',
            'excel_file.mimes' => 'The uploaded file must be in CSV format.',
            'excel_file.max' => 'The file size cannot exceed 5MB.',
        ];

        $file = $request->file('excel_file');

        $validator = Validator::make($excelFile, $rules, $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            // Excel::import(new TechniciansImport, $file, 'csv');
            $import = new TechniciansImport();
            $import->import($file);
            ini_set('max_execution_time', $max_exec_time);
            return response()->json(['success' => 'Your file was successfully imported.'], 200);
        }
    }

    public function export()
    {
        return Excel::download(new FakeTechniciansExport, 'fake_technicians.csv');
    }

    public function sampleExcel()
    {
        $filePath = storage_path('app/files/10Data_technicians.csv');
        return response()->download($filePath);
    }

    public function progress()
    {
        $progressArray = Cache::get('import-progress');
        return response()->json(['percentage' => $progressArray]);
    }
    public function checkInOut()
    {
        $pageTitle = "Technician Check-In/Out";
        $checkInOut = CheckInOut::searchable(['tech_name', 'company_name', 'date', 'time_zone'])->dateFilter()->paginate(getPaginate());
        return view('admin.technicians.check_in_out', compact('pageTitle', 'checkInOut'));
    }
    public function generatePDFCheckInOut(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'date' => 'required',
                'company_name' => 'required'
            ]
        );
        if ($validate->fails()) {
            $notify[] = ['error', 'Input date and Company field'];
            return back()->withNotify($notify);
        }
        $data = CheckInOut::where('date', $request->date)->where('company_name', $request->company_name)->get();
        $pdf = PDF::loadView('admin.technicians.pdf_check_in_out', [
            'data' => $data,
        ]);
        return $pdf->download($request->date . "-" . $request->company_name . "-" . 'checkIn_Out.pdf');
    }

    public function databaseBackup()
    {
        // dd("This service is not available at this moment. Please try again after some time.");
        try {
            $backupDir = public_path('sql_backup');

            if (!File::isDirectory($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $dateTime = now()->format('Y-m-d-h-i-s-a');
            $backup_file_name = $dateTime . ".sql";
            $backup_file_path = $backupDir . "/" . $backup_file_name;

            MySql::create()
                ->setDumpBinaryPath('/usr/bin')
                ->setDbName(config('database.connections.mysql.database'))
                ->setUserName(config('database.connections.mysql.username'))
                ->setPassword(config('database.connections.mysql.password'))
                ->setHost(config('database.connections.mysql.host'))
                ->dumpToFile($backup_file_path);

            if (file_exists($backup_file_path)) {
                return response()->download($backup_file_path);
            }

            return response()->json(['message' => 'Backup created successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create backup: ' . $e->getMessage()], 500);
        }
    }

    public function getCoordinate(Request $request)
    {
        $address = $request->input('address');
        $geoCode = new GeocodingService();
        $response = $geoCode->geocodeAddress($address);
        return response()->json($response);
    }

    public function geocodeIndex()
    {
        $pageTitle = 'Technician Address Conversion';
        return view('admin.technicians.geocode', compact('pageTitle'));
    }

    public function techAutocomplete(Request $request)
    {
        $query = $request->input('query');
        $results = Technician::select('id', 'company_name', 'technician_id')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('company_name', 'like', '%' . $query . '%')
                    ->orWhere('technician_id', 'like', '%' . $query . '%');
            })->limit(10)->get();

        return response()->json(['results' => $results], 200);
    }

    public function assignLatLong(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'techId' => 'required',
            'tech_lat' => 'required',
            'tech_long' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            Technician::where('id', $request->techId)->update([
                'co_ordinates' => DB::raw("ST_GeomFromText('POINT($request->tech_lat $request->tech_long)', 4326)"),
            ]);

            return response()->json(['success' => 'Co-Ordinates updated for this technician.'], 200);
        } catch (QueryException $e) {
            return response()->json(['exceptions' => 'Failed to update co-ordinates.'], 500);
        }
    }

    public function multiAssignCoordinate()
    {
        $max_exec_time = ini_get('max_execution_time');
        ini_set('max_execution_time', 300);
        $technicians = Technician::whereRaw("ST_X(co_ordinates) IS NULL OR ST_Y(co_ordinates) IS NULL")->get(['id', 'address_data']);
        $address_array = [];
        if (count($technicians) != 0) {
            foreach ($technicians as $technician) {
                $address['city'] = $technician->address_data->city;
                $address['state'] = $technician->address_data->state;
                $address['zipcode'] = $technician->address_data->zip_code;
                $address['country'] = $technician->address_data->country;
                $address_string = implode(", ", $address);
                $address_array[] = [
                    'id' => $technician->id,
                    'address' => $address_string
                ];
            }
            $coordinates = $this->geocodingService->geocodeAddresses($address_array);
            if ($coordinates != null) {
                foreach ($coordinates as $value) {
                    Technician::where('id', $value['id'])->update([
                        'co_ordinates' => DB::raw("ST_GeomFromText('POINT(" . $value['lat'] . " " . $value['lng'] . ")', 4326)"),
                    ]);
                }
            }
            ini_set('max_execution_time', $max_exec_time);
            return response()->json(['message' => 'Coordinates updated successfully'], 200);
        } else {
            ini_set('max_execution_time', $max_exec_time);
            return response()->json(['warning' => 'No technician found with empty coordinates !'], 422);
        }
    }
    public function zipCodeSearch(Request $request)
    {
        $zipcode = $request->input('zipcode');
        $technicians = Technician::where('address_data->zip_code', '=', $zipcode)->get();
        $technicians = $technicians->map(function ($technician) {
            foreach ($technician->toArray() as $key => $value) {
                $technician[$key] = is_string($value) ? utf8_encode($value) : $value;
            }
            return $technician;
        });
        return response()->json(['technicians' => $technicians]);
    }
    public function zipCodeDetails($id)
    {
        $technician = Technician::findOrFail($id);
        $technician = $technician->toArray();
        foreach ($technician as $key => $value) {
            if (is_string($value)) {
                $technician[$key] = utf8_encode($value);
            }
        }
        return response()->json(['technician' => $technician]);
    }


    public function engineerList()
    {
        $pageTitle = "Engineer List";
        $technicians = Technician::all();
        $engineers = Engineer::paginate(getPaginate());
        return view('admin.technicians.engineers.index', compact('pageTitle', 'technicians', 'engineers'));
    }

    public function createEngineer(Request $request)
    {
        $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image validation
        ]);

        $eng = new Engineer();

        $eng->tech_id = $request->tech_id;
        $eng->name = $request->name;
        $eng->role = $request->role;
        $eng->phone = $request->phone;
        $eng->email = $request->email;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('assets/admin/technician/'), $fileName);

            $eng->avatar = 'assets/admin/technician/' . $fileName;
        }

        $eng->save();

        $notify[] = ['success', 'Engineer created successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function assignEng(Request $request, $id)
    {
        $techs = $request->techs;

        if($techs){
            foreach ($techs as $tech) {
                $assingEng = new AssignedEngineer();
    
                $assingEng->wo_id = $id;
                $assingEng->tech_id = $tech['value'];
    
                $assingEng->save();
            }
        }
        

        if ($request->name) {
            $eng = new Engineer();
            $wo = WorkOrder::find($id);

            $eng->tech_id = $wo->ftech_id;
            $eng->name = $request->name;
            $eng->role = $request->role;
            $eng->phone = $request->phone;
            $eng->email = $request->email;

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');

                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('assets/admin/technician/'), $fileName);

                $eng->avatar = 'assets/admin/technician/' . $fileName;
            }

            $eng->save();

            $newAssingEng = new AssignedEngineer();

            $newAssingEng->wo_id = $id;
            $newAssingEng->tech_id = $eng->id;

            $newAssingEng->save();
        }


        $notify[] = ['success', 'Technician assigned successfully'];
        return redirect()->back()->withNotify($notify);
    }
}