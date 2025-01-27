
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const invoiceButton = document.getElementById("invoiceButton");
        const confirmInvoiceBtn = document.getElementById("confirmInvoiceBtn");
        const confirmInvoiceModal = new bootstrap.Modal(document.getElementById("confirmInvoiceModal"));

        invoiceButton.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent direct navigation
            const invoiceUrl = this.getAttribute("data-invoice-url");
            confirmInvoiceBtn.href = invoiceUrl;
            confirmInvoiceModal.show();
        });
        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
            button.addEventListener("click", function() {
                confirmInvoiceModal.hide();
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("#referCode").forEach(button => {
            button.addEventListener("click", function(event) {
                event.preventDefault(); // Prevent default anchor behavior

                let url = this.href; // Get the route from the href attribute
                let referenceCode = prompt("Enter reference code:"); // Prompt user for reference code

                if (referenceCode === null || referenceCode.trim() === "") {
                    alert("Reference code is required.");
                    return;
                }

                fetch(url, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            reference_code: referenceCode
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message || "Invoice updated successfully");
                        location.reload(); // Reload the page to reflect changes
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    });
</script>



<!-- Confirmation Modal for invoice button -->
<div class="modal fade" id="confirmInvoiceModal" tabindex="-1" aria-labelledby="confirmInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmInvoiceModalLabel">Confirm Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to generate the invoice?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmInvoiceBtn" class="btn btn-danger">Yes, Proceed</a>
            </div>
        </div>
    </div>
</div>
<!-- End Confirmation Modal for invoice button -->