@extends('admin.layoutsNew.app')
@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<link rel="stylesheet" href="{{ asset('assetsNew/main_css/technician/index.css') }}">

@endsection
@section('content')
<div class="content-wrapper" style="background-color: white;">
    <!-- Content Header (Page header) -->
    @include('admin.includeNew.breadcrumb')
    <!-- /.content-header -->
    <div class="modal fade text-dark" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-gray">
                    <h4 class="modal-title mx-2" id="staticBackdropLabel"><span class="text-light">Technician
                            Profile</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mb-5" style="max-height: 400px; overflow-y: auto;">
                    <div id="tech-data"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-mb-12 d-flex justify-content-between align-items-center">
                                <h3 class="text-dark mb-0">Engineer List</h3>
                                <div>
                                    <a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEngineer"> <i
                                            class="fas fa-plus-circle"></i>
                                        Add Engineer</a>
                                    <div class="modal fade" id="addEngineer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Add Engineer</h5>
                                                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('technician.engineer.add')}}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="tech_id" class="form-label">Select Technician</label>
                                                            <select type="text" class=" select2" data-live-search="true" data-width="100%" data-size="5" id="tech_id" name="tech_id" placeholder="Enter Name">
                                                                <option value="">--Select a technician--</option>
                                                                @foreach($technicians as $tech)
                                                                <option value="{{$tech->id}}">{{$tech->company_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Name</label>
                                                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="role" class="form-label">Role</label>
                                                            <input type="text" class="form-control" id="role" name="role" placeholder="Enter Role">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="phone" class="form-label">Phone</label>
                                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="avatar" class="form-label">Avatar</label>
                                                            <input type="file" class="form-control" id="avatar" name="avatar" placeholder="Enter Phone">
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Create</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-dark">
                        <div class="table-responsive">
                            <table class="table table-hover bottomBorder" style=" cursor:pointer">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Engineer</th>
                                        <th>Company Name</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($engineers as $key => $engineer)
                                    <tr>
                                        <td>{{ ($engineers->currentPage() - 1) * $engineers->perPage() + $key + 1 }}</td>
                                        <td class='p-2'>
                                            <div class='d-flex align-items-center gap-2'>
                                                @if($engineer->avatar)
                                                <img src="{{asset($engineer->avatar)}}" style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;" alt="">
                                                @else
                                                <div class="d-flex justify-content-center align-items-center" style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%; background-color: grey">
                                                    {{ $engineer->name ? substr($engineer->name, 0, 1) : '' }}
                                                </div>
                                                @endif
                                                {{$engineer->name}}
                                            </div>
                                        </td>
                                        <td>{{$engineer->technician->company_name}}</td>
                                        <td>{{$engineer->role}}</td>
                                        <td><a href="mailto:{{$engineer->email}}">{{$engineer->email}}</a></td>
                                        <td><a href="callto:{{$engineer->phone}}">{{$engineer->phone}}</a></td>
                                        <td>
                                            <div
                                                class="button-container d-flex align-items-center mt-1 justify-content-center">
                                                <div class="dropdown">
                                                    <i class="fas fa-ellipsis-v custom-icon mx-4"
                                                        style="cursor: pointer;" id="dropdownMenuButton"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false"></i>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <button class="dropdown-item" type="button"
                                                            data-toggle="modal" data-target="#editEngineer-{{$engineer->id}}">
                                                            Edit
                                                        </button>

                                                        <div class="modal fade" id="editEngineer-{{$engineer->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Engineer</h5>
                                                                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{route('technician.engineer.add')}}" method="post" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <div class="mb-3">
                                                                                <label for="tech_id" class="form-label">Select Technician</label>
                                                                                <select type="text" class=" select2" data-live-search="true" data-width="100%" data-size="5" id="tech_id" name="tech_id" placeholder="Enter Name">
                                                                                    <option value="">--Select a technician--</option>
                                                                                    @foreach($technicians as $tech)
                                                                                    <option value="{{$tech->id}}" @if($engineer->id == $tech->id) selected @endif>{{$tech->company_name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="name" class="form-label">Name</label>
                                                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="role" class="form-label">Role</label>
                                                                                <input type="text" class="form-control" id="role" name="role" placeholder="Enter Role">
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="email" class="form-label">Email</label>
                                                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="phone" class="form-label">Phone</label>
                                                                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone">
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="avatar" class="form-label">Avatar</label>
                                                                                <input type="file" class="form-control" id="avatar" name="avatar" placeholder="Enter Phone">
                                                                            </div>
                                                                            <div class="d-flex gap-2">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit" class="btn btn-primary">Create</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <a class="dropdown-item no-modal" title="Delete"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteConfirmationModal"
                                                            data-delete-url=""
                                                            style="cursor:pointer">
                                                            Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($engineers->hasPages())
                    <div class="card-footer py-4">
                        <p class="text-italic">Click below to see next page</p> @php echo paginateLinks($engineers) @endphp
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Section For confirm Delete -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a id="deleteLink" href="#" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#deleteConfirmationModal').on('show.bs.modal', function(event) {
            var link = $(event.relatedTarget);
            var deleteUrl = link.data('delete-url');
            var deleteButton = $('#deleteLink');
            deleteButton.attr('href', deleteUrl);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let id = null;
        let shouldOpenModal = true;

        function openModal() {
            if (shouldOpenModal) {

                $.ajax({
                    url: "{{ route('technician.get.profile') }}",
                    type: "POST",
                    data: {
                        'tech_id': id
                    },
                    dataType: "JSON",
                    success: function(res) {
                        /*let starRatingHTML = '<b class="rate-technician">Rate this technician:</b>';
                        for (let i = 1; i <= 5; i++) {
                            if (i <= res.technician.review.star_value) {
                                starRatingHTML +=
                                    '<i class="fas fa-star text-warning star-rating" data-rating="' +
                                    i + '"></i> ';
                            } else {
                                starRatingHTML +=
                                    '<i class="far fa-star  text-info star-rating-pre" data-rating="' +
                                    i + '"></i> ';
                            }
                        }*/
                        let rate = [];
                        $.each(res.technician.rate, function(key, value) {
                            rate.push(key + ': ' + value);
                        });
                        rate.join(', ');

                        let html = '<div class="technician_border">';
                        html += '<div>';
                        html += '<div class="row">';
                        //One------------------------------Create three columns
                        html += '<div class="col-md-7">';
                        html += '<p><strong>C WorkOrder Count:</strong> ' + res.technician.c_wo_ct + '</p>';
                        html += '<p><strong>WorkOrder Count:</strong> ' + res.technician.wo_ct + '</p>';
                        html += '<p><strong>Technician Id:</strong> ' + res.technician
                            .technician_id + '</p>';
                        html += '<p><strong>Company Name:</strong> ' + res.technician.company_name +
                            '</p>';
                        html += '<p><strong>City:</strong> ' + res.technician.address_data.city +
                            '</p>';
                        html += '<p class=""><strong>Primary Contract:</strong> ' + res.technician
                            .primary_contact + '</p>';
                        html += '<p ><strong>Email:</strong> ' + res.technician.email + '</p>';
                        html += '<p ><strong>MSA Expire Date:</strong> ' + res.technician
                            .msa_expire_date + '</p>';
                        html += '<p><strong>Created At:</strong> ' + res.technician.created_at +
                            '</p>';
                        html += '<p class=""><strong>COI Expire Date:</strong> ' + res.technician
                            .coi_expire_date + '</p>';
                        html += '<p ><strong>Country:</strong> ' + res.technician.address_data
                            .country + '</p>';
                        html += '<p ><strong>Travel Fee:</strong> ' + res.technician.travel_fee +
                            '</p>';
                        html += '<p><strong>USD:</strong> ' + rate + '</p>';
                        html += '</div>';
                        //Two --------------------------------------
                        html += '<div class="col-md-5 column2">';
                        html += '<p><strong>Title:</strong> ' + res.technician.title + '</p>';
                        html += '<p><strong>Skill Sets:</strong> ' + res.skill_sets + '</p>';
                        html += '<p><strong>Status:</strong> ' + res.technician.status + '</p>';
                        html += '<p><strong>Address:</strong> ' + res.technician.address_data
                            .address + '</p>';
                        html += '<p><strong>State:</strong> ' + res.technician.address_data.state +
                            '</p>';
                        html += '<p><strong>Phone:</strong> ' + res.technician.phone + '</p>';
                        html += '<p ><strong>Cell Phone:</strong> ' + res.technician.cell_phone +
                            '</p>';
                        html += '<p><strong>Zip Code:</strong> ' + res.technician.address_data
                            .zip_code + '</p>';
                        html += '<p><strong>NDA:</strong> ' + res.technician.nda + '</p>';
                        html += '<p><strong>Radius:</strong> ' + res.technician.radius + '</p>';
                        html += '<p><strong>Terms:</strong> ' + res.technician.terms + '</p>';
                        html += '</div>';
                        html += '<div>';
                        // html += '<div class="mt-3">' + starRatingHTML + '</div>';
                        // html += '<span>Comments :' + res.technician.review.comments + '</span>';
                        // html +=
                        //     '<textarea class="form-control " id="comment" rows="4" style="resize: none; border: 1px solid black;  border-radius: 5px; padding: 5px;" placeholder="Give a comment about this technician..."></textarea>';
                        // html +=
                        //     '<button type="button" class="btn bg-gray mt-3 w-100 " id="comment-btn"> <i class="fas fa-pencil-alt"></i> Update</button>';
                        // html += '</div>';
                        $('#tech-data').html(html);
                        $('#staticBackdrop').modal('show');
                    }
                });
            }
            shouldOpenModal = true;
        }
        $('table tbody tr').click(function(event) {
            let row = $(this);
            let dataValue = row.data('value');
            id = dataValue;
            openModal();
        });

        $('table tbody tr button, table tbody tr a, table tbody tr i').click(function(event) {
            shouldOpenModal = false;
        });

        // $(document).on('click', '.fa-star', function() {
        //     let newRating = $(this).data('rating');
        //     $.ajax({
        //         url: "{{ route('technician.star.update') }}",
        //         type: "POST",
        //         data: {
        //             'star_value': newRating,
        //             'tech_id': id
        //         },
        //         dataType: "JSON",
        //         success: function(res) {
        //             openModal();
        //         }
        //     });
        // });
        // $(document).on('click', '#comment-btn', function() {
        //     let comment = $('#comment').val();
        //     $.ajax({
        //         url: "{{ route('technician.comment.update') }}",
        //         type: "POST",
        //         data: {
        //             'comment': comment,
        //             'tech_id': id
        //         },
        //         dataType: "JSON",
        //         success: function(res) {
        //             openModal();
        //         }
        //     });
        // });
    });
</script>

@endsection

@push('breadcrumb-plugins')
<p class="font-weight-light p-2 m-2">Search by Technician Id, Company Name or Zipcode :</p>
<x-search-form dateSearch='no' />
@endpush