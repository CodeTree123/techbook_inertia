import React, { useState } from 'react'
import { Button, Modal } from 'react-bootstrap'

const Task = ({ data, setData, taskRef }) => {
    const [showTask, setShowTask] = useState(false);

    const handleCloseHold = () => setShowTask(false);
    const handleShowHold = () => setShowTask(true);

    const handleSubmit = (e) => {
        e.preventDefault();

        const newTask = {
            type: data.type || '',
            reason: data.reason || '',
            desc: data.desc || '',
            email: data.email || '',
            phone: data.phone || '',
            from: data.from || '',
            item: data.item || '',
        };

        setData({
            ...data,
            tasks: [...data?.tasks, newTask],
            type: '',
            reason: '',
            desc: '',
            email: '',
            phone: '',
            from: '',
            item: '',
        });

        setShowTask(false);
    };

    // editing

    const [editable, setEditable] = useState(null);

    const handleEdit = (index) => {
        setEditable(index);

        const currentTask = data.tasks[index];
        setData({
            ...data,
            type: currentTask.type,
            reason: currentTask.reason,
            desc: currentTask.desc,
            email: currentTask.email,
            phone: currentTask.phone,
            from: currentTask.from,
            item: currentTask.item,
        });
    }

    const handleCancel = () => {
        setEditable(null);
        setData({
            ...data,
            type: '',
            reason: '',
            desc: '',
            email: '',
            phone: '',
            from: '',
            item: '',
        });
    };

    const handleUpdate = (e, taskindex) => {
        e.preventDefault();
    
        const updatedTask = data.tasks.map((task, index) =>
            index === taskindex
                ? {
                      ...task,
                      type: data.type || task.type,
                      reason: data.reason || task.reason,
                      desc: data.desc || task.desc,
                      email: data.email || task.email,
                      phone: data.phone || task.phone,
                      from: data.from || task.from,
                      item: data.item || task.item,
                  }
                : task
        );
    
        // Update the state
        setData({
            ...data,
            tasks: updatedTask,
            type: '',
            reason: '',
            desc: '',
            email: '',
            phone: '',
            from: '',
            item: '',
        });
    
        // Exit edit mode
        setEditable(null);
    };

    const deleteTask = (e, index) => {
        e.preventDefault();
    
        // Remove the selected part by its index
        const updatedTasks = data.tasks.filter((_, i) => i !== index);
    
        // Update the state
        setData({
            ...data,
            tasks: updatedTasks,
        });
    
        // Exit edit mode if the deleted row is being edited
        if (editable === index) {
            setEditable(null);
        }
    };

    return (
        <div ref={taskRef} className="card bg-white border mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Tasks</h3>
            </div>
            <div className="card-body bg-white">
                <Button variant="outline-dark" className='mb-3' onClick={handleShowHold}>
                    + Add Task
                </Button>
                <Modal show={showTask} onHide={handleCloseHold}>
                    <Modal.Header>
                        <h5 className="modal-title" id="exampleModalLabel">Add Task</h5>
                        <button onClick={() => setShowTask(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                    </Modal.Header>
                    <Modal.Body>
                        <form id='addTask' onSubmit={(e) => handleSubmit(e)} encType="multipart/form-data">
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
                        <button onClick={(e) => handleSubmit(e)} type="button" className="btn btn-dark">Add Task</button>
                    </Modal.Footer>
                </Modal>

                {
                    data?.tasks?.filter((task) => task.type !== 'closeout_note').map((task, index) => (
                        <div className="px-4 py-3 mb-4 d-flex justify-content-between action-cards" style={{ backgroundColor: '#E3F2FD', cursor: 'pointer' }} draggable >
                            <div className="d-flex">

                                <label className="form-check-label">
                                    <form onSubmit={(e) => handleUpdate(e, index)}>
                                        {
                                            task.type == 'call' &&
                                            <span>Call at <a href={`callto:${task.phone}`}>{task.phone}</a></span>
                                        }

                                        {
                                            task.type == 'collect_signature' &&
                                            <span>Collect Signature from {task.from}</span>
                                        }

                                        {
                                            task.type == 'custom_task' &&
                                            <span>Custom Task</span>
                                        }

                                        {
                                            task.type == 'shipping_details' &&
                                            <span> Shipping Details ({task.item})</span>
                                        }

                                        {
                                            task.type == 'send_email' &&
                                            <span>Send Email at <a href={`mailto:${task.email}`}>{task.email}</a></span>
                                        }

                                        {
                                            task.type == 'upload_file' &&
                                            <span>
                                                Uploaded File
                                            </span>
                                        }

                                        {
                                            task.type == 'upload_photo' &&
                                            <span>
                                                Upload/Take Photo
                                            </span>
                                        }

                                        {
                                            (task.type == 'call' || task.type == 'send_email') &&
                                            <p className="mb-2 nrml-txt" style={{ fontWeight: 300, fontSize: 14, color: '#808080' }}>
                                                Reason: {task.reason}</p>
                                        }

                                        {
                                            (task.type == 'custom_task' || task.type == 'upload_file' || task.type == 'upload_photo') &&
                                            <p className="mb-2 nrml-txt" style={{ fontWeight: 300, fontSize: 14, color: '#808080' }}>
                                                Description: {task.desc}</p>
                                        }


                                        <p className="mb-0 nrml-txt" style={{ fontWeight: 300, fontSize: 12, color: '#808080' }}>
                                            Task Assigned at
                                            05:58 PM
                                            (12-11-24)
                                        </p>
                                        {
                                            editable == index &&
                                            <>
                                                {
                                                    task.type == 'send_email'
                                                    &&
                                                    <div id className="mb-3 email">
                                                        <input name="email" type="email" className="form-control" id="exampleFormControlInput1" placeholder="Enter Email" onChange={(e) => setData({ ...data, email: e.target.value })} defaultValue={task.email} />
                                                    </div>
                                                }

                                                {
                                                    task.type == 'call'
                                                    &&
                                                    <div id className="mb-3 phone">
                                                        <input name="phone" type="text" className="form-control" id="exampleFormControlInput1" placeholder="Enter Phone" onChange={(e) => setData({ ...data, phone: e.target.value })} defaultValue={task.phone} />
                                                    </div>
                                                }

                                                {
                                                    task.type == 'collect_signature'
                                                    &&
                                                    <div id className="mb-3 from">
                                                        <input name="from" type="text" className="form-control" id="exampleFormControlInput1" placeholder="Enter Signee's Name" onChange={(e) => setData({ ...data, from: e.target.value })} defaultValue={task.from} />
                                                    </div>
                                                }

                                                {
                                                    task.type == 'shipping_details' &&
                                                    <div id className="mb-3 item">
                                                        <input name="item" type="text" className="form-control" id="exampleFormControlInput1" placeholder="Enter Item Name" onChange={(e) => setData({ ...data, item: e.target.value })} defaultValue={task.item} />
                                                    </div>
                                                }


                                                {
                                                    (task.type == 'call' || task.type == 'send_email') &&
                                                    <div id className="form-floating mb-2 reason">
                                                        <textarea name="reason" className="form-control" placeholder="Enter Reason" id="floatingTextarea2" style={{ height: 100 }} defaultValue={task.reason} onChange={(e) => setData({ ...data, reason: e.target.value })} />
                                                        <label htmlFor="floatingTextarea2">Reason</label>
                                                    </div>
                                                }

                                                {
                                                    (task.type == 'custom_task' || task.type == 'upload_file' || task.type == 'upload_photo' || task.type == 'closeout_note')
                                                    &&
                                                    <div id className="form-floating mb-2 desc">
                                                        <textarea name="desc" className="form-control" placeholder="Enter Description" id="floatingTextarea2" style={{ height: 100 }} defaultValue={task.desc} onChange={(e) => setData({ ...data, desc: e.target.value })} />
                                                        <label htmlFor="floatingTextarea2">Description</label>
                                                    </div>
                                                }
                                            </>
                                        }

                                    </form>
                                </label>
                            </div>

                            <div className="d-flex action-group gap-2">
                                {
                                    editable != index &&
                                    <button onClick={() => handleEdit(index)} className="btn edit-btn border-0" style={{ height: 'max-content' }}>
                                        <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                                    </button>
                                }

                                {
                                    editable == index &&
                                    <button onClick={(e) => handleUpdate(e, index)} className="btn btn-success fw-bold" style={{ height: 'max-content' }}>
                                        Save
                                    </button>
                                }
                                {
                                    editable == index &&
                                    <button onClick={() => handleCancel()} className="btn btn-danger fw-bold" style={{ height: 'max-content' }}>
                                        Cancel
                                    </button>
                                }
                                {
                                    editable != index &&
                                    <button onClick={(e) => deleteTask(e, index)} type="button" className="btn border-0" style={{ height: 'max-content' }}>
                                        <i className="fa-solid fa-trash text-danger" aria-hidden="true" />
                                    </button>
                                }

                            </div>
                        </div>

                    ))
                }
            </div>
        </div>
    )
}

export default Task