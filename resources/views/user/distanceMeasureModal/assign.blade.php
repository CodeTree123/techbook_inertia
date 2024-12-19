<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-lg">
        <div class="modal-content ">
            <div class="modal-header bg-gray ">
                <h5 class="modal-title " id="staticBackdropLabel">Assign This Technician</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="user_dispatch_form">
                    @csrf
                    <input type="hidden" name="ftech_id" id="assign_modal_ftech_id">
                    <input type="hidden" name="workOrderId" id="assign_modal_workOrderId">
                    <div class="d-flex">
                        <h6>Company Name :&nbsp;</h6>
                        <h6 id="assign_modal_company_name" class="ml-2"></h6>
                    </div>
                    <div class="d-flex">
                        <h6>Technician ID :&nbsp;</h6>
                        <h6 id="assign_modal_tech_id" class="ml-2">dfvdfvsdfvd</h6>
                    </div>
                    <div class="d-flex">
                        <h6>Status :&nbsp;</h6>
                        <h6 id="assign_modal_status" class="ml-2">Active</h6>
                    </div>
                    <div class="d-flex">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                            <label class="form-check-label" for="defaultCheck1"
                                style="margin-top: 3px; font-size:12px;">
                                &nbsp;Send email attached workorder to the tech.
                            </label>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary">Assign</button>
                        <button class="btn btn-primary mx-2 d-none" id="assignTechLoader" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
