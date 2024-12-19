
<div class="d-flex flex-wrap justify-content-end gap-2 align-items-center breadcrumb-plugins mt-5">
    @if($non_converted_techs > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert" id="conversion-alert-user">
            <strong>{{ $non_converted_techs }}</strong> technician is found with empty co-ordinate please visit address conversion module to convert technician address otherwise closest tech module will not function properly.
        </div>
    @endif
</div>
@push('custom_script')
<script>
    $(document).ready(function(){
        $('#conversion-alert-user').click(function(){
            $(this).toggle('d-none');
        });
    });
</script>
@endpush
