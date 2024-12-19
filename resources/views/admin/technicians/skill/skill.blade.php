@extends('admin.layoutsNew.app')
@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('assetsNew/main_css/technician/skill/skill.css')}}">
<div class="content-wrapper" style="background-color: white;">
    @include('admin.includeNew.breadcrumb')
    <!-- /.content-header -->

    <style>
        #example th {
            text-align: center;
            color: black;
            white-space: nowrap;
            border: 1px solid #ccc;
        }

        #example td {
            text-align: center;
            color: black;
            white-space: nowrap;
        }

        .table,
        td {
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
        }

        thead {
            background-color: white;
        }

        thead th {
            padding: 10px;
            text-align: center;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        thead th:first-child {
            font-weight: bold;
        }
    </style>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title text-text-white" id="staticBackdropLabel">Add Multi Skill-Sets</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dark">
                    <form id="modal-form">
                        <div class="container mt-2">
                            <label for="skill_name">Skill-Sets</label>
                            <div id="input-container">
                                <div class="input-group add-item">
                                    <input type="text" name="skill_name[]" placeholder="Enter skill sets" class="form-control skill_name">
                                    <button type="button" class="btn btn-danger remove-btn"><i class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-secondary add-btn"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <span style="color:red; font-size:14px" id="errors-container"></span>
                            <span style="color:rgb(21, 198, 110); font-size:16px; font-weight:bold" id="success-container"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" id="submit-btn">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="d-none" id="get-html">
            <div class="deleteRow">
                <br>
                <div class="input-group add-item">
                    <input type="text" name="skill_name[]" placeholder="Enter skill sets" class="form-control skill_name">
                    <button type="button" class="btn btn-danger remove-btn"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-secondary add-btn"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-mb-12 d-flex justify-content-between align-items-center">
                                <h5 class="text-dark">Skill Sets</h5>
                                <div>
                                    <button class="button btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fas fa-plus-circle"></i> Add New Skill</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-body text-dark">
                        <table class="table" id="example" style="width: 100%;">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Skill Sets</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <!-- Your table rows will go here -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="confirmDeleteLink" href="#" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>
<script>
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    function confirmDelete(link) {
        const deleteLink = link;
        const confirmDeleteLink = document.getElementById('confirmDeleteLink');
        confirmDeleteLink.href = deleteLink;
        return false;
    }
</script>

<script>
    $(document).ready(function() {
        loadSkill();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function loadSkill() {
            $.ajax({
                url: "{{ route('technician.skillList') }}",
                type: "GET",
                success: function(response) {
                    let skills = response.skills;
                    let table = $('#example').DataTable();
                    table.clear();
                    skills.forEach((element, index) => {
                        let id = element.id;
                        table.row.add([
                            index + 1,
                            element.skill_name,
                            `<div class="dropdown">` +
                            `<i class="mx-4 fas fa-ellipsis-v" id="dropdownMenuButton${id}" data-toggle="dropdown" aria-expanded="false" style="cursor:pointer;"></i>` +
                            `<div class="dropdown-menu" aria-labelledby="dropdownMenuButton${id}">` +
                            `<a class="dropdown-item skillEdit" href="#" data-id="${id}"><i class="fas fa-edit text-primary"></i> Edit</a>` +
                            `<a class="dropdown-item skillDelete" href="#" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-id="${id}"><i class="fas fa-trash text-danger"></i> Delete</a>` +
                            `</div>` +
                            `</div>`,
                        ]);
                    });
                    table.draw();
                }
            });
        }


        //skill sets edit routes
        $(document).on('click', '.skillEdit', function() {
            let id = $(this).data('id');
            let route = "{{ route('technician.catEdit',':id') }}";
            route = route.replace(':id', id);
            $(this).attr('href', route);
        });

        //skill sets delete routes
        $(document).on('click', '.skillDelete', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let route = "{{ route('technician.catDelete',':id') }}";
            route = route.replace(':id', id);
            $('#confirmDeleteLink').attr('href', route);
        });

        $(document).on('click', '.add-btn', function() {
            var html = $('#get-html').html();
            $(this).closest('#input-container').append(html);
        });

        $(document).on('click', '.remove-btn', function() {
            $(this).closest('.deleteRow').remove();
        });

        $(document).on('click', '#submit-btn', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('technician.multi.skill.store') }}",
                type: "POST",
                data: $('#modal-form').serialize(),
                datatype: "json",
                success: function(res) {
                    loadSkill();
                    iziToast.success({
                        message: res.success,
                        position: 'topRight'
                    });
                },
                error: function(res) {
                    if (res.status == 422) {
                        errors = res.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            iziToast.error({
                                message: value,
                                position: 'topRight'
                            });
                        });
                    }
                }
            });
        });

        $(document).on('click', '.btn-close', function() {
            $('.skill_name').val('');
        });
    });
