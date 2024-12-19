@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                            Add Employee
                        </button>
                    </div>
                    <div class="card-body" style="display: flex; justify-content: center;">
                        <table class="table table-responsive text-center" style="width: auto;">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Employee Id</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $em)
                                <tr>
                                    <th scope="row">{{$em->id}}</th>
                                    <td><a href="{{ route('employee.workOrder', $em->id) }}">{{$em->employee_id}}</a></td>
                                    <td>{{$em->name}}</td>
                                    <td>{{$em->email}}</td>
                                    <td>{{$em->mobile}}</td>
                                    <td><a class="btn btn-primary" href="{{ route('employee.edit', $em->id) }}">Edit</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($employees->hasPages())
                    <div class="card-footer py-4">
                        <p class="text-italic">Click below to see next page</p> @php echo paginateLinks($employees) @endphp
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('employee.add')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <div class="form-label">Name</div>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="form-label">Email</div>
                        <input type="text" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="form-label">Mobile</div>
                        <input type="text" name="mobile" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <p class="font-weight-light p-2 m-2">Search by Employee Id, Name or Mobile :</p>
    <x-search-form dateSearch='no' />
@endpush