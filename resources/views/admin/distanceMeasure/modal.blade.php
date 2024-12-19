<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gray">
        <h5 class="modal-title" id="staticBackdropLabel">Contact This Technician</h5>
        <button type="button" class="btn-close bg-gray" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- if need use style="max-height: 400px; overflow-y: auto;"  for scroll bar -->
        <div class="btn-group col-12">
          <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center w-50" id="email-btn">
            <i class="ion-android-mail" style="font-size:20px"></i>
            <span class="ml-2">Email</span>
          </button>

          <button type="button" class="btn btn-success d-flex align-items-center justify-content-center w-50" id="phone-btn">
            <i class="ion-ios-telephone-outline" style="font-size:24px"></i>
            <span class="ml-2">Phone</span>
          </button>

        </div>
        <div class="d-none" id="email-card">
          <div class="card mt-5">
            <div class="card-body">
              <div class="col-12 d-flex text-center">
                <h5>Company Name</h5>
                <h5 class="company_name">XYZ Corp</h5>
              </div>
              <hr>
              <div class="col-12">
                <label>Email</label>
                <input type="text" class="form-control" name="email" id="email-input" value="example@example.com" readonly>
              </div>
              <div class="col-12">
                <label>Subject</label>
                <input type="text" class="form-control" name="subject">
              </div>
              <div class="col-12">
                <label>Body</label>
                <textarea name="body-text" id="body-text" cols="30" rows="5" class="form-control"></textarea>
              </div>
              <div class="col-12 mt-3">
                <button class="btn btn-success btn-block" type="button">Send</button>
              </div>
            </div>
          </div>
        </div>
        <div class="d-none" id="phone-card">
          <div class="card mt-5">
            <div class="card-body">
              <div class="col-12 d-flex">
                <h5>Company Name</h5>
                <h5 class="company_name">XYZ Corp</h5>
              </div>
              <hr>
              <div class="col-12 d-flex">
                <h6>Phone Number:</h6>
                <h6 class="ml-1" id="phone_number">123-456-7890</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>