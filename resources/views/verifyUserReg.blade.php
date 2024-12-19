<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Token</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header bg-gray">
                        <h3>Token Verification</h3>
                    </div>
                    <div class="card-body">
                        <form id="tokenForm" action="{{ route('admin.token') }}" method="POST">
                            @csrf
                            <label for="">Token</label>
                            <input id="token" type="text" class="form-control" name="reg_token"
                                placeholder="Please enter the token from invitation mail...">
                            @error('reg_token')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-primary mt-3 w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        
    </script> --}}
</body>

</html>
