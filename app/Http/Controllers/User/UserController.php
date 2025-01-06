<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Imports\SitesImport;
use App\Services\GeocodingService;
use Geodesy\Distance\VincentyFormula;
use Illuminate\Http\Request;
use App\Lib\GoogleAuthenticator;
use App\Lib\FormProcessor;
use App\Models\Customer;
use App\Models\CustomerSite;
use App\Models\Form;
use App\Models\WorkOrder;
use App\Models\CustomerInvoice;
use App\Models\TicketNotes;
use App\Models\SubTicket;
use App\Models\CheckInOut;
use App\Models\Transaction;
use App\Constants\Status;
use App\CustomClass\DistanceMatrixService;
use App\Imports\CustomersImport;
use App\Imports\UserTechImport;
use App\Models\Inventory;
use App\Models\Review;
use App\Models\SkillCategory;
use App\Models\Technician;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyTestMail;
use App\Mail\MyTestMail_sample;
use App\Models\AssignedEngineer;
use App\Models\Contact;
use App\Models\DocForTechnician;
use App\Models\Employee;
use App\Models\OrderShipment;
use App\Models\Task;
use App\Models\TechProvidedPart;
use App\Models\WorkOrderTimeLog;
use App\Models\PaySheet;
use App\Models\WorkOrderSchedule;
use App\Models\Engineer;
use App\Services\UserService;
use Geodesy\Distance\HaversineFormula;
use Illuminate\Support\Facades\DB;
use Geodesy\Location\LatLong;
use Geodesy\Unit\Mile;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Models\TechDeletionReason;
use App\Models\OtherExpense;
use Illuminate\Support\Facades\Auth; 

class UserController extends Controller
{
    protected $userService;
    protected $geocodingService;

    public function __construct(UserService $userService, GeocodingService $geocodingService)
    {
        $this->userService = $userService;
        $this->geocodingService = $geocodingService;
    }
    public function home()
    {
        $pageTitle = "Tech Yeah User";
        $work = $this->woData();
        return $this->allRecord($pageTitle, $work);
    }

