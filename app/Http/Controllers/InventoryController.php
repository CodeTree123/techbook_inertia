<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Inventory;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected $inventoryService;
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $pageTitle = "Tech-Yeah Inventory";
        $data = $this->inventoryService->techYeahIndex();
        if (request()->ajax()) {
            return $data;
        }
        return view('admin.inventory.index', compact('pageTitle'));
    }

    public function customerIndex(Request $request)
    {
        $pageTitle = "Customer Inventory";
        $data = $this->inventoryService->getCustomerInventory($request);
        if ($request->ajax()) {
            return $data;
        }
        return view('admin.inventory.customer_inventory.index', compact('pageTitle', 'data'));
    }

    public function customerDetails(Request $request)
    {
        $result = $this->inventoryService->getCustomerDetails($request);
        if ($result) {
            return response()->json($result);
        }
    }

    public function create()
    {
        $pageTitle = "Add Inventory Item";
        $customers = Customer::all();
        return view('admin.inventory.create', compact('pageTitle', 'customers'));
    }

    public function store(Request $request)
    {
        $result = $this->inventoryService->insertItems($request);
        if (isset($result['errors'])) {
            return response()->json(['errors' => $result['errors']], 422);
        }
        return response()->json(['success' => 'Inventory items added successfully'], 200);
    }

    public function edit($id)
    {
        $data = $this->inventoryService->editInventory($id);
        return view('admin.inventory.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $updatedInventory = $this->inventoryService->updateItems($request, $id);
        if ($updatedInventory) {
            $notify[] = ['success', 'Item Updated successfully.'];
            if ($updatedInventory->customer_id === null) {
                return redirect()->route('inventory.techyeah.index')->withNotify($notify);
            } else {
                return redirect()->route('inventory.customer.index')->withNotify($notify);
            }
        }
    }

    public function destroy($id)
    {
        $deletedItem = $this->inventoryService->deleteItems($id);
        if ($deletedItem) {
            $notify[] = ['success', 'Item deleted successfully.'];
            return redirect()->back()->withNotify($notify);
        }
    }

    public function customerAutocomplete(Request $request)
    {
        $data = $this->inventoryService->autoComplete($request);
        return response()->json(['results' => $data], 200);
    }

    public function itemDetails(Request $request)
    {
        $data = $this->inventoryService->viewItem($request);
        return response()->json(['details' => $data], 200);
    }
}
