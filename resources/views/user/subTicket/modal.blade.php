<div class="modal fade" id="subTicketModal" tabindex="-1" aria-labelledby="subTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Create Sub Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <table class="table table-bordered" id="ticket_type">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Ticket Number</th>
                                    <th scope="col">Create</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- sub ticket child modal  -->
<div class="modal fade" id="postSubTicketModal" tabindex="-1" role="dialog" aria-labelledby="postSubTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postSubTicketModalLabel">New Sub Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-12">
                        <form id="subticket-update-form">
                            @csrf
                            <input type="hidden" class="form-control" name="id" id="subticket_ticketID">
                            <label for="">Ticket ID</label>
                            <input type="text" class="form-control" name="ticket_id" id="subticket_ticket_id" readonly>
                            <label for="">Work Request</label>
                            <textarea name="work_request" class="form-control my-2" id="work_request" cols="10" rows="5" placeholder="Enter Work Request"></textarea>
                            <span id="work_request_error" style="color: red; font-size:14px;"></span>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="subticketSubmit">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>