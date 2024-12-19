
<div class="d-flex flex-wrap justify-content-end gap-2 align-items-center breadcrumb-plugins">
    @if($non_converted_techs > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert" id="conversion-alert">
            <strong>{{ $non_converted_techs }}</strong> technician is found with empty co-ordinate please visit address conversion module to convert technician address otherwise closest tech module will not function properly.
        </div>
    @endif
</div>
@push('custom-scripts')
    <script>
        $(document).ready(function(){
            $('#conversion-alert').click(function(){
                $(this).toggle('d-none');
            });
        });
    </script>
@endpush