    public function userViewLayout($id)
    {
        $wo = WorkOrder::with([
            'customer',
            'technician',
            'employee',
            'site' => function ($query) {
                $query->select(
                    'id',
                    'location',
                    'address_1',
                    'city',
                    'state',
                    'zipcode',
                    'time_zone',
                    DB::raw('ST_AsText(co_ordinates) as co_ordinates')
                );
            },
            'contacts',
            'docsForTech',
            'checkInOut.engineer',
            'timeLogs',
            'tasks',
            'shipments',
            'techProvidedParts',
            'schedules'
        ])->find($id);

        $pageTitle = $wo->order_id . " | TechBook";
        $customers = Customer::all();
        $employees = Employee::all();
        $customerSites = CustomerSite::all();

        if (!$wo) {
            Log::error("WorkOrder not found with ID: $id");
            abort(404, 'WorkOrder not found.');
        }

        if ($wo->stage != 7) {

            try {
                $currentDateTime = Carbon::now();

                // Loop over all schedules
                foreach ($wo->schedules as $schedule) {
                    // Combine the date and time for the current schedule
                    $scheduleDateTime = Carbon::parse($schedule->on_site_by)
                        ->setTimeFrom(Carbon::parse($schedule->scheduled_time));

                    if ($scheduleDateTime->lte($currentDateTime)) {
                        $checkInOut = $wo->checkInOut
                            ->where('date', '=', $scheduleDateTime->format('m/d/y')) // Match the date
                            ->where('check_in', '<=', $scheduleDateTime->format('H:i:s')) // Compare time
                            ->first();

                        // dd($checkInOut);
                        if (!$checkInOut) {
                            Log::alert("No check-in/out found for schedule on {$scheduleDateTime}. WorkOrder ID: $id.");

                            // Update the status and stage if no check-in/out is found
                            $wo->stage = Status::STAGE_DISPATCH;
                            $wo->status = Status::AT_RISK;
                            $wo->save();

                            Log::info("WorkOrder ID: $id updated for schedule on {$scheduleDateTime} with stage: {$wo->stage} and status: {$wo->status}");
                        } else {
                            Log::info("Check-in/out exists for schedule on {$scheduleDateTime}. Skipping status update for WorkOrder ID: $id.");
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error processing WorkOrder ID: $id - " . $e->getMessage());
            }
        }

        // Handle billing term and past due logic
        $billingTerm = @$wo->customer->billing_term;
        $numericValue = (int)preg_replace('/^NET/', '', $billingTerm);

        $pastDue = DB::table('past_due_check')->where('wo_id', $wo->id)->first();

        if ($pastDue) {
            $daysSinceCreated = now()->diffInDays($pastDue->created_at);
            if ($numericValue < $daysSinceCreated) {
                $wo->status = Status::PAST_DUE;
                $wo->save();

                Log::info("WorkOrder ID: $id status updated to 'PAST_DUE'.");
            }
        }

        // Return the view with the required data
        return view('user.workOrder.wo_view', compact('pageTitle', 'wo', 'customers', 'employees', 'customerSites'));
    }
    

    public function userViewPdf()
    {

        $pageTitle = "All WorkOrder";
        $work = $this->woData();
        return $this->allRecord($pageTitle, $work);
    }
    public function statusPending()
    {
        $pageTitle = "Pending WorkOrder";
        $work = $this->woData('PendingTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusContacted()
    {
        $pageTitle = "Contacted WorkOrder";
        $work = $this->woData('ContactedTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusConfirmed()
    {
        $pageTitle = "Confirmed WorkOrder";
        $work = $this->woData('ConfirmedTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusAtRisk()
    {
        $pageTitle = "At Risk WorkOrder";
        $work = $this->woData('AtRiskTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusDelayed()
    {
        $pageTitle = "Delayed WorkOrder";
        $work = $this->woData('DelayedTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusOnHold()
    {
        $pageTitle = "On Hold WorkOrder";
        $work = $this->woData('OnHoldTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusEnRoute()
    {
        $pageTitle = "En Route WorkOrder";
        $work = $this->woData('EnRouteTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusCheckedIn()
    {
        $pageTitle = "Checked In WorkOrder";
        $work = $this->woData('CheckedInTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusCheckedOut()
    {
        $pageTitle = "Checked Out WorkOrder";
        $work = $this->woData('CheckedOutTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusNeedsApproval()
    {
        $pageTitle = "Needs Approval WorkOrder";
        $work = $this->woData('NeedsApprovalTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusIssue()
    {
        $pageTitle = "Issue WorkOrder";
        $work = $this->woData('IssueTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusApproved()
    {
        $pageTitle = "Approved WorkOrder";
        $work = $this->woData('ApprovedTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusInvoiced()
    {
        $pageTitle = "Invoiced WorkOrder";
        $work = $this->woData('InvoicedTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusPastDue()
    {
        $pageTitle = "Past Due WorkOrder";
        $work = $this->woData('PastDueTicket');
        return $this->allRecord($pageTitle, $work);
    }
    public function statusPaid()
    {
        $pageTitle = "Paid WorkOrder";
        $work = $this->woData('PaidTicket');
        return $this->allRecord($pageTitle, $work);
    }
    //stage
    public function stageNew()
    {
        $pageTitle = "New WorkOrder";
        $work = $this->woData('NewStage');
        return $this->allRecord($pageTitle, $work);
    }
    public function stageNeedDispatch()
    {
        $pageTitle = "Need to Dispatch WorkOrder";
        $work = $this->woData('NeedDispatchStage');
        return $this->allRecord($pageTitle, $work);
    }
    public function stageDispatch()
    {
        $pageTitle = "Dispatched WorkOrder";
        $work = $this->woData('DispatchedStage');
        return $this->allRecord($pageTitle, $work);
    }
    public function stageClosed()
    {
        $pageTitle = "Complete WorkOrder";
        $work = $this->woData('ClosedStage');
        return $this->allRecord($pageTitle, $work);
    }
    public function stageBilling()
    {
        $pageTitle = "Complete WorkOrder";
        $work = $this->woData('BillingStage');
        return $this->allRecord($pageTitle, $work);
    }

    private function allRecord($pageTitle, $work)
    {
        return view('user.workOrder.list_pdf_view', compact('pageTitle', 'work'));
    }
    public function woData($scope = null)
    {
        if ($scope) {
            $w = WorkOrder::$scope();
        } else {
            $w = WorkOrder::query();
        }
        return $w->latest()->with('customer', 'site')
            ->searchable([
                'order_id',
                'customer:company_name',
                'customer:address->zip_code',
                'site:zipcode',
                'site:location',
                'site:address_1',
            ])->orderBy('id', 'desc')->paginate(getPaginate());
    }
    public function service(Request $request)
    {
        $orderId = WorkOrder::orderBy('id', 'desc')->first();
        $rand = rand(10, 99);

        if ($orderId == null) {
            $id = 0;
            $f = $id + 1;
        } else {
            $p = $orderId->id;
            $f = $p + 1;
        }
        $date = date('mdy');
        $id = $date . $rand;
        $service = new WorkOrder();
        $service->order_id = "S1" . $id . $f;
        $service->open_date = date('m/d/y');
        $service->order_type = Status::SERVICE;
        $service->status = Status::PENDING;
        $service->stage = Status::STAGE_NEW;
        $service->em_id = $request->em_id;
        $service->site_id = $request->site_id;
        $service->slug = $request->customer_id;
        $service->requested_by = $request->requested_by;
        $service->request_type = $request->request_type;
        $service->priority = $request->priority;
        $service->complete_by = $request->complete_by;
        $service->p_o = $request->p_o;
        $service->status = $request->status;
        $service->num_tech_required = $request->num_tech_required;
        $service->scope_work = $request->scope_work;
        $service->deliverables = $request->deliverables;
        $service->r_tools = $request->r_tools;
        $service->instruction = $request->instruction;
        $service->site_contact_name = $request->site_contact_name;
        $service->site_contact_phone = $request->site_contact_phone;
        $service->h_operation = $request->h_operation;
        $service->on_site_by = $request->on_site_by;
        $service->scheduled_time = $request->scheduled_time;
        $service->save();
        $invoice = new CustomerInvoice();
        $invoice->invoice_number = getNumber();
        $invoice->work_order_id = $service->id;
        $invoice->save();
        $response = [
            'message' => 'Service work order created successfully without data',
            'id' => $service->id,
        ];
        return response()->json($response);
    }
    public function project(Request $request)
    {
        $orderId = WorkOrder::orderBy('id', 'desc')->first();
        $rand = rand(10, 99);

        if ($orderId == null) {
            $id = 0;
            $f = $id + 1;
        } else {
            $p = $orderId->id;
            $f = $p + 1;
        }

        $date = date('mdy');
        $id = $date . $rand;
        $project = new WorkOrder();
        $project->order_id = "P1" . $id . $f;
        $project->open_date = date('m/d/y');
        $project->order_type = Status::PROJECT;
        $project->status = Status::PENDING;
        $project->stage = Status::STAGE_NEW;
        $project->em_id = $request->em_id;
        $project->site_id = $request->site_id;
        $project->slug = $request->customer_id;
        $project->requested_by = $request->requested_by;
        $project->request_type = $request->request_type;
        $project->priority = $request->priority;
        $project->complete_by = $request->complete_by;
        $project->p_o = $request->p_o;
        $project->status = $request->status;
        $project->num_tech_required = $request->num_tech_required;
        $project->scope_work = $request->scope_work;
        $project->deliverables = $request->deliverables;
        $project->r_tools = $request->r_tools;
        $project->instruction = $request->instruction;
        $project->site_contact_name = $request->site_contact_name;
        $project->site_contact_phone = $request->site_contact_phone;
        $project->h_operation = $request->h_operation;
        $project->on_site_by = $request->on_site_by;
        $project->scheduled_time = $request->scheduled_time;
        $project->save();
        $invoice = new CustomerInvoice();
        $invoice->invoice_number = getNumber();
        $invoice->work_order_id = $project->id;
        $invoice->save();
        $response = [
            'message' => 'Project work order created successfully without data',
            'id' => $project->id,
        ];
        return response()->json($response);
    }
    public function install(Request $request)
    {
        $orderId = WorkOrder::orderBy('id', 'desc')->first();
        $rand = rand(10, 99);

        if ($orderId == null) {
            $id = 0;
            $f = $id + 1;
        } else {
            $p = $orderId->id;
            $f = $p + 1;
        }
        $date = date('mdy');
        $id = $date . $rand;
        $install = new WorkOrder();
        $install->order_id = "I1" . $id . $f;
        $install->open_date = date('m/d/y');
        $install->order_type = Status::INSTALL;
        $install->status = Status::PENDING;
        $install->stage = Status::STAGE_NEW;
        $install->em_id = $request->em_id;
        $install->site_id = $request->site_id;
        $install->slug = $request->customer_id;
        $install->requested_by = $request->requested_by;
        $install->request_type = $request->request_type;
        $install->priority = $request->priority;
        $install->complete_by = $request->complete_by;
        $install->p_o = $request->p_o;
        $install->status = $request->status;
        $install->num_tech_required = $request->num_tech_required;
        $install->scope_work = $request->scope_work;
        $install->deliverables = $request->deliverables;
        $install->r_tools = $request->r_tools;
        $install->instruction = $request->instruction;
        $install->site_contact_name = $request->site_contact_name;
        $install->site_contact_phone = $request->site_contact_phone;
        $install->h_operation = $request->h_operation;
        $install->on_site_by = $request->on_site_by;
        $install->scheduled_time = $request->scheduled_time;
        $install->save();
        $invoice = new CustomerInvoice();
        $invoice->invoice_number = getNumber();
        $invoice->work_order_id = $install->id;
        $invoice->save();
        $response = [
            'message' => 'Install work order created successfully without data',
            'id' => $install->id,
        ];
        return response()->json($response);
    }

    public function fieldPopulator(Request $request)
    {
        $workOrder = WorkOrder::findOrFail($request->id);
        return response()->json($workOrder);
    }

    public function updateWorkOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'workOrderId' => 'required|exists:work_orders,id',
                'customer_id' => 'required|exists:customers,id',
                'em_id' => 'required|exists:employees,id',
                'site_id' => 'required|exists:customer_sites,id',
                'pictures.*' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // 2048 KB = 2 MB
            ], [
                'pictures.*.max' => 'File size must be less than 2MB',
                'pictures.*.mimes' => 'Only jpeg, png, jpg, and gif files are allowed',
            ]);

            if ($validator->fails()) {
                return response(['errors' => $validator->errors()], 422);
            }

            $update = WorkOrder::find($request->workOrderId);
            $update->em_id = $request->em_id;
            $update->priority = $request->priority;
            $update->open_date = $request->open_date;
            $update->requested_by = $request->requested_by;
            $update->request_type = $request->request_type;
            $update->complete_by = $request->complete_by;
            $update->status = $request->status;
            $update->stage = $request->stage;
            $update->slug = $request->customer_id;
            $update->site_id = $request->site_id;
            $update->scope_work = $request->scope_work;
            $update->num_tech_required = $request->num_tech_required;
            $update->on_site_by = $request->on_site_by;
            $update->scheduled_time = $request->scheduled_time;
            $update->site_contact_name = $request->site_contact_name;
            $update->site_contact_phone = $request->site_contact_phone;
            $update->h_operation = $request->h_operation;
            $update->r_tools = $request->r_tools;
            $update->instruction = $request->instruction;
            $update->deliverables = $request->deliverables;
            $update->p_o = $request->p_o;
            if ($request->hasFile('pictures')) {
                $pictureFiles = $request->file('pictures');
                $fileNames = [];
                $existingPictures = json_decode($update->pictures, true) ?? [];
                foreach ($pictureFiles as $pictureFile) {
                    $fileNamePicture = $request->workOrderId . '_' . $pictureFile->getClientOriginalName();
                    $pictureFile->move(public_path('imgs'), $fileNamePicture);
                    $fileNames[] = $fileNamePicture;
                }
                $allPictures = array_merge($existingPictures, $fileNames);
                $update->pictures = json_encode($allPictures);
            }

            $update->save();

            if ($request->general_notes || $request->billing_notes || $request->tech_support_notes || $request->close_out_notes || $request->dispatch_notes) {
                $note = new TicketNotes();
                $note->work_order_id = $request->workOrderId;
                $note->auth_id = auth()->id();
                $note->general_notes = $request->general_notes;
                $note->billing_notes = $request->billing_notes;
                $note->tech_support_notes = $request->tech_support_notes;
                $note->close_out_notes = $request->close_out_notes;
                $note->dispatch_notes = $request->dispatch_notes;
                $note->save();
            }

            $response = [
                'message' => 'Work order updated successfully',
                'id' => $request->workOrderId
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            $response = [
                'errors' => 'A fatal error occured while we processing your request.'
            ];
            return response()->json($response, 500);
        }
    }
    public function subTicket(Request $request)
    {
        try {
            $subTicket = new SubTicket();
            $subTicket->work_order_id = $request->work_order_id;
            $subTicket->auth_id = auth()->id();
            $subTicket->tech_support_note = $request->tech_support_note;
            $subTicket->save();

            $response = [
                'message' => 'Sub ticket created successfully',
                'id' => $request->work_order_id
            ];
            return response()->json($response);
        } catch (QueryException $e) {
            $errorMessage = 'Error creating sub ticket. Please try again.';
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    public function checkIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tech_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->work_order_id) {
            $company = WorkOrder::where('id', $request->work_order_id)->with('technician')->first();
            $companyName = $company->technician->company_name;
            $techId = $company->technician->id;
        } else {
            return response()->json(['techNotFound' => 'Technician not found. Please assign a technician first.'], 404);
        }

        $currentTime = Carbon::now()->toTimeString();

        $existingCheckIn = CheckInOut::where('work_order_id', $request->work_order_id)
            ->where('tech_name', $request->tech_name)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($existingCheckIn) {
            $response = [
                'errors' => 'This technician has already checked in within the past 24 hours',
            ];
            return response()->json($response, 400);
        }

        $checkIn = new CheckInOut();
        $checkIn->work_order_id = $request->work_order_id;
        $checkIn->tech_id = $techId;
        $checkIn->time_zone = $request->time_zone;
        $checkIn->date = $request->date;
        $checkIn->tech_name = $request->tech_name;
        $checkIn->description = $request->description;
        $checkIn->company_name = $companyName;
        $checkIn->check_in = $request->check_in;
        $checkIn->save();

        $workOrder = WorkOrder::find($request->work_order_id);
        $workOrder->status = Status::CHECKED_IN;
        $workOrder->save();

        $response = [
            'message' => 'Check In successfully',
            'id' => $request->work_order_id
        ];

        return response()->json($response);
    }

    public function initiateCheckOut($id)
    {
        $type = request()->get('type', 'complete');
        $checkOutTime = date('H:i:s');
        $CheckIn = CheckInOut::find($id);
        if ($CheckIn) {
            $lastCheckIn = $CheckIn
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->first();
            if ($lastCheckIn) {
                $lastCheckIn->check_out = $checkOutTime;
                $checkInTime = Carbon::createFromFormat('H:i:s', $lastCheckIn->check_in);
                $checkOutTime = Carbon::createFromFormat('H:i:s', $checkOutTime);
                $totalMinutes  = $checkInTime->diffInMinutes($checkOutTime);
                $totalHours = floor($totalMinutes / 60);
                $remainingMinutes = $totalMinutes % 60;
                $lastCheckIn->total_hours = $totalHours . ':' . $remainingMinutes;
                $lastCheckIn->save();

                $workOrder = WorkOrder::find($lastCheckIn->work_order_id);
                $workOrder->status = ($type === 'round_trip') ? Status::CHECKED_IN : Status::CHECKED_OUT;
                $workOrder->save();
                if ($workOrder->status == Status::CHECKED_OUT) {
                    $workOrder->stage = Status::STAGE_CLOSED;
                    $workOrder->save();
                }

                $response = [
                    'message' => ucfirst($type) . ' Check Out successfully for ' . $lastCheckIn->tech_name,
                    'id' => $lastCheckIn->work_order_id,
                    'total_hours' => $lastCheckIn->total_hours,
                ];
            } else {
                $response = [
                    'message' => 'checked out already',
                    'id' => $CheckIn->work_order_id,
                ];
            }
        }

        return response()->json($response);
    }

    public function checkOutEdit(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'tech_name' => 'required|string',
            'check_in' => 'required|date_format:H:i:s',
        ]);

        $checkInOut = CheckInOut::findOrFail($id);

        $checkInOut->tech_name = $request->tech_name;
        $checkInOut->date = $request->date;
        $checkInOut->check_in = $request->check_in;
        $checkInOut->check_out = $request->check_out;
        $checkInTime = Carbon::createFromFormat('H:i:s', $request->check_in);
        $checkOutTime = Carbon::createFromFormat('H:i:s', $request->check_out);
        $totalMinutes  = $checkInTime->diffInMinutes($checkOutTime);
        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;
        $checkInOut->total_hours = $totalHours . ':' . $remainingMinutes;
        $checkInOut->save();
        return response()->json(['message' => 'Check-in/out record updated successfully'], 200);
    }

    public function checkOutDelete($id)
    {
        $delete = CheckInOut::find($id);
        $delete->delete();
        return response()->json(['message' => 'Check-in/out record deleted successfully'], 200);
    }


    public function orderIdsiteHistory($id)
    {
        $grab = WorkOrder::with('technician')->findOrFail($id);
        if ($grab->technician === null) {
            $errors = [
                'tech_error' => 'Currently no tech is assigned to this order.',
                'site_error' => 'No site history found.',

            ];
            return response()->json($errors, 404);
        }
        $siteId = $grab->site_id;
        $site = CustomerSite::with('customer', 'workOrder.technician')->where('id', $siteId)->first();
        $i = $site->workOrder->status == Status::CONTACTED;
        $siteIdMain = [
            'company_name' => @$site->customer->company_name,
            'location' => $site->location,
            'address_1' => $site->address_1,
            'city' => $site->city,
            'state' => $site->state,
            'zipcode' => $site->zipcode,
            'time_zone' => $site->time_zone,
            'num_tech_required' => $grab->num_tech_required,
            'site_contact_name' => $grab->site_contact_name,
            'site_contact_phone' => $grab->site_contact_phone,
            'r_tools' => $grab->r_tools,
            'fcompany_name' => @$grab->technician->company_name,
            'technician_id' => @$grab->technician->technician_id,
            'ftech_email' => @$grab->technician->email,
            'ftech_address' => @$grab->technician->address_data->address,
            'ftech_country' => @$grab->technician->address_data->country,
            'ftech_city' => @$grab->technician->address_data->city,
            'ftech_state' => @$grab->technician->address_data->state,
            'ftech_zipcode' => @$grab->technician->address_data->zip_code,
            'w_id' => @$grab->id,
            'wT' => @$site->workOrder->where('site_id', $siteId)->count(),
            'wC' => @$site->workOrder->where('site_id', $siteId)->where('status', $i)->count(),
        ];

        $response = [
            'result' => $siteIdMain,
            'tech_message' => "",
            'site_message' => "",
        ];

        return response()->json($response, 200);
    }


    public function Onsite()
    {
        if (request()->ajax()) {
            $query = WorkOrder::OnsiteTicket()->select('id', 'order_id');
            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
        $pageTitle = "Onsite Ticket";
        $details = WorkOrder::OnsiteTicket()->get();
        return response()->json(['pageTitle' => $pageTitle, 'details' => $details]);
    }
    public function ticketUpdate(Request $request)
    {
        $requestData = $request->all();
        $rules = [
            'id' => 'required',
            'a_instruction' => 'required'
        ];

        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $workOrder = WorkOrder::findOrFail($requestData['id']);
            $workOrder->a_instruction = $requestData['a_instruction'];
            $workOrder->save();
            return response()->json(['success' => 'Ticket updated successfully.'], 200);
        }
    }

    public function detailsOrder($orderId)
    {
        $id = WorkOrder::where('order_id', $orderId)->first();
        $id = $id->id;
        $view = WorkOrder::with('site', 'customer', 'technician')->find($id);
        return response()->json(['view' => @$view]);
    }
    // public function depositHistory(Request $request)
    // {
    //     $pageTitle = 'Deposit History';
    //     $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
    //     return view('user.deposit_history', compact('pageTitle', 'deposits'));
    // }

    public function userData()
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }
        $pageTitle = "User Data";
        return view('user.user_data', compact('pageTitle', 'user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country' => @$user->address->country,
            'address' => $request->address,
            'state' => @$request->state,
            'zip' => $request->zip,
            'city' => $request->city,
        ];
        $user->profile_complete = 1;
        $user->save();
        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function show2faForm()
    {
        $general = gs();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view('user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());

        return view('user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }


    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'kyc')->first();
        return view('user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view('user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'kyc')->first();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);
        $user = auth()->user();
        $user->kyc_data = $userData;
        $user->kv = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general = gs();
        $title = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function autoComplete(Request $request)
    {
        $query = $request->input('query');
        $results = Customer::select('id', 'customer_id', 'company_name')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('company_name', 'like', '%' . $query . '%')
                    ->orWhere('customer_id', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get();

        return response()->json(['results' => $results], 200);
    }

    public function getCustomer(Request $request)
    {
        $customer = Customer::findOrfail($request->id);
        $data = [
            'address' => $customer->address->address,
            'city' => $customer->address->city,
            'state' => $customer->address->state,
            'zipcode' => $customer->address->zip_code,
            'phone' => $customer->phone,
            'email' => $customer->email,
            'project_manager' => $customer->project_manager,
            'sales_person' => $customer->sales_person,
        ];
        return response()->json($data, 200);
    }

    public function storeSite(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'site_id' => 'required|regex:/^[A-Za-z0-9]{4,12}$/|unique:customer_sites,site_id',
            'location' => 'required',
            'address_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required|digits:5',
            'time_zone' => 'required',
        ], [
            'site_id.regex' => 'The site ID must be between 4 and 12 alphanumeric characters.',
            'site_id.unique' => 'The site ID has already been taken.',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->onlyInput('site_id'); 
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
    
            return back()->with('success', 'Site added successfully'); 
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()->back()->withErrors(['site_id' => 'A site with this Id already exists.'])->onlyInput('site_id'); 
            }
            return redirect()->back()->withErrors(['database' => 'An unexpected database error occurred.'])->onlyInput('site_id'); 
        }
    }

    public function getSite(Request $request)
    {
        $site = CustomerSite::with('customer')->findOrFail($request->id);
        $response = [
            'address' => $site->address_1,
            'city' => $site->city,
            'state' => $site->state,
            'zipcode' => $site->zipcode,
            'customer' => $site->customer->company_name,
            'time_zone' => $site->time_zone,
            'description' => $site->description,
            'created_at' => "Site created : " . $site->created_at->diffForHumans(),
        ];
        return response()->json(['result' => $response], 200);
    }

    public function siteAutoComplete(Request $request)
    {
        $query = $request->input('query');
        $customerId = $request->input('id');
        $results = CustomerSite::select('id', 'site_id', 'location', 'customer_id', 'zipcode')
            ->where('customer_id', $customerId)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('site_id', 'like', '%' . $query . '%')
                    ->orWhere('zipcode', 'like', '%' . $query . '%')
                    ->orWhere('location', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get();

        if (!$customerId) {
            return response()->json(['errors' => 'Please select a customer first.'], 422);
        }

        return response()->json(['results' => $results], 200);
    }

    public function siteModalAutoComplete(Request $request)
    {
        $query = $request->input('query');
        $results = CustomerSite::select('id', 'site_id', 'location', 'zipcode', 'customer_id')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('site_id', 'like', '%' . $query . '%')
                    ->orWhere('zipcode', 'like', '%' . $query . '%')
                    ->orWhere('location', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get();

        return response()->json(['results' => $results], 200);
    }

    public function customerAutoComplete(Request $request)
    {
        $query = $request->input('query');
        $results = Customer::select('id', 'customer_id', 'address', 'company_name')
            ->where('customer_id', 'like', '%' . $query . '%')
            ->orWhere('address->zip_code', 'like', '%' . $query . '%')
            ->orWhere('company_name', 'like', '%' . $query . '%')
            ->limit(10)
            ->get();

        if ($results->isEmpty()) {
            return response()->json(['error' => 'No results found'], 404);
        }

        return response()->json(['results' => $results], 200);
    }

    public function siteImport(Request $request)
    {
        $excelFile = $request->all();
        // Adjusting rules to check only extensions (relying on extensions due to MIME type misclassifications)
        $rules = [
            'site_excel_file' => 'required|max:5120', // file extension check
            'customer_id' => 'required',
        ];
        $messages = [
            'customer_id.required' => 'Please select a customer.',
            'site_excel_file.required' => 'Please select an excel file.',
            'site_excel_file.mimes' => 'Please select a file with csv, xls, or xlsx extension.',
        ];

        $validator = Validator::make($excelFile, $rules, $messages);

        if ($validator->fails()) {
            // return response()->json(['errors' => $validator->errors()], 422);
            return redirect()->back()->withErrors($validator->errors());
        } else {
            // Import the file
            Excel::import(new SitesImport($request->customer_id), $request->file('site_excel_file'), 'csv');
            return response()->json(['success' => 'Your file was successfully imported.'], 200);
        }
    }


    public function sampleSiteExcel()
    {
        $filePath = storage_path('app/files/site_import.csv');
        return response()->download($filePath);
    }

    public function getWorkOrderSearch(Request $request)
    {
        $query = $request->input('query');

        $results = WorkOrder::leftJoin('customer_sites', 'work_orders.site_id', '=', 'customer_sites.id')
            ->leftJoin('customers', 'work_orders.slug', '=', 'customers.id')
            ->select('work_orders.id', 'work_orders.order_id', 'work_orders.site_id', 'work_orders.slug', 'customers.company_name')
            ->where('work_orders.order_id', 'like', '%' . $query . '%')
            ->orWhere('customer_sites.zipcode', 'like', '%' . $query . '%')
            ->orWhere('customers.company_name', 'like', '%' . $query . '%')
            ->limit(10)
            ->get();

        return response()->json(['results' => $results], 200);
    }

    public function getWorkOrderData(Request $request)
    {
        $workOrder = WorkOrder::with('customer', 'site')->findOrfail($request->id);
        $dataArray = [
            'customer_id' => @$workOrder->customer->customer_id,
            'customer_address' => @$workOrder->customer->address->address,
            'customer_city' => @$workOrder->customer->address->city,
            'customer_state' => @$workOrder->customer->address->state,
            'customer_zipcode' => @$workOrder->customer->address->zip_code,
            'customer_phone' => @$workOrder->customer->phone,
            'scope_work' => $workOrder->scope_work,
            'tools_required' => $workOrder->r_tools,
            'deliverables' => $workOrder->deliverables,
            'instruction' => $workOrder->instruction,
            'customer_site_id' => @$workOrder->site->site_id,
            'customer_site_address' => @$workOrder->site->address_1,
            'customer_site_city' => @$workOrder->site->city,
            'customer_site_state' => @$workOrder->site->state,
            'customer_site_zipcode' => @$workOrder->site->zipcode,
            'site_contact' => $workOrder->site_contact_name,
            'site_contact_phone' => $workOrder->site_contact_phone,
            'site_hours_operation' => $workOrder->h_operation,
            'onsite_by' => $workOrder->on_site_by,
            'scheduled_time' => $workOrder->scheduled_time,
            'number_of_tech' => $workOrder->num_tech_required,
            'requested_date' => $workOrder->open_date,
            'requested_by' => $workOrder->requested_by,
            'completed_by' => $workOrder->complete_by,
            'p_o' => $workOrder->p_o,
            'request_type' => $workOrder->request_type,
            'status' => $workOrder->status,
            'priority' => $workOrder->priority,
            'project_manager' => @$workOrder->employee->name,
            'employee_id' => $workOrder->em_id,
            'sales_person' => @$workOrder->customer->sales_person,
            'customerId' => $workOrder->slug,
            'siteId' => $workOrder->site_id,
            'stage' => $workOrder->stage,
            'wo_id' => $workOrder->id,
        ];
        return response()->json(['result' => $dataArray], 200);
    }

    public function generalNotes(Request $request)
    {
        $generalNotes = TicketNotes::with('userData:id,firstname,lastname')
            ->select('id', 'general_notes', 'auth_id', 'created_at', 'updated_at')
            ->where('work_order_id', $request->id)
            ->whereNotNull('general_notes')
            ->get();

        $generalNotes = $generalNotes->map(function ($note) {
            $note->general_notes = strip_tags($note->general_notes);
            return $note;
        });

        return DataTables::of($generalNotes)
            ->addIndexColumn()
            ->addColumn('formatted_created_at', function ($note) {
                return Carbon::parse($note->created_at)->format('Y-m-d h:i:s A');
            })
            ->addColumn('formatted_updated_at', function ($note) {
                return Carbon::parse($note->updated_at)->format('Y-m-d h:i:s A');
            })
            ->rawColumns(['formatted_created_at', 'formatted_updated_at'])
            ->make(true);
    }

    public function dispatchNotes(Request $request)
    {
        $dispatchNotes = TicketNotes::with('userData:id,firstname,lastname')
            ->select('id', 'dispatch_notes', 'auth_id', 'created_at', 'updated_at')
            ->where('work_order_id', $request->id)
            ->whereNotNull('dispatch_notes')
            ->get();

        $dispatchNotes = $dispatchNotes->map(function ($note) {
            $note->dispatch_notes = strip_tags($note->dispatch_notes);
            return $note;
        });

        return DataTables::of($dispatchNotes)
            ->addIndexColumn()
            ->addColumn('formatted_created_at', function ($note) {
                return Carbon::parse($note->created_at)->format('Y-m-d h:i:s A');
            })
            ->addColumn('formatted_updated_at', function ($note) {
                return Carbon::parse($note->updated_at)->format('Y-m-d h:i:s A');
            })
            ->rawColumns(['formatted_created_at', 'formatted_updated_at'])
            ->make(true);
    }

    public function billingNotes(Request $request)
    {
        $billinghNotes = TicketNotes::with('userData:id,firstname,lastname')
            ->select('id', 'billing_notes', 'auth_id', 'created_at', 'updated_at')
            ->where('work_order_id', $request->id)
            ->whereNotNull('billing_notes')
            ->get();

        $billinghNotes = $billinghNotes->map(function ($note) {
            $note->billing_notes = strip_tags($note->billing_notes);
            return $note;
        });

        return DataTables::of($billinghNotes)
            ->addIndexColumn()
            ->addColumn('formatted_created_at', function ($note) {
                return Carbon::parse($note->created_at)->format('Y-m-d h:i:s A');
            })
            ->addColumn('formatted_updated_at', function ($note) {
                return Carbon::parse($note->updated_at)->format('Y-m-d h:i:s A');
            })
            ->rawColumns(['formatted_created_at', 'formatted_updated_at'])
            ->make(true);
    }

    public function techSupportNotes(Request $request)
    {
        $techSupportNotes = TicketNotes::with('userData:id,firstname,lastname')
            ->select('id', 'tech_support_notes', 'auth_id', 'created_at', 'updated_at')
            ->where('work_order_id', $request->id)
            ->whereNotNull('tech_support_notes')
            ->get();

        $techSupportNotes = $techSupportNotes->map(function ($note) {
            $note->tech_support_notes = strip_tags($note->tech_support_notes);
            return $note;
        });

        return DataTables::of($techSupportNotes)
            ->addIndexColumn()
            ->addColumn('formatted_created_at', function ($note) {
                return Carbon::parse($note->created_at)->format('Y-m-d h:i:s A');
            })
            ->addColumn('formatted_updated_at', function ($note) {
                return Carbon::parse($note->updated_at)->format('Y-m-d h:i:s A');
            })
            ->rawColumns(['formatted_created_at', 'formatted_updated_at'])
            ->make(true);
    }

    public function closeoutNotes(Request $request)
    {
        $closeoutNotes = TicketNotes::with('userData:id,firstname,lastname')
            ->select('id', 'close_out_notes', 'auth_id', 'created_at', 'updated_at')
            ->where('work_order_id', $request->id)
            ->whereNotNull('close_out_notes')
            ->get();

        $closeoutNotes = $closeoutNotes->map(function ($note) {
            $note->close_out_notes = strip_tags($note->close_out_notes);
            return $note;
        });

        return DataTables::of($closeoutNotes)
            ->addIndexColumn()
            ->addColumn('formatted_created_at', function ($note) {
                return Carbon::parse($note->created_at)->format('Y-m-d h:i:s A');
            })
            ->addColumn('formatted_updated_at', function ($note) {
                return Carbon::parse($note->updated_at)->format('Y-m-d h:i:s A');
            })
            ->rawColumns(['formatted_created_at', 'formatted_updated_at'])
            ->make(true);
    }
    public function workOrderSubTicket(Request $request)
    {
        $subTicket = SubTicket::with('userData:id,firstname,lastname')->select('id', 'tech_support_note', 'auth_id', 'created_at', 'updated_at')->where('work_order_id', $request->id)->whereNotNull('tech_support_note');
        return DataTables::of($subTicket)
            ->addIndexColumn()
            ->addColumn('formatted_created_at', function ($note) {
                return Carbon::parse($note->created_at)->format('Y-m-d h:i:s A');
            })
            ->addColumn('formatted_updated_at', function ($note) {
                return Carbon::parse($note->updated_at)->format('Y-m-d h:i:s A');
            })
            ->rawColumns(['formatted_created_at', 'formatted_updated_at'])
            ->make(true);
    }
    public function checkInOutTable(Request $request)
    {

        $checkInOut = CheckInOut::latest('id', 'date', 'company_name', 'tech_name', 'check_in', 'check_out', 'total_hours', 'time_zone')->where('work_order_id', $request->id);

        return DataTables::of($checkInOut)
            ->addIndexColumn()
            ->make(true);
    }

    //customer parts details
    public function customerParts(Request $request)
    {
        $workOrder = WorkOrder::select('id', 'slug')->findOrFail($request->work_order_id);
        $customerinventory = Inventory::with('customer:id,customer_id,company_name')->where('customer_id', $workOrder->slug)->first();
        $inventoryItem = Inventory::select('item_name')->where('customer_id', $workOrder->slug)->count();
        $inventoryItemTotal = Inventory::where('customer_id', $workOrder->slug)->sum('quantity');

        $response = [
            'customerinventory' => $customerinventory,
            'inventoryItem' => $inventoryItem,
            'inventoryItemTotal' => $inventoryItemTotal,
        ];

        return response()->json($response, 200);
    }

    public function inventoryAutoComplete(Request $request)
    {
        $query = $request->input('query');
        $results = Inventory::select('id', 'part_number', 'ty_part_number')
            ->where('customer_id', $request->customer_id)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('part_number', 'like', '%' . $query . '%')
                    ->orWhere('ty_part_number', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get();

        return response()->json(['results' => $results], 200);
    }

    public function inventoryItem(Request $request)
    {
        $inventory = Inventory::where('id', $request->id)->first();
        return response()->json($inventory);
    }

    public function inventoryCalculation(Request $request)
    {
        $item = Inventory::find($request->item_id);
        $total_price = '$' . (int)$item->raw_unit_cost * (int)$request->item_value;
        return response()->json($total_price);
    }

    public function skills()
    {
        $skillsets = SkillCategory::all();
        if ($skillsets) {
            return response()->json($skillsets);
        }
    }

    public function newSkill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skill_name' => 'required|unique:skill_categories|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator->errors());
        }

        $skill_category = new SkillCategory();
        $skill_category->skill_name = $request->skill_name;
        $skill_category->save();

        return redirect()->back();
    }

    public function newTech(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|max:100',
            'address' => 'nullable|max:100',
            'city' => 'nullable|max:100',
            'state' => 'nullable|max:100',
            'zip_code' => 'nullable|max:5',
            'email' => 'nullable|email',
            'rate' => 'nullable|numeric',
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

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator->errors());
        }

        // generating unique 5 digit id starting with 5000
        $fTech = Technician::latest('id')->first();
        if ($fTech == null) {
            $firstReg = 0;
            $fTechId = $firstReg + 1;
            if ($fTechId < 10) {
                $id = '5000' . $fTechId;
            } elseif ($fTechId < 100) {
                $id = '500' . $fTechId;
            } elseif ($fTechId < 1000) {
                $id = '50' . $fTechId;
            } elseif ($fTechId < 10000) {
                $id = '5' . $fTechId;
            } elseif ($fTechId < 100000) {
                $id = '5' . $fTechId;
            }
        } else {
            $id = $fTech->id;
            $fTechId = $id + 1;
            if ($fTechId < 10) {
                $id = '5000' . $fTechId;
            } elseif ($fTechId < 100) {
                $id = '500' . $fTechId;
            } elseif ($fTechId < 1000) {
                $id = '50' . $fTechId;
            } elseif ($fTechId < 10000) {
                $id = '5' . $fTechId;
            } elseif ($fTechId < 100000) {
                $id = '5' . $fTechId;
            }
        }

        $addressData = [
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code
        ];



        $skillsIds = $request->skill_id;
        $technician = new Technician();

        $technician->company_name = $request->company_name;
        $technician->address_data = $addressData;
        $technician->email = $request->email;
        $technician->primary_contact_email = $request->primary_contact_email;
        $technician->phone = $request->phone;
        $technician->primary_contact = $request->primary_contact;
        $technician->title = $request->title;
        $technician->cell_phone = $request->cell_phone;
        $technician->rate = [
            'STD' => $request->std_rate,
            'EM' => $request->em_rate,
            'OT' => $request->ot_rate,
            'SH' => $request->sh_rate
        ];;
        $technician->radius = $request->radius;
        $technician->travel_fee = $request->travel_fee;
        $technician->status = $request->status;
        $technician->coi_expire_date = $request->coi_expire_date;
        $technician->msa_expire_date = $request->msa_expire_date;
        $technician->nda = $request->nda;
        $technician->terms = $request->terms;
        $technician->preference = $request->preference;
        $technician->c_wo_ct = $request->c_wo_ct;
        $technician->source = $request->source;
        $technician->notes = $request->notes;
        $technician->technician_id = $id;

        //pdf store here
        if ($request->coi_file) {
            $pdfFile = $request->file('coi_file');
            $pdfFileNameCoi = $id . '_' . $pdfFile->getClientOriginalName();
            $pdfFile->storeAs('pdfs', $pdfFileNameCoi, 'public');
            $technician->coi_file = $pdfFileNameCoi;
        }
        if ($request->msa_file) {
            $pdfFile = $request->file('msa_file');
            $pdfFileNameMsa = $id . '_' . $pdfFile->getClientOriginalName();
            $pdfFile->storeAs('pdfs', $pdfFileNameMsa, 'public');
            $technician->msa_file = $pdfFileNameMsa;
        }
        if ($request->nda_file) {
            $pdfFile = $request->file('nda_file');
            $pdfFileNameNda = $id . '_' . $pdfFile->getClientOriginalName();
            $pdfFile->storeAs('pdfs', $pdfFileNameNda, 'public');
            $technician->nda_file = $pdfFileNameNda;
        }
        //end pdf
        $technician->save();
        $review = new Review();
        $review->technician_id = $technician->id;
        $review->save();
        $technician->skills()->attach($skillsIds);
        
        return redirect()->back();
    }

    public function ftechAuto(Request $request)
    {
        $query = $request->input('query');
        $results = Technician::select('id', 'technician_id', 'company_name', 'address_data')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('technician_id', 'like', '%' . $query . '%')
                    ->orWhere('company_name', 'like', '%' . $query . '%')
                    ->orWhere('address_data->zip_code', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get();

        return response()->json(['results' => $results], 200);
    }

    public function techData(Request $request)
    {
        $technician = Technician::with('skills')->findOrFail($request->id);
        $rateString = '';

        if (is_array($technician->rate) || is_object($technician->rate)) {
            foreach ($technician->rate as $key => $value) {
                $rateString .= "$key : $value, ";
            }
            $rateString = rtrim($rateString, ", ");
        }

        $skill_sets = $technician->skills->pluck('skill_name')->toArray();
        $imploded = implode(", ", $skill_sets);
        $response = collect($technician)->except('created_at', 'updated_at', 'available', 'co_ordinates');
        $response = $response->put('rate', $rateString);

        $array = [
            'tech' => $response,
            'skills' => $imploded
        ];

        return response()->json($array);
    }


    public function techImport(Request $request)
    {
        $excelFile = $request->all();
        $rules = [
            'ftech_csv_file' => 'required|mimes:csv|max:5120',
        ];

        $message = [
            'ftech_csv_file.required' => 'Please select a file to upload.',
            'ftech_csv_file.mimes' => 'The uploaded file must be in CSV format.',
            'ftech_csv_file.max' => 'The file size cannot exceed 5MB.',
        ];

        $validator = Validator::make($excelFile, $rules, $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            Excel::import(new UserTechImport, $request->file('ftech_csv_file'), 'csv');
            return response()->json(['success' => 'Your file was successfully imported.'], 200);
        }
    }

    public function techExcel()
    {
        $filePath = storage_path('app/files/10Data_technicians.csv');
        return response()->download($filePath);
    }

    public function customerImport(Request $request)
    {
        // dd($request->all());
        $excelFile = $request->all();

        $rules = [
            'customer_csv_file' => 'required',
        ];

        $message = [
            'customer_csv_file.required' => 'Please select a file to upload.',
            'customer_csv_file.mimes' => 'The uploaded file must be in CSV format.',
            // 'customer_csv_file.max' => 'The file size cannot exceed 5MB.',
        ];

        $validator = Validator::make($excelFile, $rules, $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            Excel::import(new CustomersImport, $request->file('customer_csv_file'));
            return response()->json(['success' => 'Your file was successfully imported.'], 200);
        }
    }

    public function customerExcel()
    {
        $filePath = storage_path('app/files/example_customer.csv');
        return response()->download($filePath);
    }

    public function storeCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|max:100',
            'address' => 'required|max:100',
            'customer_type' => 'required|max:100',
            'phone' => 'required|max:15',
        ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator->errors());
        }

        // generating unique 5 digit id starting with 6000
        $customer = Customer::latest('id')->first();
        if ($customer == null) {
            $firstReg = 0;
            $customerId = $firstReg + 1;
            if ($customerId < 10) {
                $id = '6000' . $customerId;
            } elseif ($customerId < 100) {
                $id = '600' . $customerId;
            } elseif ($customerId < 1000) {
                $id = '60' . $customerId;
            } elseif ($customerId < 10000) {
                $id = '6' . $customerId;
            }
        } else {
            $id = $customer->id;
            $customerId = $id + 1;
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
        }

        $addressData = [
            'address' => $request->address,
            'address2' => $request->address2,
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
        $customer->s_rate_a = $request->s_rate_a;
        $customer->s_rate_f = $request->s_rate_f;
        $customer->e_rate_a = $request->e_rate_a;
        $customer->e_rate_f = $request->e_rate_f;
        $customer->w_rate_f = $request->w_rate_f;
        $customer->w_rate_a = $request->w_rate_a;
        $customer->sh_rate_a = $request->sh_rate_a;
        $customer->sh_rate_f = $request->sh_rate_f;
        $customer->travel = $request->travel;
        $customer->billing_term = $request->billing_term;
        $customer->type_phone = $request->type_phone;
        $customer->type_pos = $request->type_pos;
        $customer->type_wireless = $request->type_wireless;
        $customer->type_cctv = $request->type_cctv;
        $customer->team = $request->team;
        $customer->sales_person = $request->sales_person;
        $customer->project_manager = $request->project_manager;
        $customer->customer_id = $id;
        $customer->save();

        // return response()->json(['success' => 'New customer added.'], 200);
    }

    public function customerSearch(Request $request)
    {
        $query = $request->input('query');
        $results = Customer::select('id', 'customer_id', 'company_name')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('customer_id', 'like', '%' . $query . '%')
                    ->orWhere('company_name', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get();

        return response()->json(['results' => $results], 200);
    }

    public function fetchCustomer(Request $request)
    {
        $customer = Customer::findOrFail($request->id);
        $response = collect($customer)->except('created_at', 'updated_at');
        return response()->json($response);
    }

    public function findWorkOrder(Request $request)
    {
        $workOrder = WorkOrder::with('site', 'technician')->find($request->id);
        if (!$workOrder) {
            return response()->json(['message' => 'Work order not found. Please search the work order first.'], 404);
        }
        if ($workOrder->technician === null) {
            $site = $workOrder->site;
            if (!$site) {
                return response()->json(['message' => 'Site information is missing. Please update the work order.'], 400);
            }
            $addressParts = [
                $site->city,
                $site->state,
                $site->zipcode,
            ];
            $addressString = implode(", ", array_filter($addressParts));
            return response()->json($addressString, 200);
        } else {
            return response()->json(['message' => 'This work order already have technician assigned.'], 404);
        }
    }
    public function ifNullWorkOrder(Request $request)
    {
        $workOrder = WorkOrder::find($request->id);
        if (!$workOrder) {
            return response()->json(['message' => 'Work order not found. Please search the work order first.'], 404);
        }
    }

    public function distanceResponse(Request $request)
    {
        $defaultRadius = 150; // Initial radius
        $increment = 50; // Increment value for the radius
        $maxRadius = 500; // Maximum allowable radius
    
        // Determine the current radius
        $radius = $request->input('currentRadius', $defaultRadius);
    
        if ($request->has('nextRadius') && $request->nextRadius) {
            $radius += $increment;
        }
    
        if ($radius > $maxRadius) {
            return response()->json(['errors' => 'Exceeded maximum search radius of 500 miles.'], 422);
        }
    
        $respondedTechnicians = is_array($request->input('respondedTechnicians', []))
            ? $request->input('respondedTechnicians', [])
            : json_decode($request->input('respondedTechnicians', '[]'), true);
    
        $data = $request->all();
        $rules = [
            'destination' => 'required',
        ];
        $message = [
            'destination.required' => 'Project site address is required.',
        ];
        $validator = Validator::make($data, $rules, $message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Check if technicians are available
        $technicians_available = Technician::AvailableFtech()->get(['id', 'address_data']);
        if ($technicians_available->isEmpty()) {
            return response()->json(['errors' => "No available technicians found!"], 404);
        }
    
        // Geocode destination
        $coordinate = $this->geocodingService->geocodeAddress($request->destination);
        if ($coordinate == null) {
            return response()->json(['geocodeError' => 'Invalid site address. Unable to retrieve coordinates.'], 503);
        }
    
        $destination_latitude = $coordinate['geometry']['location']['lat'];
        $destination_longitude = $coordinate['geometry']['location']['lng'];
        $destination = $destination_latitude . ',' . $destination_longitude;
    
        // Fetch locations and calculate distances
        $locations = Technician::select(
            'id',
            DB::raw('ST_X(co_ordinates) as latitude'),
            DB::raw('ST_Y(co_ordinates) as longitude')
        )->whereNotIn('id', $respondedTechnicians)->get();
    
        $destination_obj = new LatLong();
        $destination_obj->setLatitude($destination_latitude);
        $destination_obj->setLongitude($destination_longitude);
    
        $manual_distances = [];
        foreach ($locations as $location) {
            if (is_null($location->latitude) || is_null($location->longitude)) {
                return response()->json(['error' => 'Technician has missing address data.'], 400);
            }
    
            $origin_obj = new LatLong();
            $origin_obj->setLatitude($location->latitude);
            $origin_obj->setLongitude($location->longitude);
    
            $haverSine = new HaversineFormula($origin_obj, $destination_obj);
            $haverSine->setUnit(new Mile);
            $manual_distances[$location->id] = $haverSine->getDistance();
        }
    
        // Filter technicians within the radius
        $filteredArray = array_filter($manual_distances, fn($value) => $value <= $radius);
        asort($filteredArray);
    
        if (empty($filteredArray)) {
            return response()->json(['errors' => "No technicians found in $radius miles radius."], 404);
        }
    
        // Fetch closest technicians
        $manualClosestDistances = Technician::select(
            'id',
            DB::raw('ST_X(co_ordinates) as longitude'),
            DB::raw('ST_Y(co_ordinates) as latitude')
        )->whereIn('id', array_slice(array_keys($filteredArray), 0, 10))->get();
    
        $technicians = [];
        foreach ($manualClosestDistances as $manualClosestDistance) {
            $technicians[] = Technician::availableFtech()
                ->select('id', 'address_data')
                ->where('id', $manualClosestDistance->id)
                ->get();
        }
    
        $mergedTechnicians = collect($technicians)->flatten();
    
        // Prepare origins for Distance Matrix
        $origins = [];
        foreach ($mergedTechnicians as $technician) {
            $addressData = isset($technician->address_data) ? (array)$technician->address_data : [];
            $formattedOrigin = implode(', ', [
                $addressData['country'] ?? '',
                $addressData['city'] ?? '',
                $addressData['state'] ?? '',
                $addressData['zip_code'] ?? ''
            ]);
    
            $origins[] = [
                'technician_id' => $technician->id,
                'origin' => $formattedOrigin,
            ];
        }
    
        $originsString = implode('|', array_column($origins, 'origin'));
    
        // Fetch distance matrix
        $distances = new DistanceMatrixService();
        $data = $distances->getDistance($originsString, $destination);
    
        // Compile response
        $completeInfo = [];
        $rows = isset($data['rows']) && is_array($data['rows']) ? $data['rows'] : [];
    
        foreach ($rows as $index => $row) {
            if (isset($row['elements'][0]) && $row['elements'][0]['status'] === "OK") {
                $technicianId = $origins[$index]['technician_id'];
                $distanceText = $row['elements'][0]['distance']['text'];
                $durationText = $row['elements'][0]['duration']['text'];
    
                $ftech = Technician::with('skills')->find($technicianId);
                if ($ftech) {
                    $distanceTextMiles = null; // Initialize to handle invalid input
                    $cleanDistanceText = preg_replace('/[^0-9.]/', '', $distanceText); // Remove all non-numeric and non-decimal characters
                    
                    if (is_numeric($cleanDistanceText)) {
                        $distanceTextMiles = $cleanDistanceText * 0.621371; // Convert km to miles
                    } else {
                        // Handle the case where the value is invalid
                        error_log("Invalid distance text: {$distanceText}");
                    }
    
                    if ($distanceTextMiles <= $radius) {
                        $completeInfo[] = [
                            'id' => $ftech->id,
                            'technician_id' => $ftech->technician_id,
                            'email' => $ftech->email,
                            'phone' => $ftech->phone,
                            'company_name' => $ftech->company_name,
                            'distance' => number_format($distanceTextMiles, 2) . ' mi',
                            'status' => $ftech->status,
                            'rate' => $ftech->rate,
                            'travel_fee' => $ftech->travel_fee ?? "",
                            'preference' => $ftech->preference ?? "",
                            'duration' => $durationText,
                            'skills' => $ftech->skills->pluck('skill_name')->toArray(),
                        ];
                    }
                }
            }
        }
    
        if (empty($completeInfo)) {
            return response()->json(['errors' => "No technicians found in $radius miles radius."], 404);
        }
    
        return response()->json(['technicians' => $completeInfo, 'currentRadius' => $radius], 200);
    }
    
    

    public function assignTech(Request $request)
    {
        $orderId = $request->workOrderId;
        $workOrder = WorkOrder::find($orderId);
        $workOrder->ftech_id = $request->ftech_id;
        $workOrder->stage = Status::STAGE_DISPATCH;
        $workOrder->status = Status::CONFIRM;
        $workOrder->save();
        $technician = Technician::find($workOrder->ftech_id);
        $technician->wo_ct += 1;
        $technician->save();
        $id = [
            $workOrder->id,
        ];
        $response = [
            'id' => $id,
            'message' => 'Technician assigning successful for the selected work order.'
        ];
        if ($request->isSendingMail == 1) {
            $this->sendWorkOrder($orderId, $technician->email);
        }
        return response()->json($response, 200);
    }

    public function sendWorkOrder($orderId, $techEmail)
    {
        $to = $techEmail;
        $sub = "Work order details of TechYeah";
        $body = 'https://techyeah.codetreebd.com/pdf/work/order/download/' . $orderId;
        $sender = "Tech Yeah";
        $emailData = [
            'subject' => $sub,
            'body' => $body,
            'to' => $to,
            'sender' => $sender,
        ];
        try {
            Mail::to($to)->send(new MyTestMail($emailData));
            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Email failed to send', 'error' => $e->getMessage()], 500);
        }
    }


    public function sendMail(Request $request)
    {
        $to = $request->to_email;
        $subject = $request->subject;
        $body = $request->body_text;
        $sender = 'Tech-Yeah';

        $emailData = [
            'subject' => $subject,
            'body' => $body,
            'to' => $to,
            'sender' => $sender,
        ];

        try {
            Mail::to($to)->send(new MyTestMail_sample($emailData));
            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Email failed to send', 'error' => $e->getMessage()], 500);
        }
    }
    public function wODelete($id)
    {
        $w = WorkOrder::find($id);
        $w->delete();
        $notify[] = ['success', 'delete success'];
        return back()->withNotify($notify);
    }

    public function wOStageChange($id, $value)
    {
        $w = WorkOrder::find($id);
        $w->stage = $value;
        $w->save();
        $notify[] = ['success', 'Stage Update Success'];
    
        return back()->withNotify($notify);
    }
    


    // New functions

    public function newWo($type)
    {
        return Inertia::render('user/createWorkOrder/CreateWorkOrder', [
            'type' => $type
        ]);
    }

    public function storeWo(Request $request)
    {
        $orderId = WorkOrder::orderBy('id', 'desc')->first();
        $rand = rand(10, 99);

        if ($orderId == null) {
            $id = 0;
            $f = $id + 1;
        } else {
            $p = $orderId->id;
            $f = $p + 1;
        }
        $date = date('mdy');
        $id = $date . $rand;
        
        $wo = new WorkOrder();

        $wo->auth_id = Auth::user()->id;

        if($request->order_type == 1)
        {
            $wo->order_id = "S1" . $id . $f;
        }elseif($request->order_type == 2){
            $wo->order_id = "P1" . $id . $f;
        }elseif($request->order_type == 3){
            $wo->order_id = "I1" . $id . $f;
        }
        
        $wo->em_id = $request->wo_manager;
        $wo->priority = $request->priority;
        $wo->requested_by = $request->requested_by;
        $wo->order_type = $request->order_type;
        $wo->scope_work = $request->scope_work;
        $wo->r_tools = $request->r_tools;
        $wo->status = 1;
        $wo->stage = Status::STAGE_NEW;
        $wo->save();

        $invoice = new CustomerInvoice();
        $invoice->invoice_number = getNumber();
        $invoice->work_order_id = $wo->id;
        $invoice->save();

        return redirect()->route('user.work.order.view.inertia', $wo->id);
    }

    public function allWoList()
    {
        return Inertia::render('user/allWorkOrder/AllWorkOrder', []);
    }

    public function userInertiaLayout($id)
    {
        $wo = WorkOrder::with([
            'customer',
            'technician.engineers',
            'employee',
            'site.relatedWo:id,order_id,created_at,site_id,slug,scope_work',
            'site.relatedWo.customer:id,company_name',
            'site' => function ($query) {
                $query->select(
                    'id',
                    'location',
                    'address_1',
                    'city',
                    'state',
                    'zipcode',
                    'time_zone',
                    DB::raw('ST_AsText(co_ordinates) as co_ordinates')
                );
            },
            'contacts',
            'docsForTech',
            'checkInOut.engineer',
            'timeLogs',
            'tasks',
            'shipments',
            'techProvidedParts',
            'schedules',
            'assignedTech.engineer',
            'techRemoveReasons',
            'otherExpenses',
            'notes.user:id,firstname,lastname',
            'notes.subNotes'
        ])->find($id);
        // Check if technician data exists
        if ($wo && $wo->technician) {
            // Check if the address_data and rate are already decoded (i.e., not strings)
            if (is_string($wo->technician->address_data)) {
                $wo->technician->address_data = json_decode($wo->technician->address_data, true);
            }
            
            if (is_string($wo->technician->rate)) {
                $wo->technician->rate = json_decode($wo->technician->rate, true);
            }
    
            // Handle co_ordinates or leave as is if not needed
            $wo->technician->co_ordinates = null; // Set or process as needed
        }

    
        return Inertia::render('user/workOrder/WoView', [
            'wo' => $wo,
        ]);
    }

    // Overview

    public function makeHold(Request $request, $id)
    {
        $wo = WorkOrder::find($id);
        if ($wo->is_hold == 1) {
            $wo->is_hold = 0;
        } else {
            $wo->is_hold = Status::STAGE_HOLD;
            $wo->holding_note = $request->holding_note;
        }


        $wo->save();
    }

    public function makeCancel(Request $request, $id)
    {
        $wo = WorkOrder::find($id);

        $wo->stage = Status::STAGE_CANCEL;
        $wo->cancelling_note = $request->cancelling_note;

        $wo->save();
    }

    public function nextStatus($id)
    {
        $wo = WorkOrder::find($id);
        if ($wo->stage == Status::STAGE_CLOSED) {
            $invoiceDate = CustomerInvoice::where('work_order_id', $id)->first();
            $invoiceDate->date = \Carbon\Carbon::now();
            $invoiceDate->save();
        }

        if (!$wo) {
            $notify[] = ['error', 'Work Order not found'];
            return back()->withNotify($notify);
        }
        if ($wo->status == Status::AT_RISK) {
            $notify[] = ['error', 'Please Reschedule first!'];
        } else {
            $notify[] = ['success', 'Status Updated'];
        }

        if ($wo->status == Status::PENDING) {
            $wo->status = null;
            $wo->stage += 1;
        } elseif ($wo->stage == 2 && $wo->status == null) {
            $wo->status = Status::CONTACTED;
        } elseif ($wo->status == Status::CONTACTED) {
            $wo->status = null;
            $wo->stage += 1;
        } elseif ($wo->stage == 3 && $wo->status == null) {
            $wo->status = Status::CONFIRM;
        } elseif ($wo->status == Status::CONFIRM) { // Status 3
            $wo->status = Status::EN_ROUTE;
        } elseif ($wo->status == Status::AT_RISK || $wo->status == Status::DELAYED || $wo->status == Status::ON_HOLD) { // Status 4, 5, 6
            $wo->status = Status::EN_ROUTE; // Skip all to EN_ROUTE
        } elseif ($wo->status == Status::EN_ROUTE) { // Status 7
            $wo->status = Status::CHECKED_IN; // Move to CHECKED_IN
        } elseif ($wo->status == Status::CHECKED_IN) { // Status 8
            $wo->status = Status::CHECKED_OUT; // Move to CHECKED_OUT
        } elseif ($wo->status == Status::CHECKED_OUT) {
            $wo->status = null;
            $wo->stage += 1;
        } elseif($wo->stage == 4 && $wo->status == null) {
            $wo->status = Status::NEEDS_APPROVAL;
        } elseif ($wo->status == Status::NEEDS_APPROVAL) {
            $wo->status = null;
            $wo->stage += 1;
        } elseif($wo->stage == 5 && $wo->status == null) {
            $wo->status = Status::APPROVED;
        } elseif ($wo->status == Status::APPROVED) { // Status 12
            $wo->status = Status::INVOICED; // Move to INVOICED
        } elseif ($wo->status == Status::INVOICED) { // Status 13
            $wo->status = Status::PAID; // Move to PAID
        } elseif ($wo->status == Status::PAST_DUE) { // Status 14
            $wo->status = Status::PAID; // Move to PAID
        } else {
            $notify[] = ['warning', 'Work Order is already complete or invalid status'];
            return back()->withNotify($notify);
        }        
        
        
        try {
            if ($wo->status == Status::INVOICED) {
                DB::table('past_due_check')->insert([
                    'wo_id' => $wo->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        $wo->save();

        return back()->withNotify($notify);
    }


    public function backStatus($id)
    {
        $wo = WorkOrder::find($id);

        if (!$wo) {
            $notify[] = ['error', 'Work Order not found'];
            return back()->withNotify($notify);
        }

        if ($wo->status == Status::PAID) {
            $wo->status = Status::INVOICED;
        } elseif ($wo->status == Status::PAST_DUE) {
            $wo->status = Status::INVOICED;
        } elseif ($wo->status == Status::INVOICED) {
            $wo->status = Status::APPROVED;
        } elseif ($wo->status == Status::APPROVED) {
            $wo->status = null;
        } elseif($wo->stage == 5 && $wo->status == null) {
            $wo->stage -= 1;
            $wo->status = Status::NEEDS_APPROVAL;
        } elseif ($wo->status == Status::NEEDS_APPROVAL) {
            $wo->status = null;
        } elseif($wo->stage == 4 && $wo->status == null) {
            $wo->stage -= 1;
            $wo->status = Status::CHECKED_OUT;
        } elseif ($wo->status == Status::CHECKED_OUT) {
            $wo->status = Status::CHECKED_IN;
        } elseif ($wo->status == Status::CHECKED_IN) {
            $wo->status = Status::EN_ROUTE;
        } elseif ($wo->status == Status::EN_ROUTE) {
            $wo->status = Status::CONFIRM;
        } elseif ($wo->status == Status::CONFIRM) {
            $wo->status = null;
        } elseif($wo->stage == 3 && $wo->status == null) {
            $wo->stage -= 1;
            $wo->status = Status::CONTACTED;
        } elseif ($wo->status == Status::CONTACTED) {
            $wo->status = null;
        } elseif($wo->stage == 2 && $wo->status == null) {
            $wo->stage -= 1;
            $wo->status = Status::PENDING;
        } elseif ($wo->status == Status::PENDING) {
            $notify[] = ['warning', 'Cannot move back from the initial status'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['warning', 'Invalid status'];
            return back()->withNotify($notify);
        }        

        $wo->save();

        $notify[] = ['success', 'Status Updated'];
        return back()->withNotify($notify);
    }

    // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
    public function createWorkOrderTimeLog($tableName, $columnName, $wo_id, $date, $preLog, $value, $toUser = null, $type,  $msg, $id) {
        $wo_log = new WorkOrderTimeLog();
        $wo_log->wo_id = $wo_id;
        $wo_log->pre_log_id = $preLog->id ?? null;
        $wo_log->identity = $id ?? null;
        $wo_log->by_user = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $wo_log->to_user = $toUser ?? null;
        $wo_log->event_title = $msg;
        $wo_log->table_name = $tableName;
        $wo_log->column_name = $columnName;
        $wo_log->value_type = $type;
        $wo_log->value = $value;
        $wo_log->recorded_at = $date;
        $wo_log->save();
    }


    public function updateOverview(Request $request, $id)
    {

        $wo = WorkOrder::find($id);
        $wo->slug = $request['cus_id'] ?? $wo->slug;
        $wo->priority = $request['priority'] ?? $wo->priority;
        $wo->requested_by = $request['requested_by'] ?? $wo->requested_by;
        $wo->em_id = $request['wo_manager'] ?? $wo->em_id;

        $wo->save();

        if ($request->priority) {
            $preLog = WorkOrderTimeLog::where('column_name', 'priority')->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_orders','priority', $wo->id, $wo->updated_at, $preLog, $wo->priority, '', 'nrml_text', $preLog ? 'Priority Updated':'Priority Added', $id);
        }
        
        // Check for customer ID update
        if ($request->cus_id) {
            $preLog = WorkOrderTimeLog::where('column_name', 'slug')->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_orders','slug', $wo->id, $wo->updated_at, $preLog, $wo->slug, $wo->customer->company_name ?? '', 'id', $preLog ? 'Customer Updated':'Customer Added', $id);
        }
        
        // Check for requested by update
        if ($request->requested_by) {
            $preLog = WorkOrderTimeLog::where('column_name', 'requested_by')->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_orders','requested_by', $wo->id, $wo->updated_at, $preLog, $wo->requested_by, $request->requested_by, 'nrml_text', $preLog ? 'Requested User Updated':'Requested User Added', $id);
        }
        
        // Check for work order manager update
        if ($request->wo_manager) {
            $preLog = WorkOrderTimeLog::where('column_name', 'em_id')->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_orders','em_id', $wo->id, $wo->updated_at, $preLog, $wo->em_id, $wo->employee->name ?? '', 'id', $preLog ? 'Employee Updated':'Employee Added', $id);
        }
        
    }

    public function updateScopeOfWork(Request $request, $id)
    {

        $wo = WorkOrder::find($id);
        $wo->scope_work = $request['scope_work'] ?? $wo->scope_work;

        $wo->save();

        if ($request->scope_work) {
            $preLog = WorkOrderTimeLog::where('column_name', 'scope_work')->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_orders','scope_work', $wo->id, $wo->updated_at, $preLog, $wo->scope_work, '', 'html_text', $preLog ? 'Scope Of Work Updated':'Scope Of Work Added', $id);
        }
        
    }

    public function updateTools(Request $request, $id)
    {

        $wo = WorkOrder::find($id);
        $wo->r_tools = $request['r_tools'];

        $wo->save();

        if ($request->r_tools) {
            $preLog = WorkOrderTimeLog::where('column_name', 'r_tools')->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_orders','r_tools', $wo->id, $wo->updated_at, $preLog, $wo->r_tools, '', 'html_text', $preLog ? 'Required Tools Updated':'Required Tools Added', $id);
        }
    }

    public function updateDispatchedInstruction(Request $request, $id)
    {

        $wo = WorkOrder::find($id);
        $wo->instruction = $request['instruction'];

        $wo->save();

        if ($request->instruction) {
            $preLog = WorkOrderTimeLog::where('column_name', 'instruction')->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_orders','instruction', $wo->id, $wo->updated_at, $preLog, $wo->instruction, '', 'html_text', $preLog ? 'Dispatched Instruction Updated':'Dispatched Instruction Added', $id);
        }
    }


    public function createSchedule(Request $request, $id)
    {
        $rules = [
            'on_site_by' => 'required',
            'scheduled_time' => 'required',
            'estimated_time' => 'max:24'
        ];

        $messages = [
            'on_site_by.required' => 'Schedule date is required',
            'scheduled_time.required' => 'Schedule time is required',
            'estimated_time.max' => 'Estimated hours cannot be more than 24 hours',
        ];

        $validated = $request->validate($rules, $messages);

        $schdule = new WorkOrderSchedule();

        $schdule->wo_id = $id;
        $schdule->on_site_by = $request->on_site_by;
        $schdule->scheduled_time = $request->scheduled_time;
        $schdule->h_operation = $request->h_operation;
        $schdule->estimated_time = $request->estimated_time;

        $schdule->save();

        $this->createWorkOrderTimeLog('work_order_schedules','', $schdule->wo_id, $schdule->updated_at, '', '<p>'.$schdule->on_site_by.' at '.$schdule->scheduled_time.'</p><br/><p> Hours of operation: '.$schdule->h_operation.'</p><br/><p> Estimated Hours: '.$schdule->h_operation.'</p>', '', 'html_text','Schedule Added', $schdule->id);
    }

    public function updateSchedule(Request $request, $id)
    {
        $schdule = WorkOrderSchedule::find($id);

        $schdule->on_site_by = $request->on_site_by ?? $schdule->on_site_by;
        $schdule->scheduled_time = $request->scheduled_time ?? $schdule->scheduled_time;
        $schdule->h_operation = $request->h_operation ?? $schdule->h_operation;
        $schdule->estimated_time = $request->estimated_time ?? $schdule->estimated_time;

        $schdule->save();

        if ($request->on_site_by) {
            $preLog = WorkOrderTimeLog::where('column_name', 'schedule-on_site_by-'.$schdule->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_order_schedules','schedule-on_site_by-'.$schdule->id, $schdule->wo_id, $schdule->updated_at, $preLog, $schdule->on_site_by, '', 'time', $preLog ? 'Schedule Date Updated':'Schedule Date Added', $id);
        }
        if ($request->scheduled_time) {
            $preLog = WorkOrderTimeLog::where('column_name', 'schedule-scheduled_time-'.$schdule->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_order_schedules','schedule-scheduled_time-'.$schdule->id, $schdule->wo_id, $schdule->updated_at, $preLog, $schdule->scheduled_time, '', 'time', $preLog ? 'Schedule Time Updated':'Schedule Time Added', $id);
        }
        if ($request->h_operation) {
            $preLog = WorkOrderTimeLog::where('column_name', 'schedule-h_operation-'.$schdule->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_order_schedules','schedule-h_operation-'.$schdule->id, $schdule->wo_id, $schdule->updated_at, $preLog, $schdule->h_operation, '', 'nrml_text', $preLog ? 'Schedule Hours Of Operation Updated':'Schedule Hours Of Operation Added', $id);
        }
        if ($request->estimated_time) {
            $preLog = WorkOrderTimeLog::where('column_name', 'schedule-estimated_time-'.$schdule->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_order_schedules','schedule-estimated_time-'.$schdule->id, $schdule->wo_id, $schdule->updated_at, $preLog, $schdule->estimated_time, '', 'nrml_text', $preLog ? 'Schedule Estimated Hours Updated':'Schedule Estimated Hours Added', $id);
        }
    }

    public function deleteSchedule($id)
    {
        $schdule = WorkOrderSchedule::find($id);

        $this->createWorkOrderTimeLog('work_order_schedules','', $schdule->wo_id, $schdule->updated_at, '', '<p>'.$schdule->on_site_by.' at '.$schdule->scheduled_time.'</p><br/><p> Hours of operation: '.$schdule->h_operation.'</p><br/><p> Estimated Hours: '.$schdule->h_operation.'</p>', '', 'html_text','Schedule Deleted', $id);

        $schdule->delete();
    }

    public function updateScheduleType(Request $request, $id, $value)
    {
        $wo = WorkOrder::find($id);
        
        if (!$wo) {
            return response()->json(['error' => 'Work order not found.'], 404);
        }
    
        $wo->schedule_type = $value;

        $this->createWorkOrderTimeLog('work_orders','', $id, $wo->updated_at, '', $wo->schedule_type, '', 'nrml_text','Schedule Type Changed', $id);

        $wo->save();
    }

    public function createContact(Request $request, $id)
    {
        $rules = [
            'title' => 'required',
            'phone' => 'required',
        ];

        $messages = [
            'title.required' => 'Contact Title is required',
            'phone.required' => 'Contact Number is required',
        ];

        $validated = $request->validate($rules, $messages);

        $contact = new Contact();
        $contact->wo_id = $id;
        $contact->title = $validated['title'];
        $contact->name = $request['name'];
        $contact->phone = $validated['phone'];

        $contact->save();
        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        $this->createWorkOrderTimeLog('contacts','', $contact->wo_id, $contact->updated_at, '', '<p>'.$contact->title.'</p><br/><p>'.$contact->name.'</p><br/><p>'.$contact->phone.'</p>', '', 'html_text', 'Contact Added', $contact->id);
    }

    public function updateContact(Request $request, $id)
    {

        $contact = Contact::find($id);
        $contact->title = $request['title'] ?? $contact->title;
        $contact->name = $request['name'] ?? $contact->name;
        $contact->phone = $request['phone'] ?? $contact->phone;

        $contact->save();

        if ($request->title) {
            $preLog = WorkOrderTimeLog::where('column_name', 'contact-title-'.$contact->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('contacts','contact-title-'.$contact->id, $contact->wo_id, $contact->updated_at, $preLog, $contact->title, '', 'nrml_text', $preLog ? 'Contact Title Updated':'Contact Title Added', $id);
        }
        
        if ($request->name) {
            $preLog = WorkOrderTimeLog::where('column_name', 'contact-name-'.$contact->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('contacts','contact-name-'.$contact->id, $contact->wo_id, $contact->updated_at, $preLog, $contact->name, '', 'nrml_text', $preLog ? 'Contact Name Updated':'Contact Name Added', $id);
        }

        if ($request->phone) {
            $preLog = WorkOrderTimeLog::where('column_name', 'contact-phone-'.$contact->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('contacts','contact-phone-'.$contact->id, $contact->wo_id, $contact->updated_at, $preLog, $contact->title, '', 'nrml_text', $preLog ? 'Contact Number Updated':'Contact Number Added', $id);
        }
    }

    public function deleteContact($id)
    {

        $contact = Contact::find($id);
        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        $this->createWorkOrderTimeLog('contacts','', $contact->wo_id, $contact->updated_at, '', '<p>'.$contact->title.'</p><br/><p>'.$contact->name.'</p><br/><p>'.$contact->phone.'</p>', '', 'html_text', 'Contact Deleted', $id);
        $contact->delete();
    }

    public function updateSiteInfo(Request $request, $id)
    {
        $rules = [
            'site_id' => 'required',
        ];

        $messages = [
            'site_id.required' => 'Select a site first',
        ];

        $validated = $request->validate($rules, $messages);

        $wo = WorkOrder::find($id);
        $wo->site_id = $validated['site_id'];

        $wo->save();

        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        if($request->site_id){
            $preLog = WorkOrderTimeLog::where('column_name', 'site_id')->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('work_orders','site_id', $wo->id, $wo->updated_at, $preLog, $wo->site_id, '', 'id', $preLog? 'Site Information Updated':'Site Information Added', $id);
        }
        
    }

    public function uploadDocForTech(Request $request, $id)
    {
        try {
            // Validation rules for files
            $rules = [
                'file' => 'required|file|mimes:jpeg,png,pdf,doc,docx,xlsx|max:2048', // 2MB
            ];
    
            $messages = [
                'file.required' => 'Select files first.',
                'file.mimes' => 'Only JPEG, PNG, PDF, XLSX, DOC, and DOCX files are allowed.',
                'file.max' => 'Maximum file size is 2MB.',
            ];
    
            // Validate the incoming request
            $validated = $request->validate($rules, $messages);
    
            // Check if there is a file in the request
            if ($request->hasFile('file')) {
                // Get the uploaded file
                $file = $request->file('file'); // Single file, no need for foreach
                
                // Generate a unique file name
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $file->getClientOriginalName();
                
                // Move the file to the public directory
                $file->move(public_path('docs/technician'), $fileName);
    
                // Create a new document entry for the file
                $doc = new DocForTechnician();
                $wo = WorkOrder::find($id);
    
                // Handle the case where WorkOrder might not exist
                if (!$wo) {
                    return response()->json(['message' => 'Work order not found'], 404);
                }
    
                // Assign properties to the document record
                $doc->wo_id = $id;
                $doc->technician_id = $wo->ftech_id;
                $doc->file = 'docs/technician/' . $fileName;
                $doc->name = $originalName;
    
                // Save the document record
                $doc->save();
    
                // Return a success response
                return response()->json(['message' => 'Document uploaded successfully.']);
            }
    
            // If no files were uploaded
            return response()->json(['message' => 'No file uploaded.'], 400);
    
        } catch (\Throwable $th) {
            // Catch any errors and return the error message
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    
    public function deleteDocForTech($id)
    {
        $doc = DocForTechnician::find($id);

        if ($doc) {
            $filePath = public_path($doc->file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $doc->delete();

        } else {
            
        }
    }

    public function makeCheckin(Request $request, $id, $techId = null)
    {
        $checkInOuts = CheckInOut::where('work_order_id', $id)->where('tech_id', $techId)->get();
        $wo = WorkOrder::find($id);

        if (!$wo) {
            $notify[] = ['error', 'Work Order not found'];
            return back()->withNotify($notify);
        }

        if (!$wo->ftech_id) {
            $notify[] = ['error', 'Assign a technician first'];
            return back()->withNotify($notify);
        }

        $wo->status = 8;
        $wo->save();

        foreach ($checkInOuts as $checkInOut) {
            if ($checkInOut->check_in && !$checkInOut->check_out) {
                $notify[] = ['error', 'Technician Already Checked in'];
                return back()->withNotify($notify);
            }
        }

        $timezoneMap = [
            'PT' => 'America/Los_Angeles',
            'MT' => 'America/Denver',
            'CT' => 'America/Chicago',
            'ET' => 'America/New_York',
            'AKT' => 'America/Anchorage',
            'HST' => 'Pacific/Honolulu',

            'PT/MT' => 'America/Los_Angeles',
            'CT/MT' => 'America/Chicago',
            'CT/ET' => 'America/New_York',
        ];



        $shortTimezone = $wo->site->time_zone ?? 'UTC';
        $mappedTimezone = $timezoneMap[$shortTimezone] ?? 'UTC';


        $newCheckInOut = new CheckInOut();
        $newCheckInOut->work_order_id = $wo->id;
        $newCheckInOut->tech_id = $techId;
        $newCheckInOut->company_name = $wo->technician->company_name;
        $newCheckInOut->date = Carbon::now()->format('m/d/y');
        $newCheckInOut->check_in = Carbon::now($mappedTimezone)->format('H:i:s');
        $newCheckInOut->time_zone = $wo->site->time_zone;
        $newCheckInOut->checkin_note = $request->checkin_note;
        $newCheckInOut->save();

        $preLog = WorkOrderTimeLog::where('column_name', 'check_in')->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('check_in_outs','check_in', $newCheckInOut->work_order_id, $newCheckInOut->updated_at, $preLog, '<p>time: '.$newCheckInOut->date.' at '.$newCheckInOut->check_in.' ('.$newCheckInOut->time_zone.') </p><br/><p>note: '.$newCheckInOut->checkin_note.'</p>', '', 'html_text', $techId ? $newCheckInOut->engineer->name.' Checked In':$wo->technician->company_name. ' Checked In', $techId);
    }


    public function makeCheckout(Request $request, $id, $techId = null)
    {
        $checkInOut = CheckInOut::where('work_order_id', $id)->where('tech_id', $techId)
            ->orderBy('id', 'desc')
            ->first();
        $wo = WorkOrder::find($id);

        if (!$wo) {
            $notify[] = ['error', 'Work Order not found'];
            return back()->withNotify($notify);
        }

        // $wo->status = 9;
        // $wo->save();

        if (!$checkInOut) {
            $notify[] = ['error', 'Technician is not checked in yet'];
            return back()->withNotify($notify);
        }


        $checkInOut->work_order_id = $wo->id;

        $timezoneMap = [
            'PT' => 'America/Los_Angeles',
            'MT' => 'America/Denver',
            'CT' => 'America/Chicago',
            'ET' => 'America/New_York',
            'AKT' => 'America/Anchorage',
            'HST' => 'Pacific/Honolulu',

            'PT/MT' => ['America/Los_Angeles', 'America/Denver'],
            'CT/MT' => ['America/Denver', 'America/Chicago'],
            'CT/ET' => ['America/Chicago', 'America/New_York'],
        ];


        $shortTimezone = $wo->site->time_zone ?? 'UTC';
        $mappedTimezone = $timezoneMap[$shortTimezone] ?? 'UTC';

        $checkInTime = Carbon::parse($checkInOut->check_in, $mappedTimezone);

        $checkOutTime = Carbon::parse($request->checkoutTime ??  Carbon::now($mappedTimezone));

        if ($checkOutTime->lessThanOrEqualTo($checkInTime)) {
            $checkOutTime->addDay();
        }

        $totalMinutes = $checkOutTime->diffInMinutes($checkInTime);

        if($techId != null) {
            $firstCheckInOut = CheckInOut::where('work_order_id', $id)->where('tech_id', $techId)->where('check_out','!=', null)
            ->first();
        }else{
            $firstCheckInOut = CheckInOut::where('work_order_id', $id)->where('check_out','!=', null)->first();
        }

        if ($totalMinutes <= 60 && !$firstCheckInOut) {
            $totalHours = 1;
        } else {
            $remainingMinutes = $totalMinutes - 60;

            $additionalHours = ceil($remainingMinutes / 15) * 0.25;

            $totalHours = 1 + $additionalHours;
        }

        $checkInOut->check_out = $checkOutTime->format('H:i:s');
        $checkInOut->total_hours = $totalHours;
        $checkInOut->save();

        $preLog = WorkOrderTimeLog::where('column_name', 'check_out')->orderBy('id', 'desc')->first();
        $this->createWorkOrderTimeLog('check_in_outs','check_out', $checkInOut->work_order_id, $checkInOut->updated_at, $preLog, '<p>time: '.Carbon::now()->format('m/d/y').' at '.$checkInOut->check_out.' ('.$checkInOut->time_zone.') </p><br/><p>total hours: '.$checkInOut->total_hours.'</p>', '', 'html_text', $techId ? $checkInOut->engineer->name.' Checked Out':$wo->technician->company_name. ' Checked Out', $techId);
    }

    public function goAtRisk($id, Request $request)
    {
        $wo = WorkOrder::find($id);

        if($request->atRisk){
            $wo->stage = Status::STAGE_DISPATCH;
            $wo->status = Status::AT_RISK;
        }else{
            $wo->status = null;
        }
        
        $wo->save();
    }

    public function reSchedule(Request $request, $id)
    {
        try {
            $schdule = WorkOrderSchedule::find($id);

            $schdule->on_site_by = $request['on_site_by'] ?? $schdule->on_site_by;
            $schdule->scheduled_time = $request['scheduled_time'] ?? $schdule->scheduled_time;
            $schdule->h_operation = $request['h_operation'] ?? $schdule->h_operation;
            $schdule->estimated_time = $request['estimated_time'] ?? $schdule->estimated_time;
            $schdule->save();

            $wo = WorkOrder::find($schdule->wo_id);
            $wo->stage == Status::STAGE_DISPATCH;
            $wo->status = null;
            $wo->save();

            $this->createWorkOrderTimeLog('work_order_schedules','', $schdule->wo_id, $schdule->updated_at, '', '<p>'.$schdule->on_site_by.' at '.$schdule->scheduled_time.'</p><br/><p> Hours of operation: '.$schdule->h_operation.'</p><br/><p> Estimated Hours: '.$schdule->h_operation.'</p>', '', 'html_text','Time Rescheduled', $schdule->id);
        } catch (\Throwable $th) {
            Log::error($th);
        }
        

    }

    public function updatePaySheet(Request $request, $id)
    {
        $ps = PaySheet::where('wo_id', $id)->first();

        if (!empty($ps)) {
            $ps->tech_rate = $request->rate;
            $ps->total_amount = $request->total_amount ?? 0;
            $ps->expenses = $request->expenses ?? 0;
            $ps->save();
            $notify[] = ['success', 'Pay Sheet Updated Successfully'];
        } else {
            $ps = new PaySheet();
            $ps->wo_id = $id;
            $ps->tech_rate = $request->rate;
            $ps->total_amount = $request->total_amount ?? 0;
            $ps->expenses = $request->expenses ?? 0;
            $ps->save();
            $notify[] = ['success', 'Pay Sheet Added Successfully'];
        }
        return back()->withNotify($notify);
    }

    public function logCheckinout(Request $request, $id)
    {
        $checkInOut = CheckInOut::find($id);
    
        if (!$checkInOut) {
            $notify[] = ['error', 'Check-in/out record not found'];
            return back()->withNotify($notify);
        }
    
        // Parse the check-in and check-out times
        $checkInTime = Carbon::parse($request->check_in ?? $checkInOut->check_in);
        $checkOutTime = isset($request->check_out) ? Carbon::parse($request->check_out) : $checkInOut->check_out;
    
        if ($checkOutTime) {
            // Ensure checkOutTime is a Carbon instance before comparing
            if (!$checkOutTime instanceof Carbon) {
                $checkOutTime = Carbon::parse($checkOutTime);
            }
    
            // Ensure checkInTime is also a Carbon instance before comparing
            if (!$checkInTime instanceof Carbon) {
                $checkInTime = Carbon::parse($checkInTime);
            }
    
            // Check if the check-out time is less than or equal to the check-in time
            if ($checkOutTime->lessThanOrEqualTo($checkInTime)) {
                $checkOutTime->addDay(); // Add one day if check-out time is earlier than or equal to check-in time
            }
    
            // Calculate total minutes and total hours
            $totalMinutes = $checkOutTime->diffInMinutes($checkInTime);

            if($checkInOut->tech_id != null) {
                $firstCheckInOut = CheckInOut::where('work_order_id', $checkInOut->work_order_id)->where('tech_id', $checkInOut->tech_id)->where('check_out','!=', null)
                ->first();
            }else{
                $firstCheckInOut = CheckInOut::where('work_order_id', $checkInOut->work_order_id)->where('check_out','!=', null)->first();
            }

            if ($totalMinutes <= 60 && !$firstCheckInOut) {
                $totalHours = 1;
            } else {
                $remainingMinutes = $totalMinutes - 60;
                $additionalHours = ceil($remainingMinutes / 15) * 0.25;
                $totalHours = 1 + $additionalHours;
            }
    
            $checkInOut->total_hours = $totalHours;
        } else {
            $checkInOut->total_hours = 0;
        }
    
        // Save the check-in and check-out times along with the date
        $checkInOut->check_in = $checkInTime->format('H:i:s');
        $checkInOut->check_out = $checkOutTime ? $checkOutTime->format('H:i:s') : null;
        $checkInOut->date = Carbon::parse($request->date)->format('m/d/y');
        $checkInOut->save();
    
        $notify[] = ['success', 'Checkin/out time updated'];
        return back()->withNotify($notify);
    }
    

    public function deleteLog($id)
    {
        $checkInOut = CheckInOut::find($id);
        $checkInOut->delete();

        $notify[] = ['success', 'Checkin/out time deleted successfully'];
        return back()->withNotify($notify);
    }



    public function addTask(Request $request, $id, $techId = null)
    {
        $maxFileSize = 1024;
        if ($request->hasFile('file')) {
            $fileMime = $request->file('file')->getMimeType();
            if (str_starts_with($fileMime, 'image/')) {
                $maxFileSize = 2048;
            }
        }

        // Validate the input
        $request->validate([
            'type' => 'required|string',
            'file' => 'nullable|file|max:' . $maxFileSize,
        ], [
            'file.max' => $maxFileSize === 2048
                ? 'The image file size must not exceed 2MB.'
                : 'The file size must not exceed 1MB.',
        ]);

        $task = new Task();
        $wo = WorkOrder::find($id);

        $task->wo_id = $id;
        $task->tech_id = $techId;
        $task->type = $request->type;
        $task->reason = $request->reason;
        $task->description = $request->desc;
        $task->email = $request->email;
        $task->phone = $request->phone;
        $task->from = $request->from;
        $task->item = $request->item;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('docs/tasks'), $filename);
            $task->file = 'docs/tasks/' . $filename;
        }

        $task->save();

        if ($request->type = 'call') {
            $this->createWorkOrderTimeLog('tasks','', $task->wo_id, $task->updated_at, '', '<p> Phone: '.$task->phone.'</p><br/><p> Reason: '.$task->reason.'</p>', $task->tech ? $task->tech->name: $wo->technician->company_name, 'html_text', $task->tech_id ? 'Task Added For '.$task->tech->name : 'Task Added', $id);
        }

        elseif ($request->type = 'collect_signature') {
            $this->createWorkOrderTimeLog('tasks','', $task->wo_id, $task->updated_at, '', '<p> Signature from: '.$task->from.'</p>', $task->tech ? $task->tech->name: $wo->technician->company_name, 'html_text', $task->tech_id ? 'Task Added For '.$task->tech->name : 'Task Added', $id);
        }

        elseif ($request->type = 'custom_task') {
            $this->createWorkOrderTimeLog('tasks','', $task->wo_id, $task->updated_at, '', '<p> Description: '.$task->description.'</p>', $task->tech ? $task->tech->name: $wo->technician->company_name, 'html_text', $task->tech_id ? 'Task Added For '.$task->tech->name : 'Task Added', $id);
        }

        elseif ($request->type = 'shipping_details') {
            $this->createWorkOrderTimeLog('tasks','', $task->wo_id, $task->updated_at, '', '<p> Item: '.$task->item.'</p>', $task->tech ? $task->tech->name: $wo->technician->company_name, 'html_text', $task->tech_id ? 'Task Added For '.$task->tech->name : 'Task Added', $id);
        }

        elseif ($request->type = 'send_email') {
            $this->createWorkOrderTimeLog('tasks','', $task->wo_id, $task->updated_at, '', '<p> Email: '.$task->email.'</p><br/><p> Reason: '.$task->reason.'</p>', $task->tech ? $task->tech->name: $wo->technician->company_name, 'html_text', $task->tech_id ? 'Task Added For '.$task->tech->name : 'Task Added', $id);
        }

        elseif ($request->type = 'upload_file') {
            $this->createWorkOrderTimeLog('tasks','', $task->wo_id, $task->updated_at, '', '<p> Description: '.$task->description.'</p>', $task->tech ? $task->tech->name: $wo->technician->company_name, 'html_text', $task->tech_id ? 'Task Added For '.$task->tech->name : 'Task Added', $id);
        }

        elseif ($request->type = 'upload_photo') {
            $this->createWorkOrderTimeLog('tasks','', $task->wo_id, $task->updated_at, '', '<p> Description: '.$task->description.'</p>', $task->tech ? $task->tech->name: $wo->technician->company_name, 'html_text', $task->tech_id ? 'Task Added For '.$task->tech->name : 'Task Added', $id);
        }
    }

    // useless
    public function addCloseoutNote(Request $request, $id)
    {

        $task = new Task();
        $wo = WorkOrder::find($id);

        $task->wo_id = $id;
        $task->tech_id = $wo->ftech_id;
        $task->type = 'closeout_note';
        $task->description = $request->desc;

        $task->save();

        $notify[] = ['success', 'Closeout Note Added Successfully'];
        return back()->withNotify($notify);
    }

    // useless
    public function editCloseoutNote(Request $request, $id)
    {

        $task = Task::find($id);

        $task->description = $request->desc;

        $task->save();

        $notify[] = ['success', 'Closeout Note Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function completeTask($id)
    {
        $task = Task::find($id);

        if ($task->is_completed == 0) {
            $task->is_completed = 1;
            $notify[] = ['success', 'Task Marked As Completed'];
        } else {
            $task->is_completed = 0;
            $notify[] = ['success', 'Task Marked As Incomplete'];
        }

        $task->save();

        if ($task->type = 'call') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-call-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks','task-call-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Phone: '.$task->phone.'</p><br/><p> Reason: '.$task->reason.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->is_completed ? 'Task Marked As Completed' : 'Task Marked As Incomplete', $id);
        }

        elseif ($task->type = 'collect_signature') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-collect_signature-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks','task-collect_signature-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Signature from: '.$task->from.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->is_completed ? 'Task Marked As Completed' : 'Task Marked As Incomplete', $id);
        }

        elseif ($task->type = 'custom_task') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-custom_task-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks', 'task-custom_task-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Description: '.$task->description.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->is_completed ? 'Task Marked As Completed' : 'Task Marked As Incomplete', $id);
        }

        elseif ($task->type = 'shipping_details') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-shipping_details-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks', 'task-shipping_details-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Item: '.$task->item.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->is_completed ? 'Task Marked As Completed' : 'Task Marked As Incomplete', $id);
        }

        elseif ($task->type = 'send_email') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-send_email-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks', 'task-send_email-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Email: '.$task->email.'</p><br/><p> Reason: '.$task->reason.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->is_completed ? 'Task Marked As Completed' : 'Task Marked As Incomplete', $id);
        }

        elseif ($task->type = 'upload_file') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-upload_file-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks', 'task-upload_file-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Description: '.$task->description.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->is_completed ? 'Task Marked As Completed' : 'Task Marked As Incomplete', $id);
        }

        elseif ($task->type = 'upload_photo') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-upload_photo-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks', 'task-upload_photo-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Description: '.$task->description.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->is_completed ? 'Task Marked As Completed' : 'Task Marked As Incomplete', $id);
        }
    }

    public function uploadFilePhoto(Request $request, $id)
    {
        $maxFileSize = 1024;
        if ($request->hasFile('file')) {
            $fileMime = $request->file('file')->getMimeType();
            if (str_starts_with($fileMime, 'image/')) {
                $maxFileSize = 2048;
            }
        }
    
        $request->validate([
            'type' => 'nullable|string',
            'file' => 'nullable|file|max:' . $maxFileSize,
        ], [
            'file.max' => $maxFileSize === 2048
                ? 'The image file size must not exceed 2MB.'
                : 'The file size must not exceed 1MB.',
        ]);
    
        $task = Task::find($id);
        $wo = WorkOrder::find($task->wo_id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        $fileList = [];
        $pictures = [];

        if ($task->file) {
            $fileList = json_decode($task->file, true) ?? [];
        }

        if ($wo->pictures) {
            $pictures = json_decode($wo->pictures, true) ?? [];
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Ensure the file is valid
            if ($file->isValid()) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = 'docs/tasks/' . $filename;

                // Move the file to the desired directory immediately after validation
                $file->move(public_path('docs/tasks'), $filename);

                // Check file extension to determine if it's an image
                $extension = strtolower($file->getClientOriginalExtension());
                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($extension, $imageExtensions)) {
                    $pictures[] = $filePath;
                }

                // Add the file to the file list (non-image files are also added)
                $fileList[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $filePath,
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            } else {
                // If the file is invalid, return an error
                return response()->json([
                    'success' => false,
                    'message' => 'The uploaded file is invalid or corrupted.',
                ], 400);
            }
        }

        // Update task with the file list and save
        $task->file = json_encode($fileList);

        // Update work order's pictures field with image paths and save
        $wo->pictures = json_encode($pictures);

        // Toggle the completion status of the task
        $task->is_completed = !$task->is_completed;
        $task->save();
        $wo->save();

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'task' => $task,
        ]);
    }
    
    public function uploadMoreFilePhoto(Request $request, $id, $description)
    {
        $maxFileSize = 1024;

        if ($request->hasFile('file')) {
            $fileMime = $request->file('file')->getMimeType();
            if (str_starts_with($fileMime, 'image/')) {
                $maxFileSize = 2048; // 2MB for images
            }
        }
    
        // Validate file and type
        $request->validate([
            'type' => 'nullable|string',
            'file' => 'nullable|file|max:' . $maxFileSize,
        ], [
            'file.max' => $maxFileSize === 2048
                ? 'The image file size must not exceed 2MB.'
                : 'The file size must not exceed 1MB.',
        ]);
    
        // Find the task by description (case-insensitive)
        $task = Task::where('wo_id',$id)->whereRaw('LOWER(description) = ?', [strtolower($description)])->first();
        $wo = WorkOrder::find($id);
        // If no task is found, return error
        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }
    
        // Decode the existing file list, if any
        $fileList = $task->file ? json_decode($task->file, true) : [];
        $pictures = $wo->pictures ? json_decode($wo->pictures, true) : [];
        // Handle new file upload if present
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
    
            // Store the file (you can change this to use Storage if needed)
            // Here we're saving to the 'public' directory for simplicity
            $file->move(public_path('docs/tasks'), $filename);
    
            // Add the new file details to the list
            $fileList[] = [
                'name' => $file->getClientOriginalName(),
                'path' => 'docs/tasks/' . $filename,
                'uploaded_at' => now()->toDateTimeString(),
            ];

            $extension = strtolower($file->getClientOriginalExtension());
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($extension, $imageExtensions)) {
                // Append the new image path to the existing pictures array
                $pictures[] = 'docs/tasks/'.$filename;
            }
        }
    
        // Update the task's file field with the new file list
        $task->file = json_encode($fileList);
        $wo->pictures = json_encode($pictures);
        // Toggle the task completion status
        $task->is_completed = !$task->is_completed;
        $task->save();
        $wo->save();
        // Return success response with the updated task
        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'task' => $task,
        ]);
    }
    
    public function deleteFilePhoto(Request $request, $id, $url)
    {
        $task = Task::find($id);
        $wo = WorkOrder::find($task->wo_id);

        // Decode the existing file list, if any
        $fileList = $task->file ? json_decode($task->file, true) : [];
        $pictureList = $wo->pictures ? json_decode($wo->pictures, true) : [];

        // Remove file from the task's file list based on the URL
        $fileToDelete = null;
        foreach ($fileList as $index => $file) {
            if ($file['uploaded_at'] === $url) {
                $fileToDelete = $file;
                unset($fileList[$index]); // Remove the file from the list
                break;
            }
        }
        
        // Remove picture from the work order's pictures list based on the URL
        foreach ($pictureList as $index => $picture) {
            if ($picture == $fileToDelete['path']) {
                
                unset($pictureList[$index]); // Corrected: remove the picture from the list
                break;
            }
        }

        // Delete the file from public storage (if it exists)
        $filePath = public_path($fileToDelete['path']);
        if (file_exists($filePath)) {
            unlink($filePath); // Remove the file from the public directory
        }

        // Update the task's file field with the new file list (after removal)
        $task->file = json_encode(array_values($fileList));
        
        // Update the work order's pictures field with the new picture list (after removal)
        $wo->pictures = json_encode(array_values($pictureList));
        
        // Save the task and work order after updating
        $task->save();
        $wo->save();
    }

    public function editTask(Request $request, $id)
    {
        $task = Task::find($id);
    
        // Update task properties if the respective field is present in the request
        $task->reason = $request->has('reason') ? $request->reason : $task->reason;
        $task->description = $request->has('desc') ? $request->desc : $task->description;
        $task->email = $request->has('email') ? $request->email : $task->email;
        $task->phone = $request->has('phone') ? $request->phone : $task->phone;
        $task->from = $request->has('from') ? $request->from : $task->from;
        $task->item = $request->has('item') ? $request->item : $task->item;
    
        // Handle file upload if a new file is uploaded
        if ($request->hasFile('file')) {
            // Check if the old file exists, and delete it
            if (file_exists(public_path($task->file))) {
                unlink(public_path($task->file));
            }
    
            // Handle the new file
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('docs/tasks'), $filename);
            $task->file = 'docs/tasks/' . $filename;
        }
    
        // Save the updated task
        $task->save();
    
        // Notify success and redirect back
        if ($task->type = 'call') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-call-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks','task-call-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Phone: '.$task->phone.'</p><br/><p> Reason: '.$task->reason.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->tech_id ? 'Task Updated For '.$task->tech->name : 'Task Updated', $id);
        }

        elseif ($task->type = 'collect_signature') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-collect_signature-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks','task-collect_signature-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Signature from: '.$task->from.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->tech_id ? 'Task Updated For '.$task->tech->name : 'Task Updated', $id);
        }

        elseif ($task->type = 'custom_task') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-custom_task-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks', 'task-custom_task-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Description: '.$task->description.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->tech_id ? 'Task Updated For '.$task->tech->name : 'Task Updated', $id);
        }

        elseif ($task->type = 'shipping_details') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-shipping_details-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks', 'task-shipping_details-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Item: '.$task->item.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->tech_id ? 'Task Updated For '.$task->tech->name : 'Task Updated', $id);
        }

        elseif ($task->type = 'send_email') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-send_email-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks', 'task-send_email-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Email: '.$task->email.'</p><br/><p> Reason: '.$task->reason.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->tech_id ? 'Task Updated For '.$task->tech->name : 'Task Updated', $id);
        }

        elseif ($task->type = 'upload_file') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-upload_file-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks', 'task-upload_file-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Description: '.$task->description.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->tech_id ? 'Task Updated For '.$task->tech->name : 'Task Updated', $id);
        }

        elseif ($task->type = 'upload_photo') {
            $preLog = WorkOrderTimeLog::where('column_name', 'task-upload_photo-'.$task->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tasks', 'task-upload_photo-'.$task->id, $task->wo_id, $task->updated_at, $preLog, '<p> Description: '.$task->description.'</p>', $task->tech ? $task->tech->name: '', 'html_text', $task->tech_id ? 'Task Updated For '.$task->tech->name : 'Task Updated', $id);
        }
    }
    

    public function deleteTask($id)
    {
        $task = Task::find($id);
        if($task->file && file_exists(public_path($task->file))){
            unlink(public_path($task->file));
        }

        $this->createWorkOrderTimeLog('tasks','', $task->wo_id, $task->updated_at, '', '', $task->tech ? $task->tech->name: '', 'nrml_text', $task->tech_id ? 'Task Deleted For '.$task->tech->name : 'Task Deleted', $id);

        $task->delete();
    }

    public function assignTechToTask($taskId, $techId = null)
    {
        $task = Task::find($taskId);

        if ($task) {
            if (is_numeric($techId) && $techId != 'NaN' && $techId !== 'task-list') {
                $task->tech_id = (int) $techId;
            } else {
                $task->tech_id = null;
            }

            $task->save();
        }
    }

    // Shipment
    public function createShipment(Request $request, $id)
    {
        $shipment = new OrderShipment();

        $shipment->wo_id = $id;
        $shipment->associate = $request->associate;
        $shipment->tracking_number = $request->tracking_number;
        $shipment->shipment_from = $request->shipment_from;
        $shipment->shipment_to = $request->shipment_to;
        $shipment->created_at = $request->created_at;

        $shipment->save();

        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        $this->createWorkOrderTimeLog('order_shipments','', $shipment->wo_id, $shipment->updated_at, '', 'Shipment from '.$shipment->shipment_from.' to '.$shipment->shipment_to.' issued at '.$shipment->created_at.' by '.$shipment->associate, '', 'nrml_text', 'Shipment Added', $shipment->id);
    }

    public function updateShipment(Request $request, $id)
    {
        $shipment = OrderShipment::find($id);

        $shipment->associate = $request->associate ?? $shipment->associate;
        $shipment->tracking_number = $request->tracking_number ?? $shipment->tracking_number;
        $shipment->shipment_from = $request->shipment_from ?? $shipment->shipment_from;
        $shipment->shipment_to = $request->shipment_to ?? $shipment->shipment_to;
        $shipment->created_at = $request->created_at ?? $shipment->created_at;

        $shipment->save();

        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        if($request->associate)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'associate-' . $shipment->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('order_shipments', 'associate-' . $shipment->id, $shipment->wo_id, $shipment->updated_at, $preLog, $shipment->parts_number, '', 'nrml_text', 'Shipping Method Updated', $id);
        }elseif($request->tracking_number)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'tracking_number-' . $shipment->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('order_shipments', 'tracking_number-' . $shipment->id, $shipment->wo_id, $shipment->updated_at, $preLog, $shipment->tracking_number, '', 'nrml_text', 'Shipment Tracking Number Updated', $id);
        }elseif($request->shipment_from)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'shipment_from-' . $shipment->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('order_shipments', 'shipment_from-' . $shipment->id, $shipment->wo_id, $shipment->updated_at, $preLog, $shipment->shipment_from, '', 'nrml_text', 'Shipment From Updated', $id);
        }elseif($request->shipment_to)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'shipment_to-' . $shipment->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('order_shipments', 'associate-' . $shipment->id, $shipment->wo_id, $shipment->updated_at, $preLog, $shipment->shipment_to, '', 'nrml_text', 'Shipment To Updated', $id);
        }elseif($request->created_at)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'created_at-' . $shipment->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('order_shipments', 'created_at-' . $shipment->id, $shipment->wo_id, $shipment->updated_at, $preLog, $shipment->created_at, '', 'time', 'Shipment Issue Date Updated', $id);
        }

    }

    public function deleteShipment($id)
    {
        $shipment = OrderShipment::find($id);

        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        $this->createWorkOrderTimeLog('order_shipments', '', $shipment->wo_id, $shipment->updated_at, '', 'Shipment from '.$shipment->shipment_from.' to '.$shipment->shipment_to.' issued at '.$shipment->created_at.' by '.$shipment->associate, '', 'nrml_text', 'Shipment Deleted', $id);

        $shipment->delete();
    }

    public function storeTechPart(Request $request, $id)
    {
        $validated = $request->validate([
            'part_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $wo = WorkOrder::find($id);

        if (!$wo || !$wo->ftech_id) {
            $notify[] = ['error', 'Assign a technician first'];
            return back()->withNotify($notify);
        }

        // Create a new tech part
        $techPart = new TechProvidedPart();
        $techPart->wo_id = $id;
        $techPart->tech_id = $wo->ftech_id;
        $techPart->part_name = $validated['part_name'];
        $techPart->parts_number = $request['parts_number'];
        $techPart->quantity = $validated['quantity'];
        $techPart->price = $validated['price'];
        $techPart->amount = $validated['quantity'] * $validated['price'];

        $techPart->save();

        $this->createWorkOrderTimeLog('tech_provided_parts','', $wo->id, $techPart->updated_at, '',  $techPart->part_name.' ($'.$techPart->price.' x '.$techPart->quantity.') ', '', 'nrml_text', 'Parts Information Added', $techPart->id);
    }

    public function updateTechPart(Request $request, $id)
    {

        $validated = $request->validate([
            'part_name' => 'string|max:255',
            'quantity' => 'integer|min:1',
            'price' => 'numeric|min:0',
        ]);

        $techPart = TechProvidedPart::find($id);
        $techPart->part_name = $validated['part_name'] ?? $techPart->part_name;
        $techPart->parts_number = $request['parts_number'] ?? $techPart->parts_number;
        $techPart->quantity = $validated['quantity'] ?? $techPart->quantity;
        $techPart->price = $validated['price'] ?? $techPart->price;

        if($request->quantity && $request->price)
        {
            $techPart->amount = $validated['quantity'] * $validated['price'];
        }elseif($request->quantity)
        {
            $techPart->amount = $validated['quantity'] * $techPart->price;
        }elseif($request->price)
        {
            $techPart->amount = $validated['price'] * $techPart->quantity;
        }
        
        $techPart->save();

        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        if($request->quantity)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'price-' . $techPart->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tech_provided_parts', 'parts_number-' . $techPart->id, $techPart->wo_id, $techPart->updated_at, '', $techPart->parts_number, $preLog, 'nrml_text', 'Parts Quantity Updated', $id);
        }elseif($request->price)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'price-' . $techPart->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tech_provided_parts', 'parts_number-' . $techPart->id, $techPart->wo_id, $techPart->updated_at, '', $techPart->price, $preLog, 'nrml_text', 'Parts Price Updated', $id);
        }elseif($request->part_name)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'price-' . $techPart->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tech_provided_parts', 'parts_number-' . $techPart->id, $techPart->wo_id, $techPart->updated_at, $preLog, $techPart->part_name, '', 'nrml_text', 'Parts Name Updated', $id);
        }elseif($request->parts_number)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'price-' . $techPart->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('tech_provided_parts', 'parts_number-' . $techPart->id, $techPart->wo_id, $techPart->updated_at, $preLog, $techPart->parts_number, '', 'nrml_text', 'Parts Number Updated', $id);
        }
    }

    public function deleteTechPart($id)
    {
        $techPart = TechProvidedPart::find($id);
        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        $this->createWorkOrderTimeLog('tech_provided_parts', '', $techPart->wo_id, $techPart->updated_at, '', $techPart->part_name.' ($'.$techPart->price.' x '.$techPart->quantity.') ', '', 'nrml_text', 'Part Deleted', $id);
        
        $techPart->delete();
    }

    // useless
    public function storeCloseoutNote(Request $request, $id)
    {
        $wo = WorkOrder::find($id);
        $wo->closing_note = $request->closing_note;
        $wo->save();

        $notify[] = ['success', 'Closeout Note Added Successfully'];
        return back()->withNotify($notify);
    }

    public function editTech(Request $request, $id)
    {
        $tech = Technician::find($id);

        if ($request->company_name) {
            $tech->company_name = $request->company_name ?? $tech->company_name;
        } elseif ($request->admin_name) {
            $tech->admin_name = $request->admin_name;
        }

        $tech->tech_type = $request->tech_type ?? $tech->tech_type;
        $tech->email = $request->email ?? $tech->email;
        $tech->phone = $request->phone ?? $tech->phone;

        $tech->save();
    }

    public function deleteTech($id)
    {
        $wo = WorkOrder::find($id);

        $wo->ftech_id = null;

        $wo->save();

        $notify[] = ['success', 'Technician Deleted Successfully'];
        return back()->withNotify($notify);
    }

    public function editAssignees(Request $request ,$id)
    {
        $eng = Engineer::find($id);

        $eng->name = $request->name ?? $eng->name;
        $eng->role = $request->role ?? $eng->role;
        $eng->phone = $request->phone ?? $eng->phone;
        $eng->email = $request->email ?? $eng->email;

        $eng->save();

        $notify[] = ['success', 'Technician Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function deleteAssignees($id)
    {
        $assigned = AssignedEngineer::find($id);

        if ($assigned) {
            $assigned->delete();
            $notify[] = ['success', 'Technician Deleted Successfully'];
        } else {
            $notify[] = ['error', 'Technician not found'];
        }

        return back()->withNotify($notify);
    }

    // Update Travel Cost

    public function updateTravel(Request $request ,$id)
    {
        $wo = WorkOrder::find($id);

        $wo->travel_cost = $request->travel_cost ?? $wo->travel_cost;

        $wo->save();

        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        $this->createWorkOrderTimeLog('work_orders', 'travel_cost', $id, $wo->updated_at, '', $wo->travel_cost, '', 'nrml_text', 'Travel Free Updated', $id);
    }

    // Field Tech

    public function assignTechToWo(Request $request, $id, $techId)
    {
        $wo = WorkOrder::find($id);

        $wo->ftech_id = $techId;
        $wo->stage = 3;
        $wo->status = null;
        $wo->save();

        $tech = Technician::find($techId);

        $tech->wo_ct += 1;
        $tech->save();

        if ($request->send_email == true) {
            $this->sendWorkOrder($wo->id, $tech->email);
        }
    }

    public function removeTech(Request $request, $id, $techId)
    {
        try {
            $wo = WorkOrder::find($id);
            if (!$wo) {
                return response()->json(['error' => 'Work Order not found.'], 404);
            }
    
            $wo->ftech_id = null;
            $wo->stage = 2;
            $wo->status = 2;
            $wo->save();
    
            $tech = Technician::find($techId);
            if ($tech) {
                $tech->wo_ct = max(0, $tech->wo_ct - 1);
                $tech->save();
            }
    
            if ($request->reason) {
                TechDeletionReason::create([
                    'wo_id' => $id,
                    'tech_id' => $techId,
                    'reason' => $request->reason,
                ]);
            }
    
            AssignedEngineer::where('wo_id', $id)->delete();
    
        } catch (\Exception $e) {
            Log::alert($e);
        }
    }

    public function addExpenses(Request $request, $id)
    {
        $otherExpense = new OtherExpense();
        $otherExpense->wo_id = $id;
        $otherExpense->description = $request->description;
        $otherExpense->price = $request->price;
        $otherExpense->quantity = $request->quantity ?? 1;
        $otherExpense->amount = $request->price * ($request->quantity ?? 1);

        $otherExpense->save();

        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        $this->createWorkOrderTimeLog('other_expenses','', $id, $otherExpense->updated_at, '', $otherExpense->description.' ( '.$otherExpense->price.' x '.$otherExpense->quantity.' ) ', '', 'nrml_text', 'Other Expenses Added', $otherExpense->id);
    }

    public function updateExpenses(Request $request, $id)
    {
        $otherExpense = OtherExpense::find($id);
        $otherExpense->description = $request->description ?? $otherExpense->description;
        $otherExpense->price = $request->price ?? $otherExpense->price;
        $otherExpense->quantity = $request->quantity ?? $otherExpense->quantity;
        $otherExpense->amount = ($request->price ?? $otherExpense->price) * ($request->quantity ?? $otherExpense->quantity);

        $otherExpense->save();

        if($request->description)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'other-exp-description-' . $otherExpense->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('other_expenses', 'other-exp-description-' . $otherExpense->id, $otherExpense->wo_id, $otherExpense->updated_at, $preLog, $otherExpense->description, '', 'nrml_text', 'Other Expenses Description Updated', $otherExpense->id);
        }elseif($request->price)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'other-exp-price-' . $otherExpense->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('other_expenses', 'other-exp-price-' . $otherExpense->id, $otherExpense->wo_id, $otherExpense->updated_at, $preLog, $otherExpense->price, '', 'nrml_text', 'Other Expenses Price Updated', $otherExpense->id);
        }elseif($request->quantity)
        {
            $preLog = WorkOrderTimeLog::where('column_name', 'other-exp-quantity-' . $otherExpense->id)->orderBy('id', 'desc')->first();
            $this->createWorkOrderTimeLog('other_expenses', 'other-exp-quantity-' . $otherExpense->id, $otherExpense->wo_id, $otherExpense->updated_at, $preLog, $otherExpense->quantity, '', 'nrml_text', 'Other Expenses Quantity Updated', $otherExpense->id);
        }
    }

    public function deleteExpense($id)
    {
        $otherExpense = OtherExpense::find($id);

        // (tableName, columnName, wo_id, date, preLog, value, toUser, type,  msg)
        $this->createWorkOrderTimeLog('other_expenses','', $otherExpense->wo_id, $otherExpense->updated_at, '', $otherExpense->description.' ( '.$otherExpense->price.' x '.$otherExpense->quantity.' ) ', '', 'nrml_text', 'Other Expenses Deleted', $otherExpense->id);

        $otherExpense->delete();
    }


    // Site History

}
