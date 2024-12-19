<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function employee()
    {
        $pageTitle = "Employee";
        $employees = Employee::latest()->searchable(['name', 'mobile', 'email', 'employee_id'])->paginate(getPaginate());
        return view('admin.employee.view', compact('pageTitle', 'employees'));
    }

    public function employeeEdit($id)
    {
        $pageTitle = "Employee edit";
        $employees = Employee::find($id);
        return view('admin.employee.edit', compact('pageTitle', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $em = Employee::find($id);
        $em->name = $request->name;
        $em->email = $request->email;
        $em->mobile = $request->mobile;
        $em->save();
        $notify[] = ['success', 'Updated successful'];
        return back()->withNotify($notify);
    }

    public function addEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'mobile' => 'required|string|max:15|unique:employees,mobile',
        ]);

        $em = new Employee();

        DB::beginTransaction();
        try {
            $latestEmployee = Employee::orderBy('employee_id', 'desc')->lockForUpdate()->first();
            if ($latestEmployee) {
                $newId = str_pad(((int)$latestEmployee->employee_id + 1), 6, '0', STR_PAD_LEFT);
            } else {
                $newId = '000001';
            }
            $em->employee_id = $newId;
            $em->name = $request->name;
            $em->email = $request->email;
            $em->mobile = $request->mobile;
            $em->save();

            DB::commit();
            $notify[] = ['success', 'Added successfully'];
        } catch (\Exception $e) {
            DB::rollBack();
            $notify[] = ['error', 'Failed to add employee'];
        }

        return back()->withNotify($notify);
    }



    public function getEmployeeSearch(Request $request)
    {
        $query = $request->input('query');

        $results = Employee::Where('name', 'like', '%' . $query . '%')
            ->limit(10)
            ->get();

        return response()->json(['results' => $results], 200);
    }

    public function workOrder($id)
    {
        $pageTitle = "Employee Work Order";
        $workOrder = WorkOrder::where('em_id', $id)->searchable(['order_id'])->paginate(getPaginate());
        return view('admin.employee.work_order', compact('pageTitle', 'workOrder'));
    }
    public function workOrderView($id)
    {
        $pageTitle = "Employee Work Order View";
        $workOrder = WorkOrder::where('id', $id)->first();
        $imageFileNames = json_decode($workOrder->pictures);
        return view('admin.employee.work_order_view', compact('pageTitle', 'workOrder', 'imageFileNames'));
    }
}
