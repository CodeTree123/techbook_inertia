<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Form;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\CustomerSite;
use App\Models\Technician;
use App\Models\WorkOrder;
use App\Models\SkillCategory;

class UserController extends Controller
{
    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            $notify[] = 'You\'ve already completed your profile';
            return response()->json([
                'remark'=>'already_completed',
                'status'=>'error',
                'message'=>['error'=>$notify],
            ]);
        }
        $validator = Validator::make($request->all(), [
            'firstname'=>'required',
            'lastname'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark'=>'validation_error',
                'status'=>'error',
                'message'=>['error'=>$validator->errors()->all()],
            ]);
        }


        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country'=>@$user->address->country,
            'address'=>$request->address,
            'state'=>$request->state,
            'zip'=>$request->zip,
            'city'=>$request->city,
        ];
        $user->profile_complete = 1;
        $user->save();

        $notify[] = 'Profile completed successfully';
        return response()->json([
            'remark'=>'profile_completed',
            'status'=>'success',
            'message'=>['success'=>$notify],
        ]);
    }

    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = 'Your KYC is under review';
            return response()->json([
                'remark'=>'under_review',
                'status'=>'error',
                'message'=>['error'=>$notify],
            ]);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = 'You are already KYC verified';
            return response()->json([
                'remark'=>'already_verified',
                'status'=>'error',
                'message'=>['error'=>$notify],
            ]);
        }
        $form = Form::where('act','kyc')->first();
        $notify[] = 'KYC field is below';
        return response()->json([
            'remark'=>'kyc_form',
            'status'=>'success',
            'message'=>['success'=>$notify],
            'data'=>[
                'form'=>$form->form_data
            ]
        ]);
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act','kyc')->first();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $validator = Validator::make($request->all(), $validationRule);

        if ($validator->fails()) {
            return response()->json([
                'remark'=>'validation_error',
                'status'=>'error',
                'message'=>['error'=>$validator->errors()->all()],
            ]);
        }

        $userData = $formProcessor->processFormData($request, $formData);
        $user = auth()->user();
        $user->kyc_data = $userData;
        $user->kv = 2;
        $user->save();

        $notify[] = 'KYC data submitted successfully';
        return response()->json([
            'remark'=>'kyc_submitted',
            'status'=>'success',
            'message'=>['success'=>$notify],
        ]);

    }

    public function depositHistory(Request $request)
    {
        $deposits = auth()->user()->deposits();
        if ($request->search) {
            $deposits = $deposits->where('trx',$request->search);
        }
        $deposits = $deposits->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());
        $notify[] = 'Deposit data';
        return response()->json([
            'remark'=>'deposits',
            'status'=>'success',
            'message'=>['success'=>$notify],
            'data'=>[
                'deposits'=>$deposits
            ]
        ]);
    }

    public function transactions(Request $request)
    {
        $remarks = Transaction::distinct('remark')->get('remark');
        $transactions = Transaction::where('user_id',auth()->id());

        if ($request->search) {
            $transactions = $transactions->where('trx',$request->search);
        }


        if ($request->type) {
            $type = $request->type == 'plus' ? '+' : '-';
            $transactions = $transactions->where('trx_type',$type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark',$request->remark);
        }

        $transactions = $transactions->orderBy('id','desc')->paginate(getPaginate());
        $notify[] = 'Transactions data';
        return response()->json([
            'remark'=>'transactions',
            'status'=>'success',
            'message'=>['success'=>$notify],
            'data'=>[
                'transactions'=>$transactions,
                'remarks'=>$remarks,
            ]
        ]);
    }

    public function submitProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname'=>'required',
            'lastname'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark'=>'validation_error',
                'status'=>'error',
                'message'=>['error'=>$validator->errors()->all()],
            ]);
        }

        $user = auth()->user();

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country'=>@$user->address->country,
            'address'=>$request->address,
            'state'=>$request->state,
            'zip'=>$request->zip,
            'city'=>$request->city,
        ];
        $user->save();

        $notify[] = 'Profile updated successfully';
        return response()->json([
            'remark'=>'profile_updated',
            'status'=>'success',
            'message'=>['success'=>$notify],
        ]);
    }

    public function submitPassword(Request $request)
    {
        $passwordValidation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => ['required','confirmed',$passwordValidation]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark'=>'validation_error',
                'status'=>'error',
                'message'=>['error'=>$validator->errors()->all()],
            ]);
        }

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = 'Password changed successfully';
            return response()->json([
                'remark'=>'password_changed',
                'status'=>'success',
                'message'=>['success'=>$notify],
            ]);
        } else {
            $notify[] = 'The password doesn\'t match!';
            return response()->json([
                'remark'=>'validation_error',
                'status'=>'error',
                'message'=>['error'=>$notify],
            ]);
        }
    }

    public function allCustomers(Request $request)
    {
        $search = $request->query('search', '');

        $customers = Customer::select('id', 'company_name')
            ->when($search, function ($query, $search) {
                $query->where('company_name', 'like', "%{$search}%")->orWhere('customer_id', 'like', "%{$search}%");
            })
            ->orderBy('company_name', 'asc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $customers->items(), // Extract only items for Select
            'message' => 'Customers fetched successfully'
        ]);
    }
    
    public function allEmployees(Request $request)
    {
        $search = $request->query('search', '');

        $employees = Employee::select('id', 'name')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10); // Limit results to avoid overloading data

        return response()->json([
            'success' => true,
            'data' => $employees->items(), // Extract only items for the dropdown
            'message' => 'Employees fetched successfully'
        ]);
    }

    public function allSites(Request $request)
    {
        $search = $request->query('search', '');

        $sites = CustomerSite::select('id', 'site_id', 'location', 'customer_id', 'address_1', 'city', 'state', 'zipcode')
            ->when($search, function ($query, $search) {
                $query->where('site_id', 'like', "%{$search}%")->orWhere('location', 'like', "%{$search}%")->orWhere('address_1', 'like', "%{$search}%")->orWhere('city', 'like', "%{$search}%")->orWhere('state', 'like', "%{$search}%")->orWhere('zipcode', 'like', "%{$search}%");
            })
            ->orderBy('site_id', 'asc')
            ->paginate(10); // Limit results to avoid overloading data

        return response()->json([
            'success' => true,
            'data' => $sites->items(), // Extract only items for the dropdown
            'message' => 'Sites fetched successfully'
        ]);
    }

    public function customerSites(Request $request, $id)
    {
        $search = $request->query('search', '');

        $sites = CustomerSite::select('id', 'site_id', 'location', 'customer_id', 'address_1', 'city', 'state', 'zipcode')
    ->where('customer_id', $id)
    ->when($search, function ($query, $search) {
        $query->where(function ($q) use ($search) {
            $q->where('site_id', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%")
              ->orWhere('address_1', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%")
              ->orWhere('state', 'like', "%{$search}%")
              ->orWhere('zipcode', 'like', "%{$search}%");
        });
    })
    ->orderBy('site_id', 'asc')
    ->paginate(10);

     // Limit results to avoid overloading data

        return response()->json([
            'success' => true,
            'data' => $sites->items(), // Extract only items for the dropdown
            'message' => 'Sites fetched successfully'
        ]);
    }

    public function allFieldTech(Request $request)
    {
        $search = $request->query('search', '');

        $technicians = Technician::select('id', 'company_name', 'address_data', 'email', 'phone', 'rate', 'tech_type')
            ->when($search, function ($query, $search) {
                $query->where('company_name', 'like', "%{$search}%")
                    ->orWhere('technician_id', 'like', "%{$search}%")
                    ->orWhere('address_data->zip_code', 'like', "%{$search}%")
                    ->orWhere('address_data->address', 'like', "%{$search}%");
            })
            ->orderBy('company_name', 'asc')
            ->paginate(10); // Paginate the results

        return response()->json([
            'success' => true,
            'data' => $technicians->items(), // The current page's items
            'pagination' => [
                'current_page' => $technicians->currentPage(),
                'last_page' => $technicians->lastPage(),
                'per_page' => $technicians->perPage(),
                'total' => $technicians->total(),
            ],
            'message' => 'Technicians fetched successfully',
        ]);
    }

    public function allWoList(Request $request)
    {
        
        $search = $request->query('search', '');
        $stage = $request->query('stage', '');
        $w_orders = WorkOrder::select('id', 'order_id', 'created_at', 'slug', 'ftech_id', 'stage', 'status','site_id')
            ->with(['customer:id,company_name', 'technician:id,company_name','site:id,site_id,zipcode'])
            ->when($search, function ($query, $search) {
                $query->where('order_id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($query) use ($search) {
                        $query->where('company_name', 'like', "%{$search}%")
                            ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(address, '$.address')) LIKE ?", ["%{$search}%"])
                            ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(address, '$.zip_code')) LIKE ?", ["%{$search}%"]);
                    })
                    ->orWhereHas('technician', function ($query) use ($search) {
                        $query->where('company_name', 'like', "%{$search}%")
                            ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(address_data, '$.address')) LIKE ?", ["%{$search}%"])
                            ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(address_data, '$.zip_code')) LIKE ?", ["%{$search}%"]);
                    })
                    ->orWhereHas('site', function ($query) use ($search) {
                        $query->where('site_id', 'like', "%{$search}%")
                        ->orWhere('address_1', 'like', "%{$search}%")->orWhere('location', 'like', "%{$search}%")
                            ->orWhere('zipcode', 'like', "%{$search}%");
                    });
            })
            ->when($stage && $stage != 0, function ($query) use ($stage) {
                $query->where('stage', $stage);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);
    
        return response()->json([
            'w_orders' => $w_orders
        ]);
    } 
    
    public function singleSite($id)
    {
        $site = CustomerSite::select('id', 'address_1', 'city', 'state', 'zipcode', 'time_zone', 'customer_id')
    ->with('customer:id,company_name')
    ->where('id', $id)
    ->first();

        return response()->json([
            'success' => true,
            'data' => $site,
            'message' => 'Sites fetched successfully'
        ]);
    }

    public function singleCustomer($id)
    {
        $customer = Customer::find($id); 

        return response()->json([
            'success' => true,
            'data' => $customer,
            'message' => 'Sites fetched successfully'
        ]);
    }

    public function singleTech($id)
    {
        $technician = Technician::with(['skills'])->find($id);

        if ($technician) {
            unset($technician['co_ordinates']);
            return response()->json([
                'success' => true,
                'data' => $technician,
                'message' => 'Technician fetched successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Technician not found'
        ], 404);
    }

    public function allSkillSets()
    {

        $skills = SkillCategory::all(); // Paginate the results

        return response()->json([
            'success' => true,
            'data' => $skills,
            'message' => 'Skills fetched successfully',
        ]);
    }
    

}
