<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    //
    public function item(){
        $pageTitle = 'Item';
        return view('admin.item.index',compact('pageTitle'));
    }

    public function itemStore(Request $request){
        $this->validate($request, [
           'item_name' => 'required',
           'hsn/sec' => 'required',
           'item_type_id' => 'required',
           'category_id' => 'required',
           'unit_id' => 'required', 
        ]);

        $items = new Item();

        $items->item_name = $request->item_name;
        $items->hsn_sec = $request->hsn_sec;
        $items->item_type_id = $request->item_type_id;
        $items->category_id = $request->category_id;
        $items->save();

    }
}
