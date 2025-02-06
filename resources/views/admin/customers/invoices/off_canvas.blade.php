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
    // When the button is clicked, fetch logs and open the offcanvas
    $('#viewLogDetailsBtn').on('click', function() {
        var woId = @json($wId); // Pass the Work Order ID from Blade into JavaScript
        console.log("Work Order ID: ", woId); // Debugging line

        // Build the URL for the AJAX request
        var url = '{{ route('admin.logs.paginate', ['id' => ':id', 'page' => ':page']) }}';
        url = url.replace(':id', woId); // Replace with the actual work order ID
        url = url.replace(':page', 1); // Start with page 1

        // Make the AJAX request
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log("Logs successfully fetched:", response); // Debugging line
                $('.offcanvas-body').html(response); // Update the content in the offcanvas

                // Manually show the offcanvas after content is loaded
                var offcanvas = new bootstrap.Offcanvas(document.getElementById('invoiceOffcanvas'));
                offcanvas.show(); 
            },
            error: function(xhr, status, error) {
                console.log('Error fetching logs:', error); // Debugging line
            }
        });
    });

    // Listen for clicks on pagination links inside the offcanvas
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault(); // Prevent default page reload

        // Get the page number from the href attribute
        var page = $(this).attr('href').split('page=')[1];
        var woId = @json($wId); // Work Order ID from Blade

        // Build the URL for the AJAX request
        var url = '{{ route('admin.logs.paginate', ['id' => ':id', 'page' => ':page']) }}';
        url = url.replace(':id', woId); // Replace with the actual work order ID
        url = url.replace(':page', page); // Replace with the current page number

        console.log("Fetching logs from URL:", url); // Debugging line

        // Make the AJAX request
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log("Logs successfully fetched:", response); // Debugging line
                $('.offcanvas-body').html(response); // Update the content in the offcanvas
            },
            error: function(xhr, status, error) {
                console.log('Error fetching logs:', error); // Debugging line
            }
        });
    });
});

</script>