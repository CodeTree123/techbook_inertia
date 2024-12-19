@extends('admin.layoutsNew.app')
@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<link rel="stylesheet" href="{{asset('assetsNew/main_css/customer/customer_zone.css')}}">
@endsection
@section('content')
<div class="content-wrapper" style="background-color: white;">
    @include('admin.includeNew.breadcrumb')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('inventory.customer.index')}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i>&nbsp;Back</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('inventory.item.update', $inventory->id) }}" id="itemForm" method="POST">
                            @csrf
                            <div id="container">
                                <div class="row mt-1">
                                    <div class="form-group col-12">
                                        <h4 style="font-weight: bold;">{{ $company }}</h4>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="">Item Name</label>
                                        <input type="text" class="form-control" placeholder="Enter item name" name="item_name" value="{{ $inventory->item_name }}">
                                        @error('item_name')
                                        <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="">Manufacturer</label>
                                        <input type="text" class="form-control" placeholder="Enter manufacturer name" name="manufacturer" value="{{ $inventory->manufacturer }}">
                                        @error('manufacturer')
                                        <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="">Part Number</label>
                                        <input type="text" class="form-control" placeholder="Enter part number" name="part_number" value="{{ $inventory->part_number }}">
                                        @error('part_number')
                                        <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="">Quantity</label>
                                        <input type="number" class="form-control" placeholder="Enter item quantity" name="quantity" value="{{ $inventory->raw_quantity }}">
                                        @error('quantity')
                                        <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="">Unit Cost</label>
                                        <input type="number" class="form-control" placeholder="Enter unit cost" name="unit_cost" value="{{ $inventory->raw_unit_cost }}">
                                        @error('unit_cost')
                                        <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12">
                                        <label for="">Description</label>
                                        <textarea class="form-control" placeholder="Enter item description" name="description">{{ $inventory->description }}</textarea>
                                        @error('description')
                                        <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sync-alt"></i>
                                        Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection