@extends('admin.layoutsNew.app')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endsection

@section('content')
<div class="content-wrapper" style="background-color: white;">
    @include('admin.includeNew.breadcrumb')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('inventory.add.item') }}" class="btn btn-primary btn-sm" style="float: right;"><i class="fas fa-plus"></i>
                            Add Item</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tech-yeah-items">
                                <thead>
                                    <tr class="text-nowrap text-center">
                                        <th class="text-center">#</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Manufacturer</th>
                                        <th class="text-center">Unit Cost</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center" style="width:50px">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-nowrap text-center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- delete confirmation modal --}}
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
                <div class="modal-body  ">
                    <img class="text-center" style="display: block; margin: 0 auto; width: 100px; height: 70px;" src="https://techyeahinc.com/assets/media/logo/tech_yeah_logo.png" alt="">

                    <h4 class="text-center">Tech Yeah Inventory</h4>
                    <div class="form-row col-12 ">
                        <div class="form-group col-8 mt-4   ">
                            <label for="">Item Name :</label>
                            <span class="ml-2" id="item-name"></span><br>
                            <label for="">Manufacturer :</label>
                            <span class="ml-2" id="manufacturer"></span><br>
                            <label for="">Part Number :</label>
                            <span class="ml-2" id="part-number"></span><br>
                            <label for="">TY Part Number :</label>
                            <span class="ml-2" id="ty-part-number"></span><br>
                        </div>
                        <div class="form-group col-4 mt-4">

                            <label for="">Unit Cost :</label>
                            <span class="ml-2" id="unit-cost"></span><br>
                            <label for="">Quantity :</label>
                            <span class="ml-2" id="quantity"></span><br>
                            <label for="">Description :</label>
                            <span class="ml-2" id="description"></span>
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
    $(document).ready(function() {
        renderTable();

        function renderTable() {
            $('#tech-yeah-items').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('inventory.techyeah.index') !!}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'item_name'
                    },
                    {
                        data: 'manufacturer'
                    },
                    {
                        data: 'unit_cost'
                    },
                    {
                        data: 'quantity'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<div class="button-container d-flex align-items-center">' +
                                '<div class="dropdown">' +
                                '<i class="fas fa-ellipsis-v custom-icon mx-4" id="dropdownMenuButton_' + row.id + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton_' + row.id + '">' +

                                '<a class="dropdown-item no-modal view-button" style="cursor:pointer" data-id="' + row.id + '"> <i class="fas fa-eye"></i> View</a>' +
                                '<a class="dropdown-item no-modal edit-button" style="cursor:pointer" data-id="' + row.id + '"><i class="fas fa-edit"></i> Edit</a>' +
                                '<a class="dropdown-item no-modal delete-button" style="cursor:pointer" data-id="' + row.id + '"><i class="fas fa-trash"></i> Delete</a>'
                            '</div>' +
                            '</div>' +
                            '</div>';
                        }
                    },
                ],
                columnDefs: [{
                    "targets": [5],
                    "orderable": false
                }]
            });
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

        //view item
        $(document).on('click', '.view-button', function() {
            let id = $(this).data('id');
            getItemDetails(id);
            $('#item-view-modal').modal('show');
        });

        function getItemDetails(id) {
            if (id) {
                $.ajax({
                    url: "{{ route('inventory.item.details') }}",
                    type: "GET",
                    dataType: "JSON",
                    data: {
                        "id": id,
                        "owner": "TY"
                    },
                    success: function(response) {
                        $('#item-name').text(response.details[0].item_name);
                        $('#manufacturer').text(response.details[0].manufacturer);
                        $('#part-number').text(response.details[0].part_number);
                        $('#ty-part-number').text(response.details[0].ty_part_number);
                        $('#description').text(response.details[0].description);
                        $('#unit-cost').text(response.details[0].unit_cost);
                        $('#quantity').text(response.details[0].quantity);
                    }
                });
            }
        }
    });
</script>
@endsection