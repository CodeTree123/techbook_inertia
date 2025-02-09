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
    // Trigger offcanvas on button click
    $('#viewLogDetailsBtn').on('click', function() {
        var woId = @json($wId);
        console.log("Work Order ID: ", woId);

        $('#loader').show();

        var url = '{{ route('admin.logs.paginate', ['id' => ':id', 'page' => ':page']) }}';
        url = url.replace(':id', woId);
        url = url.replace(':page', 1);  // Initial page

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log("Logs successfully fetched:", response);
                $('.offcanvas-body').html(response);  // Update logs

                // Reinitialize Bootstrap's collapse functionality for the newly loaded content
                $('.accordion-button').each(function() {
                
                    var collapse = new bootstrap.Collapse(this, {
                        toggle: false // Don't trigger the collapse immediately, just initialize
                    });
                });

                $('#loader').hide();

                // Initialize and show offcanvas
                var offcanvas = new bootstrap.Offcanvas(document.getElementById('invoiceOffcanvas'));
                offcanvas.show();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching logs:', error);
                $('#loader').hide();
            }
        });
    });

    // Handle pagination clicks inside the offcanvas
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
                $('.offcanvas-body').html(response);  // Update logs

                $('.accordion-button').each(function() {
                    let target = $(this).attr('data-bs-target'); // Get target collapse ID
                    if (target) {
                        let collapseElement = document.querySelector(target);
                        if (collapseElement) {
                            new bootstrap.Collapse(collapseElement, { toggle: false });
                        }
                    }
                });

                $('#loader').hide();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching logs:', error);
                $('#loader').hide();
            }
        });
    });

    // Close the offcanvas when the close button is clicked
    $(document).on('click', '.btn-close', function() {
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('invoiceOffcanvas'));
        offcanvas.hide();  // Hide the offcanvas manually if needed
    });
});

</script>

