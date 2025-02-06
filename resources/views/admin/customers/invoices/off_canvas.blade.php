<style>
    #loader {
    text-align: center;
    margin-top: 20px;
}
</style>
<div class="offcanvas offcanvas-end" tabindex="-1" id="invoiceOffcanvas" aria-labelledby="invoiceOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="invoiceOffcanvasLabel">Log Details</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Logs will be dynamically loaded here via AJAX -->
    </div>
</div>

<script>
$(document).ready(function() {
    $('#viewLogDetailsBtn').on('click', function() {
        var woId = @json($wId);
        console.log("Work Order ID: ", woId);

        $('#loader').show();

        var url = '{{ route('admin.logs.paginate', ['id' => ':id', 'page' => ':page']) }}';
        url = url.replace(':id', woId);
        url = url.replace(':page', 1);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log("Logs successfully fetched:", response);
                $('.offcanvas-body').html(response);

                var offcanvas = new bootstrap.Offcanvas(document.getElementById('invoiceOffcanvas'));
                offcanvas.show(); 

                $('#loader').hide();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching logs:', error);
                
                $('#loader').hide();
            }
        });
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        var woId = @json($wId);

        $('#loader').show();

        var url = '{{ route('admin.logs.paginate', ['id' => ':id', 'page' => ':page']) }}';
        url = url.replace(':id', woId);
        url = url.replace(':page', page);

        console.log("Fetching logs from URL:", url);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log("Logs successfully fetched:", response);
                $('.offcanvas-body').html(response);
                $('#loader').hide();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching logs:', error);
                $('#loader').hide();
            }
        });
    });
});
</script>
