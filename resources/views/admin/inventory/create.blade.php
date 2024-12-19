@extends('admin.layoutsNew.app')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
                        <a href="{{ route('inventory.techyeah.index')}}" class="btn btn-primary btn-sm p-2"> <i class="fas fa-arrow-left"></i> Back</a>
                        <button class="btn btn-primary addBtn" type="button" style="float:right"><i class="fas fa-plus"></i> Add More</button>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('inventory.item.store') }}" id="itemForm" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <label for="">Select Owner</label>
                                    <input type="text" id="searchInput" placeholder="Start typing to search....." class="form-control">
                                    <input type="hidden" name="customer_id" id="customer_id">
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Item Name</label>
                                    <input type="text" class="form-control item_name" placeholder="Enter item name" name="item_name[]">
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Manufacturer</label>
                                    <input type="text" class="form-control manufacturer" placeholder="Enter manufacturer name" name="manufacturer[]">
                                </div>
                            </div>
                            <div id="container">
                                <div class="form-row mt-1">
                                    <div class="form-group col-4">
                                        <label for="">Part Number</label>
                                        <input type="text" class="form-control part_number" placeholder="Enter part number" name="part_number[]">
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="">Quantity</label>
                                        <input type="number" class="form-control quantity" placeholder="Enter item quantity" name="quantity[]">
                                        <span style="color:red; font-size:14px" class="quantity"></span>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="">Unit Cost</label>
                                        <input type="number" class="form-control unit_cost" placeholder="Enter unit cost" name="unit_cost[]">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="">Description</label>
                                        <textarea class="form-control description" placeholder="Enter item description" name="description[]"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100"> <i class="fas fa-paper-plane"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none" id="elements">
        <div class="row mt-1" id="removable-container">
            <div class="form-group col-12">
                <button class="btn btn-danger removeBtn" type="button" style="float:right"><i class="fas fa-minus"></i> Remove</button>
                <button class="btn btn-primary addBtn mx-1" type="button" style="float:right"><i class="fas fa-plus"></i> Add More</button>
            </div>
            <div class="form-group col-4">
                <label for="">Item Name</label>
                <input type="text" class="form-control item_name" placeholder="Enter item name" name="item_name[]">
            </div>
            <div class="form-group col-4">
                <label for="">Manufacturer</label>
                <input type="text" class="form-control manufacturer" placeholder="Enter manufacturer name" name="manufacturer[]">
            </div>
            <div class="form-group col-4">
                <label for="">Part Number</label>
                <input type="text" class="form-control part_number" placeholder="Enter part number" name="part_number[]">
            </div>
            <div class="form-group col-6">
                <label for="">Quantity</label>
                <input type="number" class="form-control quantity" placeholder="Enter item quantity" name="quantity[]">
                <span style="color:red; font-size:14px" class="quantity"></span>
            </div>
            <div class="form-group col-6">
                <label for="">Unit Cost</label>
                <input type="number" class="form-control unit_cost" placeholder="Enter unit cost" name="unit_cost[]">
            </div>
            <div class="form-group col-12">
                <label for="">Description</label>
                <textarea class="form-control description" placeholder="Enter item description" name="description[]"></textarea>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        
        $(document).on('click', '.addBtn', function() {
            let html = $('#elements').html();
            $('#container').append(html);
        });

        $(document).on('click', '.removeBtn', function() {
            $(this).closest('#removable-container').remove();
        });

        $(document).on('submit', '#itemForm', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('inventory.item.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $(".item_name, .manufacturer, .part_number, .quantity, .unit_cost, .description").val("");
                    iziToast.success({
                        message: data.success,
                        position: "topRight"
                    });
                },
                error: function(xhr) {
                    if (xhr.status == 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.error-message').text('');
                        $.each(errors, function(key, value) {
                            if (key.includes('.')) {
                                let parts = key.split('.');
                                let arrayFieldName = parts[0];
                                let arrayIndex = parts[1];
                                $.each(value, function(index, message) {
                                    let errorMessage = '<span class="error-message text-danger">' + message + '</span>';
                                    $('[name="' + arrayFieldName + '[]"]:eq(' + arrayIndex + ')').after(errorMessage);
                                });
                            } else {
                                $.each(value, function(index, message) {
                                    let errorMessage = '<span class="error-message text-danger">' + message + '</span>';
                                    $('[name="' + key + '"]').after(errorMessage);
                                });
                            }
                        });
                    }
                }
            });
        });

        $(document).on('change', '#itemForm input', function() {
            let fieldName = $(this).attr('name');
            $(this).siblings('[name="' + fieldName + '"]').text('');
        });

        $('#searchInput').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('inventory.customer.autocomplete') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {
                            return {
                                label: item.company_name + "-" + item.customer_id,
                                value: item.company_name + "-" + item.customer_id,
                                customerID: item.id,
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedCustomerId = ui.item.customerID;
                $('#customer_id').val(selectedCustomerId);
            }
        });
    });
</script>
@endsection