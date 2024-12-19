<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header bg-gray">
                        <h3>Admin Register</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.postRegister') }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="reg_token" value="{{ $invitation->token }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter the full name" value="{{ old('name') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Username</label>
                                    <input type="text" class="form-control" name="username"
                                        placeholder="Enter username" value="{{ old('username') }}">
                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Enter passsword">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation"
                                        placeholder="Enter the same passsword again">
                                    @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="role_id">Role</label>
                                    <select class="form-select" name="role_id" id="role_id"
                                        aria-label="Default select example">
                                        <option value="" {{ old('role_id') == '' ? 'selected' : '' }}>Select Role
                                        </option>
                                        <option value="1" {{ old('role_id') == '1' ? 'selected' : '' }}>Project
                                            Manager</option>
                                        <option value="2" {{ old('role_id') == '2' ? 'selected' : '' }}>Dispatch
                                            Team</option>
                                        {{-- <option value="0" {{ old('role_id') == '0' ? 'selected' : '' }}>Super Admin</option> --}}
                                    </select>
                                    @error('role_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Select Profile Photo</label>
                                    <input type="file" name="image" class="form-control"
                                        value="{{ old('image') }}">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3 w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
