<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Check-IN-OUT Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="check_in_out_edit_form">
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <input type="hidden" id="edit_w_id">
                    <div class="form-label">Technician Name</div>
                    <input type="text" name="tech_name" class="form-control" id="tech_name">
                    <div class="form-label">Date</div>
                    <input type="text" name="date" class="form-control" id="date_edit">
                    <div class="form-label">Check In</div>
                    <input type="text" name="check_in" class="form-control" id="check_in_edit">
                    <div class="form-label">Check Out</div>
                    <input type="text" name="check_out" class="form-control" id="check_out_edit">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-danger" id="delete_check_in_out">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
