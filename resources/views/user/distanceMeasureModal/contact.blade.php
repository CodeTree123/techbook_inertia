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
              <form id="send_mail_form">
                @csrf
                <div class="card-body">
                  <div class="col-12 d-flex text-center">
                    <h5>Company Name :&nbsp;</h5>
                    <h5 class="contact_modal_company">XYZ Corp</h5>
                  </div>
                  <hr>
                  <div class="col-12">
                    <label>Email</label>
                    <input type="text" class="form-control" name="to_email" id="contact_modal_email_input" readonly>
                  </div>
                  <div class="col-12">
                    <label>Subject</label>
                    <input type="text" class="form-control" name="subject">
                  </div>
                  <div class="col-12">
                    <label>Body</label>
                    <textarea name="body_text" id="body-text" cols="30" rows="5" class="form-control"></textarea>
                  </div>
                  <div class="col-12 mt-3">
                    <button class="btn btn-success btn-block" type="submit">Send</button>
                    <button class="btn btn-primary d-none" type="button" disabled id="email-sending-loader">
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      <span class="visually-hidden">Loading...</span>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="d-none" id="phone-card">
            <div class="card mt-5">
              <div class="card-body">
                <div class="col-12 d-flex">
                  <h5>Company Name :&nbsp;</h5>
                  <h5 class="contact_modal_company">XYZ Corp</h5>
                </div>
                <hr>
                <div class="col-12 d-flex">
                  <h6>Phone Number :&nbsp;</h6>
                  <h6 class="ml-1" id="contact_modal_phone_number">123-456-7890</h6>
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