import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Button, Modal } from 'react-bootstrap'

const TaskModal = ({ id, techId, onSuccessMessage, is_cancelled, is_billing }) => {

    const [showTask, setShowTask] = useState(false);

    const handleCloseHold = () => setShowTask(false);
    const handleShowHold = () => setShowTask(true);

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'type': '',
        'reason': '',
        'desc': '',
        'email': '',
        'phone': '',
        'from': '',
        'item': '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.addTask', [id, techId]), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Task Created Successfully');
                setShowTask(false)
            }
        });
    };


    return (
        <>
            <Button variant="outline-dark" onClick={handleShowHold} disabled={is_cancelled || is_billing}>
                + Add Task
            </Button>
            <Modal show={showTask} onHide={handleCloseHold}>
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Add Task</h5>
                    <button onClick={() => setShowTask(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <form id='addTask' onSubmit={(e) => submit(e)} encType="multipart/form-data">
                        <select name="type" className="taskSelect form-select mb-2" onChange={(e) => setData({ ...data, type: e.target.value })}>
                            <option value selected>Select Option</option>
                            <option value="call">Call</option>
                            <option value="collect_signature">Collect Signature
                            </option>
                            <option value="custom_task">Completed Custom Task</option>
                            <option value="shipping_details">Shipping Details</option>
                            <option value="send_email">Send Email</option>
                            <option value="upload_file">Upload File</option>
                            <option value="upload_photo">Upload/Take Photo</option>
                            <option value="closeout_note">Closeout Note</option>
                        </select>


                        {
                            data.type == 'send_email'
                            &&
                            <div id className="mb-3 email">
                                <input name="email" type="email" className="form-control" id="exampleFormControlInput1" placeholder="Enter Email" onChange={(e) => setData({ ...data, email: e.target.value })} />
                            </div>
                        }

                        {
                            data.type == 'call'
                            &&
                            <div id className="mb-3 phone">
                                <input name="phone" type="text" className="form-control" id="exampleFormControlInput1" placeholder="Enter Phone" onChange={(e) => setData({ ...data, phone: e.target.value })} />
                            </div>
                        }

                        {
                            data.type == 'collect_signature'
                            &&
                            <div id className="mb-3 from">
                                <input name="from" type="text" className="form-control" id="exampleFormControlInput1" placeholder="Enter Signee's Name" onChange={(e) => setData({ ...data, from: e.target.value })} />
                            </div>
                        }

                        {
                            data.type == 'shipping_details' &&
                            <div className="mb-3 item">
                                <input name="item" type="text" className="form-control" id="exampleFormControlInput1" placeholder="Enter Item Name" onChange={(e) => setData({ ...data, item: e.target.value })} />
                            </div>
                        }


                        {
                            (data.type == 'call' || data.type == 'send_email') &&
                            <div id className="form-floating mb-2 reason">
                                <textarea name="reason" className="form-control" placeholder="Enter Reason" id="floatingTextarea2" style={{ height: 100 }} defaultValue={""} onChange={(e) => setData({ ...data, reason: e.target.value })} />
                                <label htmlFor="floatingTextarea2">Reason</label>
                            </div>
                        }

                        {
                            (data.type == 'custom_task' || data.type == 'upload_file' || data.type == 'upload_photo' || data.type == 'closeout_note')
                            &&
                            <div id className="form-floating mb-2 desc">
                                <textarea name="desc" className="form-control" placeholder="Enter Description" id="floatingTextarea2" style={{ height: 100 }} defaultValue={""} onChange={(e) => setData({ ...data, desc: e.target.value })} />
                                <label htmlFor="floatingTextarea2">Description</label>
                            </div>
                        }
                    </form>
                </Modal.Body>
                <Modal.Footer>
                    <button onClick={() => setShowTask(false)} type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button onClick={(e) => submit(e)} type="button" className="btn btn-dark">Add Task</button>
                </Modal.Footer>
            </Modal>
        </>

    )
}

export default TaskModal