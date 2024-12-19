<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Inventory;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class InventoryService
{
    public function techYeahIndex()
    {
        $items = Inventory::select('id', 'item_name', 'manufacturer', 'unit_cost', 'quantity')->whereNull('customer_id');
        return DataTables::of($items)
            ->addIndexColumn()
            ->make(true);
    }

    public function getCustomerInventory($request)
    {
        $items = Inventory::select('id', 'customer_id', 'item_name', 'quantity', 'manufacturer', 'unit_cost')
            ->where('customer_id', $request->customer_id)
            ->get();

        $items = $items->map(function ($item, $index) {
            $item['serial_number'] = $index + 1;
            return $item;
        });

        return DataTables::of($items)->make(true);
    }
    public function insertItems($request)
    {
        $requestData = $request->all();

        $rules = [
            'item_name.*' => 'required',
            'description.*' => 'required',
            'quantity.*' => 'required',
            'unit_cost.*' => 'required',
            'part_number.*' => 'required',
            'manufacturer.*' => 'required'
        ];
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $filteredArray = array_filter($requestData['item_name'], function ($value) {
            return $value !== null;
        });

        $countItem = count($filteredArray);
        if ($countItem > 0) {
            for ($i = 0; $i < $countItem; $i++) {
                $inventory = new Inventory();
                $inventory->customer_id = $requestData['customer_id'];
                $inventory->item_name = $requestData['item_name'][$i];
                $inventory->description = $requestData['description'][$i];
                $inventory->quantity = $requestData['quantity'][$i];
                $inventory->part_number = $requestData['part_number'][$i];
                $inventory->ty_part_number = $requestData['part_number'][$i] . "-TY";
                $inventory->manufacturer = $requestData['manufacturer'][$i];
                $inventory->unit_cost = $requestData['unit_cost'][$i];
                $inventory->save();
            }
        }

        return ['success' => 'Inventory items added successfully'];
    }

    public function editInventory($id)
    {
        $pageTitle = "Edit Inventory Item";
        $inventory = Inventory::with('customer')->findOrFail($id);
        $company = $inventory->customer ? $inventory->customer->company_name : "Tech Yeah";

        return compact('inventory', 'pageTitle', 'company');
    }

    public function updateItems($request, $id)
    {
        $request->validate([
            'item_name' => 'required',
            'description' => 'required',
            'quantity' => 'required',
            'unit_cost' => 'required',
            'part_number' => 'required',
            'manufacturer' => 'required'
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->item_name = $request->item_name;
        $inventory->description = $request->description;
        $inventory->quantity = $request->quantity;
        $inventory->part_number = $request->part_number;
        $inventory->manufacturer = $request->manufacturer;
        $inventory->unit_cost = $request->unit_cost;
        $inventory->save();

        return $inventory;
    }

    public function getCustomerDetails($request)
    {
        $inventoryItems = Inventory::with('customer')
            ->where('customer_id', $request->customer_id)
            ->get();

        $companyNames = $inventoryItems->map(function ($item) {
            $id = $item->customer->customer_id;
            $name = $item->customer->company_name;
            return $name . "-" . $id;
        })->unique();
        return $companyNames;
    }

    public function deleteItems($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();
        return $inventory;
    }

    public function autoComplete($request)
    {
        $query = $request->input('query');
        $results = Customer::select('id', 'customer_id', 'company_name')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('company_name', 'like', '%' . $query . '%')
                    ->orWhere('customer_id', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get();

        $staticData = [
            [
                'id' => null,
                'customer_id' => '00000',
                'company_name' => 'Tech-Yeah',
            ]
        ];

        $mergedResult = array_merge($staticData, $results->toArray());

        if ($request->input('customer') === "1") {
            return $results;
        } else {
            return $mergedResult;
        }
    }

    public function viewItem($request)
    {
        if ($request->owner === "TY") {
            $TYDetails = Inventory::whereNull('customer_id')
                ->where('id', $request->id)
                ->get();
            return $TYDetails;
        } else {
            $customerDetails = Inventory::with('customer')
                ->where('id', $request->id)
                ->get();
            return $customerDetails;
        }
    }
}
