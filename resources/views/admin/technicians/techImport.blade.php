@extends('admin.layoutsNew.app')
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
@endsection
@section('content')
    <div class="content-wrapper" style="background-color: white;">
        @include('admin.includeNew.breadcrumb')
        <div class="container-fluid">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('technician.download.sampleExcel') }}" class="button" id="excelButton"><i
                                class="fas fa-download"></i> Download Excel Sheet</a>
                        </i>
                        <a href="{{ route('technician.index') }}" class="float-right"><i
                                class="fas fa-user"></i>&nbsp;Techncian List</a>
                    </div>
                    <div class="card-body">
                        <div class="mx-auto my-auto">
                            <label>Select an Excel file to import</label>
                            <form id="import" action="{{ route('technician.excel.import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <input type="file" class="form-control col-6" name="excel_file" id="fileInput">
                                    <span id="error-container" style="color:red; font-size:16px"></span>
                                </div>

                                <button class="btn btn-primary mt-3" type="submit" id="import">
                                    <i class="d-none fa fa-spinner fa-spin" style="font-size:16px"></i>
                                    <span class="button-text">Import</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            // const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            //     cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            // });

            // const channel = pusher.subscribe('excel-import-progress');

            // channel.bind('import.progress', function(data) {
            //     const percentage = data.percentage;
            //     console.log(percentage);

            //     if (percentage > 0) {
            //         $('#progressBar').css('width', percentage + '%').attr('aria-valuenow', percentage).text(
            //             percentage + '%');
            //     }
            // });

            $(document).on("submit", "#import", function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                var input = $('#fileInput').val();
                var button = $(this);
                var icon = button.find('i');
                icon.removeClass('d-none');
                button.find('.button-text').text('Please Wait !');

                $.ajax({
                    url: "{{ route('technician.excel.import') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        icon.addClass('d-none');
                        button.find('.button-text').text('Import');
                        $('#fileInput').val("");
                        iziToast.success({
                            message: data.success,
                            position: "topRight"
                        });
                        $('#error-container').text("");

                    },
                    error: function(xhr, status, error) {
                        icon.addClass('d-none');
                        button.find('.button-text').text('Import');
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = '';
                            $.each(errors, function(index, error) {
                                errorMessage += error[0] + '<br>';
                            });
                            $('#error-container').html(errorMessage);
                        } else {
                            $('#error-container').text(
                                "An error occurred while importing the file.").css('color',
                                'red');
                        }
                    }
                });
            });
        });
    </script>
@endsection
