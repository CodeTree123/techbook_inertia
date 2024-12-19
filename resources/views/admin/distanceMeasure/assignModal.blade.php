<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content ">
      <div class="modal-header bg-gray ">
        <h5 class="modal-title " id="staticBackdropLabel">Assign This Technician</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('dispatch.assign.ftech') }}" method="POST">
          <input type="hidden" name="ftech_id" id="ftech_id">
          <input type="hidden" name="workOrderId" id="workOrderId">
          @csrf
          <div class="d-flex">
            <h5>Company Name : </h5>
            <h5 id="company_name" class="ml-2"></h5>
          </div>
          <div class="d-flex">
            <h5>Technician ID : </h5>
            <h5 id="tech_id" class="ml-2">dfvdfvsdfvd</h5>
          </div>
          <div class="d-flex">
            <h5>Status : </h5>
            <h5 id="status" class="ml-2">Active</h5>
          </div>
          <hr>
          <div class="d-flex">
            <button type="submit" class="btn btn-primary">Assign</button>
            {{-- <button type="button" class="btn btn-secondary ml-2" data-bs-dismiss="modal">Close</button> --}}
          </div>
        </form>
      </div>
    </div>
  </div>
</div>