</script>

{{-- <script>
    $(document).ready(function() {
        $('a.page-link').removeClass('active');
        $('li.page-item').removeClass('active');
        $('a.page-link[href*="page=1"][data-page="1"]').addClass('active');
        fetchData(1);
        var page = 1;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function fetchData(pageNumber) {
            $.ajax({
                url: "{{ route('technician.get.skills') }}?page=" + pageNumber,
type: "GET",
success: function(data) {
let html = "";
let serial = (pageNumber - 1) * 10 + 1;
$.each(data.skills.data, function(key, value) {
html +=
'<tr>' +
    '<td>' + serial + '</td>' +
    '<td>' + value.skill_name + '</td>' +
    '<td>' +
        '<div>' +
            '<button class="btn btn-sm text-primary" id="edit" value="' + value.id + '" type="button" title="Edit" data-bs-title="Edit" data-bs-toggle="tooltip" data-bs-placement="top">' +
                '<i class="fas fa-edit" style="font-size:18px;"></i>' +
                '</button>' +
            '<button class="btn btn-sm" id="delete" value="' + value.id + '" type="button" title="Delete" data-bs-title="Delete" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#deleteConfirmationModal">' +
                '<i class="fas fa-trash text-danger"></i>' +
                '</button>' +
            '</div>' +
        '</td>' +
    '</tr>';
serial++
});
$('#tbody').html(html);
}
});
}

//taking the id and made a route for edit
$(document).on('click', '#edit', function() {
let skill_id = $(this).val();
let route = "{{ route('technician.catEdit',':skill_id') }}";
route = route.replace(':skill_id', skill_id);
window.location.href = route;
});
//set the delete link in modal
$(document).on('click', '#delete', function() {
let skill_id = $(this).val();
let route = "{{ route('technician.catDelete',':skill_id') }}";
route = route.replace(':skill_id', skill_id);
let link = route;
confirmDelete(link);
});

$(document).on('click', 'a.page-link', function(e) {
e.preventDefault();
$('a.page-link').removeClass('active');
$('span.page-link').removeClass('active');
$('li.page-item').removeClass('active');
$(this).addClass('active');
const href = $(this).attr('href');
const pageNumber = href ? href.split('page=')[1] : null;
page = pageNumber;
console.log('Clicked on pagination link. Href:', href);
console.log('Page number:', pageNumber);
fetchData(pageNumber);

});

$(document).on('click', 'span.page-link', function(e) {
console.log('Clicked on first page link');
$('span.page-link').removeClass('active');
$('li.page-item').removeClass('active');
$('a.page-link').removeClass('active');
$(this).addClass('active');
var number = $('span.page-link').text();
number = number.replace(/[^0-9]/g, '');
page = number;
fetchData(number);
});

$(document).on('click', '.add-btn', function() {
var html = $('#get-html').html();
$(this).closest('#input-container').append(html);
});

$(document).on('click', '.remove-btn', function() {
$(this).closest('.deleteRow').remove();
});

$(document).on('click', '#submit-btn', function(e) {
e.preventDefault();
$.ajax({
url: "{{ route('technician.multi.skill.store') }}",
type: "POST",
data: $('#modal-form').serialize(),
datatype: "json",
success: function(res) {
fetchData(page);
iziToast.success({
message: res.success,
position: 'topRight'
});
},
error: function(res) {
if (res.status == 422) {
errors = res.responseJSON.errors;
$.each(errors, function(key, value) {
iziToast.error({
message: value,
position: 'topRight'
});
});
}
}
});
});

$(document).on('click', '.btn-close', function() {
$('.skill_name').val('');
});
});
</script> --}}
@endsection