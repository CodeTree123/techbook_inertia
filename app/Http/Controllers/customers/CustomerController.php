<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constants\Status;
use App\Models\Customer;
use App\Models\CustomerSite;
use App\Models\SkillCategory;
use App\Models\Technician;
use App\Models\VendorCareList;
use App\Models\WorkOrder;
use App\Models\CustomerInvoice;
use App\Models\TicketNotes;
use App\Models\workOrderPerformed;
use App\Models\CheckInOut;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Services\GeocodingService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class CustomerController extends Controller
{
    protected $geocodingService;
    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }
    public function index()
    {
        if (request()->ajax()) {
            $query = Customer::select('id', 'customer_id', 'company_name', 'customer_type', 's_rate_f', 'e_rate_f', 'travel');
            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }

        $pageTitle = "Customer List";
        $customers = Customer::all();
        return $this->viewCustomer($pageTitle, $customers);
    }
    private function viewCustomer($pageTitle, $customers)
    {
        return view('admin.customers.index', compact('pageTitle', 'customers'));
    }
    public function customerWithOrder()
    {
        if (request()->ajax()) {
            $query = Customer::WithOrder()->select('id', 'customer_id', 'company_name', 'customer_type', 's_rate', 'e_rate', 'travel');
            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
        $pageTitle = "Customer With Order";
        $customers = Customer::WithOrder()->get();
        return $this->viewCustomer($pageTitle, $customers);
    }
    public function create()
    {
        $pageTitle = "Customer Registration";
        return view('admin.customers.create', compact('pageTitle'));
    }

    //site store
    public function sites(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'site_id' => 'required|regex:/^[A-Za-z0-9]{4,12}$/|unique:customer_sites,site_id',
            'location' => 'required',
            'address_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required|integer|digits:5',
            'time_zone' => 'required',
        ], [
            'site_id.required' => 'The site ID is required.',
            'site_id.regex' => 'The site ID must be between 4 and 12 alphanumeric characters.',
            'site_id.unique' => 'The site ID already exists.',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        //generating site id
        $customerId = Customer::findOrFail($request->customer_id)->customer_id;
        $id = $customerId . "-" . $request->site_id;

        try {
            $site = new CustomerSite();
            $site->customer_id = $request->customer_id;
            $site->site_id = $id;
            $site->description = $request->description;
            $site->location = $request->location;
            $site->address_1 = $request->address_1;
            $site->address_2 = $request->address_2;
            $site->city = $request->city;
            $site->state = $request->state;
            $site->zipcode = $request->zipcode;
            $site->time_zone = $request->time_zone;
            $site->save();
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['errors' => ['site_id' => 'A site with this Id already exists.']], 422);
            }
            return response()->json(['errors' => ['database' => 'An unexpected database error occurred.']], 500);
        }
        return response()->json(['message' => 'Site added successfully'], 200);
    }
    //end site

    //begin site list
    public function siteList()
    {
        if (request()->ajax()) {
            $query = CustomerSite::with('customer:id,company_name,s_rate_f,e_rate_f');

            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }

        $pageTitle = "Customer Site List";
        $sites = CustomerSite::with('customer')->get();
        return view('admin.customers.site', compact('pageTitle', 'sites'));
    }
    //end site List

    //edit siteList
    public function editSite($id)
    {
        $pageTitle = "Edit Site";
        $edit = CustomerSite::with('customer')->get()->find($id);
        return view('admin.customers.edit_site', compact('pageTitle', 'edit'));
    }
    public function deleteSite(Request $request, $id)
    {
        $site = CustomerSite::with('customer')->findOrFail($id);
        $customerId = $site->customer->id;
        $site->delete();
        $notify[] = ['success', 'Site deleted successfully'];
        if ($request->from_site_list == 1) {
            return to_route('customer.site.list')->withNotify($notify);
        }
        return redirect()->route('customer.customerZone', ['id' => $customerId])->withNotify($notify);
    }
    public function editSitePost(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required',
            'site_id' => 'required|unique:customer_sites,site_id,' . $id,
            'location' => 'required',
            'address_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required|integer|digits:5',
            'time_zone' => 'required',
        ]);

        $customerSite = CustomerSite::find($id);
        $customerSite->description = $request->description;
        $customerSite->location = $request->location;
        $customerSite->address_1 = $request->address_1;
        $customerSite->address_2 = $request->address_2;
        $customerSite->city = $request->city;
        $customerSite->state = $request->state;
        $customerSite->zipcode = $request->zipcode;
        $customerSite->time_zone = $request->time_zone;
        $customerSite->site_id = $request->site_id;
        $customerSite->customer_id = $request->customer_id;
        $customerSite->save();
        // if ($request->s_rate && $request->e_rate && $request->company_name) {
        //     $cUpdate = Customer::find($update->customer_id);
        //     $cUpdate->company_name = $request->company_name;
        //     $cUpdate->s_rate = $request->s_rate;
        //     $cUpdate->e_rate = $request->e_rate;
        //     $cUpdate->save();
        // }
        $notify[] = ['success', 'Customer Site updated successful'];
        return back()->withNotify($notify);
    }
    //end edit siteList

    //create customer
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'company_name' => 'required|max:100',
            'address' => 'required|max:100',
            'customer_type' => 'required|max:100',
            'phone' => 'required|max:15',
        ]);

        $addressData = [
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code
        ];

        $customer = new Customer();
        $customer->company_name = $request->company_name;
        $customer->address = $addressData;
        $customer->email = $request->email;
        $customer->customer_type = $request->customer_type;
        $customer->phone = $request->phone;
        $customer->s_rate_f = $request->s_rate_f;
        $customer->e_rate_f = $request->e_rate_f;
        $customer->s_rate_a = $request->s_rate_a;
        $customer->e_rate_a = $request->e_rate_a;
        $customer->w_rate_f = $request->w_rate_f;
        $customer->w_rate_a = $request->w_rate_a;
        $customer->sh_rate_f = $request->sh_rate_f;
        $customer->sh_rate_a = $request->sh_rate_a;
        $customer->travel = $request->travel;
        $customer->billing_term = $request->billing_term;
        $customer->type_phone = $request->type_phone;
        $customer->type_pos = $request->type_pos;
        $customer->type_wireless = $request->type_wireless;
        $customer->type_cctv = $request->type_cctv;
        $customer->team = $request->team;
        $customer->sales_person = $request->sales_person;
        $customer->project_manager = $request->project_manager;
        $customer->save();

        //generating unique 5 digit id and save again
        $customer->customer_id = sprintf('%d', 8528 + $customer->id);
        $customer->save();

        $notify[] = ['success', 'Customer registration successful'];
        return redirect()->route('customer.index')->withNotify($notify);
    }
    //end create customer

    //edit customer
    public function cusEdit($id)
    {
        $pageTitle = "Customer Edit";
        $edit = Customer::find($id);
        return view('admin.customers.edit_customer', compact('pageTitle', 'edit'));
    }
    public function cusDelete($id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->workOrder()->exists()) {
            $notify[] = ['error', 'Cannot delete customer with associated work orders'];
            return redirect()->route('customer.index')->withNotify($notify);
        }

        try {
            $customer->delete();
            $notify[] = ['success', 'Customer deleted successfully'];
            return redirect()->route('customer.index')->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Failed to delete customer. Please try again'];
            return redirect()->route('customer.index')->withNotify($notify);
        }
    }

    public function cusEditPost(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required|max:100',
            'address' => 'required|max:100',
            'customer_type' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'required|max:15',
        ]);

        $addressData = [
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code
        ];

        $update = Customer::find($id);
        $update->company_name = $request->company_name;
        $update->email = $request->email;
        $update->customer_type = $request->customer_type;
        $update->phone = $request->phone;
        $update->address = $addressData;
        $update->s_rate = $request->s_rate;
        $update->e_rate = $request->e_rate;
        $update->travel = $request->travel;
        $update->billing_term = $request->billing_term;
        $update->type_phone = $request->type_phone;
        $update->type_pos = $request->type_pos;
        $update->type_wireless = $request->type_wireless;
        $update->type_cctv = $request->type_cctv;
        $update->team = $request->team;
        $update->sales_person = $request->sales_person;
        $update->project_manager = $request->project_manager;
        $update->save();

        $notify[] = ['success', 'Customer Update successful!'];
        return redirect()->route('customer.index')->withNotify($notify);
    }

    public function getCustomers(Request $request)
    {
        $search = $request->input('q');

        $customers = Customer::select('id', 'company_name', 'customer_id')
            ->where('customer_id', 'LIKE', "%$search%")
            ->orWhere('company_name', 'LIKE', "%$search%")
            ->get();

        $formatted_customers = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'text' => "{$customer->customer_id} - {$customer->company_name}"
            ];
        });

        return response()->json($formatted_customers);
    }

    //Ajax get company name
    public function getComName($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            return response()->json(['company_name' => $customer->company_name]);
        }

        return response()->json(['company_name' => 'Customer not found']);
    }

    //vendor care list
    public function vendorCareCreate(Request $request)
    {
        $request->validate([
            'site_id' => 'required',
            'technician_id' => 'required',
            'priority' => 'required',
            'fha_rate' => 'required',
        ]);

        // generating unique 5 digit id starting with 8000
        $customer = VendorCareList::orderBy('id', 'desc')->first();
        if ($customer == null) {
            $firstReg = 0;
            $customerId = $firstReg + 1;
            if ($customerId < 10) {
                $id = '8000' . $customerId;
            } elseif ($customerId < 100) {
                $id = '800' . $customerId;
            } elseif ($customerId < 1000) {
                $id = '80' . $customerId;
            } elseif ($customerId < 10000) {
                $id = '8' . $customerId;
            }
        } else {
            $id = $customer->id;
            $customerId = $id + 1;
            if ($customerId < 10) {
                $id = '8000' . $customerId;
            } elseif ($customerId < 100) {
                $id = '800' . $customerId;
            } elseif ($customerId < 1000) {
                $id = '80' . $customerId;
            } elseif ($customerId < 10000) {
                $id = '8' . $customerId;
            }
        }


        $vendorCare = new VendorCareList();

        $vendorCare->order_id = $id;
        $vendorCare->site_id = $request->site_id;
        $vendorCare->technician_id = $request->technician_id;
        $vendorCare->priority = $request->priority;
        $vendorCare->fha_rate = $request->fha_rate;
        $vendorCare->save();

        $notify[] = ['success', 'data store successful!'];
        return back()->withNotify($notify);
    }

    public function vendorCare()
    {
        $pageTitle = "Vendor List Care";

        $vendorCare = VendorCareList::with('site.customer', 'tech')->get();
        $sites = CustomerSite::all();
        $technicians = Technician::all();
        return view('admin.customers.vendor_care', compact('pageTitle', 'vendorCare', 'sites', 'technicians'));
    }

    //customer zone
    public function customerZone($id)
    {
        $pageTitle = "Customer Zone";
        $zone = Customer::find($id);
        $sites = CustomerSite::where('customer_id', $id)->get();
        $workOrders = WorkOrder::where('slug', $id)->get();
        $data = ["sites" => $sites, "workOrders" => $workOrders];

        if (request()->ajax()) {
            return response()->json($data, 200, [], JSON_PRETTY_PRINT)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } else {
            return view('admin.customers.customer_zone', compact('pageTitle', 'zone', 'sites'));
        }
    }

    public function getCustomerSite(Request $request)
    {
        $search = $request->input('q');

        $customerSite = CustomerSite::select('id', 'site_id', 'location')
            ->where('customer_id', $request->id)
            ->where(function ($query) use ($search) {
                $query->where('site_id', 'LIKE', "%$search%")
                    ->orWhere('location', 'LIKE', "%$search%");
            })->get();

        $formatted_customer_site = $customerSite->map(function ($site) {
            return [
                'id' => $site->id,
                'text' => "{$site->site_id} - {$site->location}"
            ];
        });

        return response()->json($formatted_customer_site);
    }

    //begin work order
    public function workOrderAll()
    {
        $pageTitle = "All Work Order List";
        $orderData = $this->orderSearch();
        $workOrders = $orderData['data'];
        return view('admin.customers.all_work_list', compact('pageTitle', 'workOrders'));
    }
    public function orderSearch()
    {
        $workOrders = WorkOrder::with(['site']);
        $workOrders = $workOrders->searchable(['order_id'])->dateFilter();
        return [
            'data' => $workOrders->orderBy('id', 'desc')->paginate(getPaginate()),
        ];
    }
    public function editOrder($id)
    {
        $pageTitle = "Edit Work Order";
        $edit = WorkOrder::with('site', 'customer')->find($id);
        $imageFileNames = json_decode($edit->pictures); // Decode the JSON array.
        return view('admin.customers.work_order_edit', compact('pageTitle', 'edit', 'imageFileNames'));
    }
    public function editPost(Request $request, $id)
    {
        $request->validate([
            'h_operation' => 'required',
            'main_tel' => 'required',
            'site_contact_name' => 'required',
            'site_contact_phone' => 'required',
            'date_schedule' => 'required',
            'e_checkin' => 'required',
            'l_checkin' => 'required',
            'instruction' => 'required',
            'a_instruction' => 'required',
            'r_tools' => 'required',
            'scope_work' => 'required',
            'arrival' => 'required',
            'leaving' => 'required',
        ]);

        $update = WorkOrder::find($id);

        $update->h_operation = $request->h_operation;
        $update->main_tel = $request->main_tel;
        $update->site_contact_name = $request->site_contact_name;
        $update->site_contact_phone = $request->site_contact_phone;
        $update->date_schedule = $request->date_schedule;
        $update->e_checkin = $request->e_checkin;
        $update->l_checkin = $request->l_checkin;
        $update->instruction = $request->instruction;
        $update->a_instruction = $request->a_instruction;
        $update->r_tools = $request->r_tools;
        $update->scope_work = $request->scope_work;
        $update->arrival = $request->arrival;
        $update->leaving = $request->leaving;
        if ($request->hasFile('pictures')) {
            // Retrieve the existing pictures from the database.
            $existingPictures = json_decode($update->pictures, true);

            // Initialize an array to store the new file names.
            $newFileNames = [];

            // Loop through the new pictures and process them.
            foreach ($request->file('pictures') as $pictureFile) {
                $fileNamePicture = $id . '_' . $pictureFile->getClientOriginalName();
                $pictureFile->move(public_path('imgs'), $fileNamePicture);

                // Add the new file name to the array.
                $newFileNames[] = $fileNamePicture;
            }

            // Check if existingPictures is an array and not null.
            if (is_array($existingPictures)) {
                foreach ($existingPictures as $existingPicture) {
                    $pathToDelete = public_path('imgs/' . $existingPicture);
                    if (file_exists($pathToDelete)) {
                        unlink($pathToDelete);
                    }
                }
            }

            // Store the new file names in the database as a JSON string.
            $update->pictures = json_encode($newFileNames);

            // You can choose to store the JSON string in the database or perform other actions as needed.
        }

        if ($request->project_manager && $request->phone && $request->email) {
            $cUpdate = Customer::find($update->slug);
            $cUpdate->project_manager = $request->project_manager;
            $cUpdate->phone = $request->phone;
            $cUpdate->email = $request->email;
            $cUpdate->save();
        }
        if ($request->location && $request->address_1 && $request->address_2 && $request->city && $request->state && $request->zipcode) {
            $sUpdate = CustomerSite::find($update->site_id);
            $sUpdate->location = $request->location;
            $sUpdate->address_1 = $request->address_1;
            $sUpdate->address_2 = $request->address_2;
            $sUpdate->city = $request->city;
            $sUpdate->state = $request->state;
            $sUpdate->zipcode = $request->zipcode;
            $sUpdate->save();
        }
        $update->save();
        $notify[] = ['success', 'Work Order Data Updated successful!'];
        return back()->withNotify($notify);
    }
    public function orderCreate(Request $request)
    {
        $formData = $request->all();
        $notesDataArray = json_decode($formData['notesData'], true);

        $validationRules = [
            'site_id' => 'required',
            'h_operation' => 'required',
            'main_tel' => 'required',
            'site_contact_name' => 'required',
            'site_contact_phone' => 'required',
            'date_schedule' => 'required',
            'e_checkin' => 'required',
            'l_checkin' => 'required',
            'instruction' => 'required',
            'a_instruction' => 'required',
            'r_tools' => 'required',
            'scope_work' => 'required',
            'arrival' => 'required',
            'leaving' => 'required',
            'picture' => 'mimes:jpeg,png,jpg,gif',
            'order_type' => 'required',
        ];

        $message = [
            'site_id.required' => 'Site location is required',
            'h_operation.required' => 'Hours of operation is required',
            'main_tel.required' => 'Main telephone is required',
            'site_contact_name.required' => 'Site contact name is required',
            'site_contact_phone.required' => 'Site contact phone is required',
            'date_schedule.required' => 'Date schedule is required',
            'e_checkin.required' => 'Earliest checkIn is required',
            'l_checkin.required' => 'Leatest checkIn is required',
            'instruction.required' => 'Instruction is required',
            'a_instruction.required' => 'Additional instuction is required',
            'r_tools.required' => 'Required tools is required',
            'scope_work.required' => 'Scope of work is required',
            'arrival.required' => 'Upon arrival on site is required',
            'leaving.required' => 'Before leaving site is required',
            'order_type.required' => 'Order type is required',
        ];

        $validator = Validator::make($formData, $validationRules, $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $orderId = WorkOrder::orderBy('id', 'desc')->first();
        $rand = mt_rand(10, 99);

        if ($orderId == null) {
            $id = 0;
            $f = $id + 1;
        } else {
            $p = $orderId->id;
            $f = $p + 1;
        }
        $date = date('mdy');
        $open_date = date('m/d/y');
        $id = $date . $rand;

        $prefix = "";
        $orderType = "";
        if ($request->order_type === "S") {
            $prefix = "S1";
            $orderType = Status::SERVICE;
        }
        if ($request->order_type === "P") {
            $prefix = "P1";
            $orderType = Status::PROJECT;
        }
        if ($request->order_type === "I") {
            $prefix = "I1";
            $orderType = Status::INSTALL;
        }

        $workOrder = new WorkOrder();
        $workOrder->site_id = $request->site_id;
        $workOrder->open_date = $open_date;
        $workOrder->order_id =  $prefix . $id . $f;
        $workOrder->order_type = $orderType;
        $workOrder->h_operation = $request->h_operation;
        $workOrder->main_tel = $request->main_tel;
        $workOrder->site_contact_name = $request->site_contact_name;
        $workOrder->site_contact_phone = $request->site_contact_phone;
        $workOrder->date_schedule = $request->date_schedule;
        $workOrder->e_checkin = $request->e_checkin;
        $workOrder->l_checkin = $request->l_checkin;
        $workOrder->instruction = $request->instruction;
        $workOrder->a_instruction = $request->a_instruction;
        $workOrder->r_tools = $request->r_tools;
        $workOrder->scope_work = $request->scope_work;
        $workOrder->arrival = $request->arrival;
        $workOrder->leaving = $request->leaving;
        $workOrder->slug = $request->slug;
        $workOrder->status = Status::NEW;

        if ($request->hasFile('pictures')) {
            $pictureFiles = $request->file('pictures');
            $fileNames = [];
            foreach ($pictureFiles as $pictureFile) {
                $fileNamePicture = $id . '_' . $pictureFile->getClientOriginalName();
                $pictureFile->move(public_path('imgs'), $fileNamePicture);
                $fileNames[] = $fileNamePicture;
            }
            $workOrder->pictures = json_encode($fileNames);
        }

        // if ($request->hasFile('documents')) {
        //     $pdfs = $request->file('documents');
        //     $filesArray = [];
        //     foreach ($pdfs as $pdf) {
        //         $fileName = $id . '_' . $pdf->getClientOriginalName();
        //         $pdf->move(public_path('docs'), $fileName);
        //         $filesArray[] = $fileName;
        //     }
        //     $workOrder->documents = json_encode($filesArray);
        // }

        $workOrder->save();
        $invoice = new CustomerInvoice();
        $invoice->invoice_number = getNumber();
        $invoice->work_order_id = $workOrder->id;
        $invoice->save();

        if (auth('admin')->check()) {
            $adminId = auth('admin')->id();
        }

        if ($notesDataArray['GN'] || $notesDataArray['BN'] || $notesDataArray['TSN'] || $notesDataArray['CN'] || $notesDataArray['DN']) {
            $note = new TicketNotes();
            $note->work_order_id = $workOrder->id;
            $note->auth_id = $adminId;
            $note->general_notes = $notesDataArray['GN'];
            $note->billing_notes = $notesDataArray['BN'];
            $note->tech_support_notes = $notesDataArray['TSN'];
            $note->close_out_notes = $notesDataArray['CN'];
            $note->dispatch_notes = $notesDataArray['DN'];
            $note->save();
        }

        return response()->json(['message' => 'Work order created successfully'], 200);
    }
    public function viewOrder($id)
    {
        $pageTitle = "Work Order View";
        $views = WorkOrder::with('site', 'customer', 'technician')->find($id);
        $wps = workOrderPerformed::where('work_order_id', $id)->get();
        $imageFileNames = json_decode($views->pictures);
        $btnOpen = @$views->status == Status::PENDING;
        $btnDispatch = @$views->status == Status::CONTACTED;
        $btnWorkOperation = @$views->status == Status::CONFIRM;
        $btnInvoice = @$views->status == Status::AT_RISK;
        $btnClosed = @$views->status == Status::DELAYED;
        return view('admin.customers.work_order_view', compact('pageTitle', 'views', 'imageFileNames', 'wps', 'btnWorkOperation', 'btnInvoice', 'btnClosed', 'btnDispatch', 'btnOpen'));
    }
    public function deleteOrder($id)
    {
        $delete = WorkOrder::findOrFail($id);
        $customerId = $delete->customer->id;
        $delete->delete();
        $notify[] = ['success', 'Work Order deleted successfully'];
        return redirect()->route('customer.customerZone', ['id' => $customerId])->withNotify($notify);
    }

    public function ajax()
    {
        $skills = SkillCategory::all();
        return response()->json($skills);
    }

    //begin customer invoice 
    public function allInvoice()
    {
        $pageTitle = "Invoice History";
        $invoices = WorkOrder::with('invoice', 'customer')->searchable(['order_id'])->dateFilter()->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.customers.invoices.all_invoice', compact('pageTitle', 'invoices'));
    }
    public function paidInvoice()
    {
        $pageTitle = "Paid Invoices";
        $invoices = WorkOrder::PaidInvoice()->searchable(['order_id'])->dateFilter()->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.customers.invoices.paid_invoice', compact('pageTitle', 'invoices'));
    }
    public function dueInvoice()
    {
        $pageTitle = "Due Invoices";
        $invoices = WorkOrder::DueInvoice()->searchable(['order_id'])->dateFilter()->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.customers.invoices.due_invoice', compact('pageTitle', 'invoices'));
    }

    public function viewInvoice($id)
    {
        $pageTitle = "Customer Invoice";
        $invoice = WorkOrder::with('invoice', 'customer', 'site', 'notes')->find($id);
        $wps = CheckInOut::where('work_order_id', $id)->with('technician')->get();

        // Initialize total price variable
        $totalPrice = 0;

        // Loop through each work process (wp) and calculate the rate
        foreach ($wps as $wp) {
            // Parse the check-in time
            $checkIn = Carbon::parse($wp->updated_at);
            $dayOfWeek = $checkIn->dayOfWeek;

            // Define working hours (9 AM - 6 PM)
            $startStdTime = Carbon::create($checkIn->year, $checkIn->month, $checkIn->day, 9, 0, 0); // 9 AM
            $endStdTime = Carbon::create($checkIn->year, $checkIn->month, $checkIn->day, 18, 0, 0); // 6 PM

            // Get technician's rates
            $stdRate = @$wp->technician->rate['STD'];
            $emRate = @$wp->technician->rate['EM'];
            $shRate = @$wp->technician->rate['SH'];
            $otRate = @$wp->technician->rate['OT'];

            // Determine the rate based on the time and day
            if ($dayOfWeek == 5) { // Friday (Special Hours)
                $wp->calculated_rate = $shRate;
            } elseif ($dayOfWeek == 6 || $dayOfWeek == 0) { // Saturday and Sunday
                $wp->calculated_rate = $otRate;
            } elseif ($checkIn->between($startStdTime, $endStdTime)) { // Standard Hours (9 AM - 6 PM)
                $wp->calculated_rate = $stdRate;
            } else { // Evening hours (before 9 AM or after 6 PM)
                $wp->calculated_rate = $emRate;
            }
            // Safely explode total_hours and handle cases where the format is incorrect
            list($hours, $minutes) = array_pad(explode(':', @$wp->total_hours), 2, 0);

            // Ensure both $hours and $minutes are numeric
            $hours = is_numeric($hours) ? (float)$hours : 0;
            $minutes = is_numeric($minutes) ? (float)$minutes : 0;

            // Convert minutes to a fraction of an hour
            $totalHoursDecimal = $hours + ($minutes / 60);

            // Calculate amount for the work process
            $wp->amount = $wp->calculated_rate * $totalHoursDecimal;

            // Add to total price
            $totalPrice += $wp->amount;
        }

        // Add a fixed amount (e.g., 0.26)
        $totalPrice += 0.26;

        return view('admin.customers.invoices.index', compact('pageTitle', 'invoice', 'wps', 'totalPrice'));
    }


    public function pdfInvoice($id)
    {

        $pageTitle = "Download Pdf";
        $invoice = WorkOrder::with('invoice', 'customer', 'site')->find($id);
        $wps = workOrderPerformed::where('work_order_id', $id)->get();
        $price = $wps->sum('price');
        $totalPrice = $price + 0.26;
        $pdf = PDF::loadView('admin.customers.invoices.pdf', compact('pageTitle', 'invoice', 'wps', 'price', 'totalPrice'))->setOptions(['defaultFont' => 'sans-serif']);
        $pdf->setPaper('A4', 'portrait');
        $customerCompanyName = $invoice->customer->company_name;
        $fileName = $customerCompanyName . '_Invoice.pdf';

        return $pdf->download($fileName);
    }
    public function pdfWorkOrder($id)
    {

        $pageTitle = "Download Work Order";
        $views = WorkOrder::with('site', 'customer')->find($id);
        $wps = workOrderPerformed::where('work_order_id', $id)->get();
        $imageFileNames = json_decode($views->pictures); // Decode the JSON array.
        $pdf = PDF::loadView('admin.customers.work_order_pdf', compact('pageTitle', 'views', 'wps', 'imageFileNames'))->setOptions(['defaultFont' => 'sans-serif']);
        $pdf->setPaper('A4', 'portrait');
        $customerCompanyName = $views->customer->company_name;
        $fileName = $customerCompanyName . '_Work_Order.pdf';

        return $pdf->download($fileName);
    }


    public function workPerform(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'quantity' => 'required',
            'price' => 'required',
            'description' => 'required',
            'work_request' => 'required',
            'work_perform' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $date = date('m/d/y');

        $wp = workOrderPerformed::where('work_order_id', $request->work_order_id)->orderByDesc('created_at')->first();
        if (!empty($wp->date)) {
            $checkDate = $wp->date == $date;
        } else {
            $checkDate = $wp;
        }
        if ($checkDate) {
            // Update an existing record
            $wp->quantity = $request->quantity;
            $wp->price = $request->price;
            $wp->description = $request->description;
            $wp->work_request = $request->work_request;
            $wp->work_perform = $request->work_perform;
            $wp->save();
        } else {
            // Create a new record
            $wp = new workOrderPerformed();
            $wp->work_order_id = $request->work_order_id;
            $wp->quantity = $request->quantity;
            $wp->price = $request->price;
            $wp->description = $request->description;
            $wp->work_request = $request->work_request;
            $wp->work_perform = $request->work_perform;
            $wp->date = $date;
            $wp->save();
        }

        return response()->json(['message' => 'Work Perform ' . ($wp->wasRecentlyCreated ? 'added' : 'updated') . ' successfully'], 200);
    }

    public function multiAssignCoordinateForSite()
    {
        $max_exec_time = ini_get('max_execution_time');
        ini_set('max_execution_time', 300);

        // Fetch the last 10 sites with missing coordinates
        $sites = CustomerSite::whereRaw("ST_X(co_ordinates) IS NULL OR ST_Y(co_ordinates) IS NULL")
            ->latest('id') // Assuming 'id' is an auto-incrementing primary key
            ->take(500)
            ->get(['id', 'address_1', 'location', 'zipcode', 'state', 'city']);

        $address_array = [];

        if ($sites->isNotEmpty()) {
            foreach ($sites as $site) {
                $address = [
                    'location' => $site->location,
                    'address_1' => $site->address_1,
                    'address_2' => $site->address_2 ?? '', 
                    'city' => $site->city,
                    'state' => $site->state,
                    'zipcode' => $site->zipcode,
                    
                ];
                $address_string = implode(", ", $address);
                $address_array[] = [
                    'id' => $site->id,
                    'address' => $address_string
                ];
            }

            $coordinates = $this->geocodingService->geocodeAddresses($address_array);

            if ($coordinates !== null) {
                foreach ($coordinates as $value) {
                    CustomerSite::where('id', $value['id'])->update([
                        'co_ordinates' => DB::raw("ST_GeomFromText('POINT(" . $value['lat'] . " " . $value['lng'] . ")', 4326)"),
                    ]);
                }
                $notify[] = ['success', 'Coordinates added successfully to the last 10 sites'];
            } else {
                $notify[] = ['error', 'Failed to fetch coordinates'];
            }
        } else {
            $notify[] = ['info', 'No sites found with empty coordinates'];
        }

        ini_set('max_execution_time', $max_exec_time);
        return back()->withNotify($notify);
    }
}
