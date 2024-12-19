@extends('admin.layoutsNew.app')
@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('assetsNew/main_css/customer/customer_zone.css')}}">
<style>
    .table>:not(:last-child)>:last-child>* {
        border-bottom-color: #ccc;
    }
</style>
@endsection
@section('content')
<div class="content-wrapper" style="background-color: white;">
    @include('admin.includeNew.breadcrumb')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a style="float: right;" href="{{ route('inventory.add.item') }}" class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i>
                             Add Item</a>
                    </div>
                    <div class="card-body">
                        <label for="">Select Customer</label>
                        <input type="text" id="searchInput" placeholder="Start typing to search....." class="form-control col-4">
                        <div class="mt-5">
                            <div class="col-12 text-center">
                                <h4 id="company-name" style="font-weight: bold;"></h4>
                            </div>
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Manufacturer</th>
                                        <th class="text-center">Unit Cost</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    {{-- inventory item view modal --}}
    <div class="modal fade" id="item-view-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="staticBackdropLabel">Inventory Item Details</h5> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4>Customer Inventory</h4>
                <h4 id="customer-name"></h4>
                <h4 id="customerId"></h4>
                <div class="form-row col-12">
                <div class="form-group col-6">
                    <label for="">Item Name</label>
                    <span class="ml-2" id="item-name"></span><br>
                    <label for="">Manufacturer</label>
                    <span class="ml-2" id="manufacturer"></span><br>
                    <label for="">Part Number</label>
                    <span class="ml-2" id="part-number"></span><br>
                    <label for="">TY Part Number</label>
                    <span class="ml-2" id="ty-part-number"></span><br>
                </div>
                <div class="form-group col-6">
                    <label for="">Description</label>
                    <span class="ml-2" id="description"></span><br>
                    <label for="">Unit Cost</label>
                    <span class="ml-2" id="unit-cost"></span><br>
                    <label for="">Quantity</label>
                    <span class="ml-2" id="quantity"></span><br>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
</div>
<script>
    let dataTable = null;
    $(document).ready(function() {
        function renderTable(customer_id) {
            dataTable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('inventory.customer.index') }}",
                    data: {
                        customer_id: customer_id
                    },
                },
                columns: [
                    {
                        data: 'serial_number',
                        name: 'serial_number'
                    },
                    { data: 'item_name' },
                    { data: 'manufacturer' },
                    { data: 'unit_cost' },
                    { data: 'quantity' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<div class="button-container d-flex align-items-center">' +
                                '<div class="dropdown">' +
                                '<i class="fas fa-ellipsis-v custom-icon mx-4" id="dropdownMenuButton_' + row.id + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton_' + row.id + '">' +
                                '<a class="dropdown-item no-modal edit-button" data-id="' + row.id + '">Edit</a>' +
                                '<a class="dropdown-item no-modal delete-button" data-id="' + row.id + '">Delete</a>' +
                                '<a class="dropdown-item no-modal view-button" data-id="' + row.id + '">View</a>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        }
                    },
                ],
                columnDefs:[
                    {
                        "targets": [5],
                        "orderable": false 
                    }
                ]
            });  
        }

        function initializeDataTable(customer_id) {
            if (dataTable !== null) {
                dataTable.destroy();
            }
            renderTable(customer_id);
        }
        //delete button
        $(document).on('click', '.delete-button', function() {
            let id = $(this).data('id');
            let route = "{{ route('inventory.item.destroy',':id') }}";
            route = route.replace(':id', id);
            $('#confirmDelete').attr('href', route);
            $('#staticBackdrop').find('#confirmDelete').attr('href', route);
            $('#staticBackdrop').modal('show');
        });

        //edit button
        $(document).on('click', '.edit-button', function() {
            let id = $(this).data('id');
            let route = "{{ route('inventory.item.edit',':id') }}";
            route = route.replace(':id', id);
            window.location.href = route;
        });

        $(document).on('click','.view-button',function(){
            let id = $(this).data('id');
            getItemDetails(id);
            $('#item-view-modal').modal('show');
        });

        function getItemDetails(id){
            if(id){
                $.ajax({
                    url: "{{ route('inventory.item.details') }}",
                    type: "GET",
                    dataType: "JSON",
                    data:{
                        "id": id,
                        "owner": "customer"
                    },
                    success:function(response){
                        $('#item-name').text(response.details[0].item_name);
                        $('#manufacturer').text(response.details[0].manufacturer);
                        $('#part-number').text(response.details[0].part_number);
                        $('#ty-part-number').text(response.details[0].ty_part_number);
                        $('#description').text(response.details[0].description);
                        $('#unit-cost').text(response.details[0].unit_cost);
                        $('#quantity').text(response.details[0].quantity);
                        $('#customer-name').text(response.details[0].customer.company_name);
                        $('#customerId').text(response.details[0].customer.customer_id);
                    }
                });
            }
        }

        $('#searchInput').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('inventory.customer.autocomplete') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                        "customer": "1"
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
                initializeDataTable(selectedCustomerId);
                printHeader(selectedCustomerId);
            }
        });
        
        function printHeader(data){
            if(data){
                $.ajax({
                    url: "{{ route('inventory.customer') }}",
                    type: "GET",
                    dataType: "JSON",
                    data:{
                        "customer_id": data
                    },
                    success:function(response){
                        if(response.length > 0){
                            $('#company-name').text(response);
                        }else{
                            $('#company-name').text('No inventory items found for this customer.');
                        }
                    }
                });
            }
        }
        initializeDataTable(0);
    });
</script>
@endsection