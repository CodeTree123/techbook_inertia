import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Button, Modal } from 'react-bootstrap'
import TaskModal from './TaskModal';
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd';

const Task = ({ id, details, onSuccessMessage }) => {
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        type: '',
        reason: '',
        desc: '',
        email: '',
        phone: '',
        from: '',
        item: '',
        checkin_note: '',
        note: '',
        task_file: null,
    });

    const completeTaskForm = (e, taskId, isCompleted) => {
        e.preventDefault();

        post(route('user.wo.completeTask', { id: taskId }), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage(isCompleted == 0 ? 'Task Marked As Completed' : 'Task Marked As Incompleted');
            },
        });
    };

    const [editable, setEditable] = useState(null);

    const handleEdit = (index) => {
        setEditable(index);
    }

    const submit = (e, taskId) => {
        e.preventDefault();

        post(route('user.wo.editTask', taskId), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Task Updated Successfully');
                setEditable(null);
                setData(null)
            }
        });
    };

    const deleteTask = (e, taskId) => {
        e.preventDefault();

        deleteItem(route('user.wo.deleteTask', taskId), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Task Deleted Successfully');
                setEditable(null);
                setData(null)
            }
        });
    };

    // dnd

    const onDragEnd = (result) => {
        const { source, destination } = result;

        if (!destination) return;

        const sourceTechId = source.droppableId;
        const destinationTechId = destination.droppableId;

        if (sourceTechId === destinationTechId && source.index === destination.index) return;

        // Get the task being moved
        const taskId = result.draggableId; // Get task ID
        const task = details.tasks.find((t) => t.id.toString() === taskId);

        if (task) {
            const updatedTasks = details.tasks.map((t) => {
                if (t.id === task.id) {
                    return { ...t, tech_id: parseInt(destinationTechId) };
                }
                return t;
            });

            details.tasks = updatedTasks;

            post(route('user.wo.assignTechToTask', [task.id, parseInt(destinationTechId)]), {
                preserveScroll: true,
                onSuccess: () => {
                    onSuccessMessage(`Task assigned successfully!`);
                },
                onError: (error) => {
                    onSuccessMessage(error);
                }
            });
        }
    };

    const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

    const handleSubmit = (techId) => {
        document.getElementById(`checkinForm-${techId}`).submit();
    };

    const handleAddDefaultReasonClick = () => {
        const textarea = document.getElementById(`reason`);
        if (textarea) {
            textarea.style.display = textarea.style.display === 'none' || textarea.style.display === '' ? 'block' : 'none';
        }
    };

    const handleAddReasonClick = (techId) => {
        const textarea = document.getElementById(`reason-${techId}`);
        if (textarea) {
            textarea.style.display = textarea.style.display === 'none' || textarea.style.display === '' ? 'block' : 'none';
        }
    };

    const makeCheckin = (e, taskId) => {
        e.preventDefault();

        if (details.ftech_id == null) {
            onSuccessMessage('Assign a technician first');
        } else {
            post(route('user.wo.checkin', [id, taskId]), {
                preserveScroll: true,
                onSuccess: () => {
                    onSuccessMessage('Technician Checked in Successfully');
                },
                onError: (e) => {
                    onSuccessMessage(e);
                }
            });
        }

    };

    const makeCheckout = (e, taskId) => {
        e.preventDefault();

        if (details.ftech_id == null) {
            onSuccessMessage('Assign a technician first');
        } else {
            post(route('user.wo.checkout', [id, taskId]), {
                preserveScroll: true,
                onSuccess: () => {
                    onSuccessMessage('Technician Checked out Successfully');
                },
                onError: (e) => {
                    onSuccessMessage(e);
                }
            });
        }

    };

    const [addTechCloseout, setAddCloseOut] = useState(null);

    const [addCloseout, setAddClose] = useState(false);

    const handleAddCloseout = (techId) => {
        setAddCloseOut(techId);
        setData({ note: null });
    }

    const handleAddClose = () => {
        setAddClose(true);
        setData({ note: null });
    }

    const addCloseoutNote = (e, techId) => {
        e.preventDefault();

        post(route('user.closeoutnote.store', [id, techId]), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Closeout Note Added Successfully');
                setAddCloseOut(null);
                setAddClose(false);
                setData({ note: null })
            },
            onError: (e) => {
                onSuccessMessage(e);
            }
        });

    };


    const addFilePhoto = async (e, taskId) => {
        // Check if a file was selected
        if (e.target.files && e.target.files.length > 0) {
            const file = e.target.files[0];

            // Create a FormData object
            const formData = new FormData();
            formData.append('file', file); // Append the selected file to FormData

            try {
                // Send the request using Fetch API or Axios
                const response = await fetch(route('user.wo.uploadFilePhoto', taskId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, // Include CSRF token
                    },
                    body: formData, // Use FormData as the request body
                });

                if (response.ok) {
                    const result = await response.json();
                    console.log('File Uploaded Successfully', result);
                    onSuccessMessage('File Uploaded Successfully');
                } else {
                    console.error('File Upload Failed:', response.statusText);
                    onSuccessMessage('File upload failed.');
                }
            } catch (error) {
                console.error('Upload Error:', error);
                onSuccessMessage('File upload failed.');
            }
        } else {
            console.log('No file selected.');
        }
    };



    return (
        <div>
            <DragDropContext onDragEnd={onDragEnd}>
                <Droppable droppableId="task-list">
                    {(provided) => (
                        <div ref={provided.innerRef} {...provided.droppableProps} className="card bg-white shadow-lg border-0 mb-4">
                            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Tasks {details?.technician?.tech_type == 'individual' && 'for ' + details?.technician?.company_name}</h3>
                                <TaskModal id={id} details={details} onSuccessMessage={onSuccessMessage} />

                            </div>
                            <div className="card-body bg-white">
                                {
                                    details?.technician?.tech_type == 'individual' &&
                                    <div className="form-check px-4 py-3 mb-4 d-flex justify-content-between" style={{ backgroundColor: '#E3F2FD', cursor: 'pointer' }}>
                                        <div className="w-100">
                                            <form className="row">

                                                <label className="form-check-label col-10">
                                                    <input
                                                        onChange={(e) => makeCheckin(e, null)}
                                                        className="form-check-input ms-0 me-2"
                                                        type="checkbox"
                                                        value="1"
                                                        disabled={details.stage !== 3 || !details.ftech_id}
                                                        checked={
                                                            details.check_in_out?.some((check_in_out) => check_in_out.check_in && !check_in_out.check_out) || false
                                                        }
                                                    />
                                                    <div>
                                                        {(() => {
                                                            const lastCheckInOut = details?.check_in_out?.slice(-1)[0];

                                                            if (lastCheckInOut?.check_in && !lastCheckInOut?.check_out) {
                                                                return 'Checked in';
                                                            } else if (lastCheckInOut?.check_out) {
                                                                return 'Check in again';
                                                            } else {
                                                                return 'Check in';
                                                            }
                                                        })()}

                                                        <p className="mb-0 nrml-txt" style={{ fontWeight: 300, fontSize: '12px', color: '#808080' }}>
                                                            Check in at {currentTime} {timezone}
                                                        </p>
                                                    </div>
                                                </label>

                                                {details.check_in_out?.length > 0 &&
                                                    (details.check_in_out[details.check_in_out.length - 1].check_out ||
                                                        !details.check_in_out[details.check_in_out.length - 1].check_in) && (
                                                        <button
                                                            type="button"
                                                            className="btn btn-dark col-2 addReasonButton"
                                                            onClick={() => handleAddDefaultReasonClick()}
                                                        >
                                                            + Add reason
                                                        </button>
                                                    )}

                                                <textarea
                                                    id='reason'
                                                    name="reason"
                                                    className="col-12 mt-3 reasonTextarea"
                                                    placeholder="Enter Reason"
                                                    style={{ display: 'none' }}
                                                    onChange={(e) => setData({ checkin_note: e.target.value })}
                                                />
                                            </form>
                                        </div>
                                    </div>
                                }

                                {
                                    provided.placeholder &&

                                    <div className='w-full p-20' style={{ backgroundColor: 'rgb(231, 223, 223)' }}>{provided.placeholder}</div>
                                }
                                {
                                    details?.tasks?.filter((task) => task.tech_id === null && task.type !== 'closeout_note').map((task, index) => (
                                        <Draggable
                                            key={task.id.toString()}
                                            draggableId={task.id.toString()}
                                            index={index}
                                        >
                                            {(provided) => (
                                                <div ref={provided.innerRef}
                                                    {...provided.draggableProps}
                                                    {...provided.dragHandleProps} key={task.id} className="px-4 py-3 mb-4 d-flex justify-content-between action-cards" style={{ backgroundColor: '#E3F2FD', cursor: 'pointer' }} draggable >
                                                    <div className="d-flex">
                                                        {
                                                            (task.type === 'upload_file' || task.type === 'upload_photo') && !task.file ? (
                                                                <form id="completeTaskForm" className="me-2">
                                                                    <label
                                                                        htmlFor={`completeTaskFileUpload-${task.id}`}
                                                                        className="border bg-white rounded"
                                                                        style={{ width: '16px', height: '16px' }}
                                                                    >
                                                                        {/* Optional: Add an icon or text inside the label for accessibility */}
                                                                    </label>
                                                                    <input
                                                                        type="file"
                                                                        id={`completeTaskFileUpload-${task.id}`}
                                                                        className="invisible"
                                                                        onChange={(e) => addFilePhoto(e, task.id)}
                                                                    />
                                                                </form>
                                                            ) : (
                                                                <form
                                                                    id="completeTaskForm"
                                                                    className="me-2"
                                                                    onSubmit={(e) => completeTaskForm(e, task.id, task.is_completed)}
                                                                >
                                                                    <input
                                                                        type="checkbox"
                                                                        id={`completeTaskCheckbox-${task.id}`}
                                                                        onChange={(e) => completeTaskForm(e, task.id, task.is_completed)}
                                                                        checked={!!task.is_completed}
                                                                    />
                                                                </form>
                                                            )
                                                        }



                                                        <label className="form-check-label">
                                                            <form onSubmit={(e) => submit(e, task.id)}>
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
                                                                        Uploaded File {task.file?.replace('docs/tasks/', '')}
                                                                    </span>
                                                                }

                                                                {
                                                                    task.type == 'upload_photo' &&
                                                                    <span>
                                                                        Upload/Take Photo {task.file?.replace('docs/tasks/', '')}
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
                                                                        Description: {task.description}</p>
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
                                                                                <textarea name="desc" className="form-control" placeholder="Enter Description" id="floatingTextarea2" style={{ height: 100 }} defaultValue={task.description} onChange={(e) => setData({ ...data, desc: e.target.value })} />
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
                                                            <button onClick={() => handleEdit(index)} className="btn edit-btn" style={{ height: 'max-content' }}>
                                                                <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                                                            </button>
                                                        }

                                                        {
                                                            editable == index &&
                                                            <button onClick={(e) => submit(e, task.id)} className="btn btn-success fw-bold" style={{ height: 'max-content' }}>
                                                                Save
                                                            </button>
                                                        }
                                                        {
                                                            editable == index &&
                                                            <button onClick={() => setEditable(null)} className="btn btn-danger fw-bold" style={{ height: 'max-content' }}>
                                                                Cancel
                                                            </button>
                                                        }
                                                        {
                                                            editable != index &&
                                                            <button onClick={(e) => deleteTask(e, task.id)} type="button" className="btn" style={{ height: 'max-content' }}>
                                                                <i className="fa-solid fa-trash text-danger" aria-hidden="true" />
                                                            </button>
                                                        }

                                                    </div>
                                                </div>
                                            )}
                                        </Draggable>

                                    ))
                                }

                                {
                                    details?.technician?.tech_type == 'individual' &&
                                    <div
                                        className="form-check px-4 py-3 mb-4 d-flex justify-content-between"
                                        style={{ backgroundColor: '#E3F2FD', cursor: 'pointer' }}
                                    >
                                        <div className="w-100">
                                            <form
                                                className="row"
                                            >

                                                <label className="form-check-label col-10">
                                                    <input
                                                        onChange={(e) => makeCheckout(e, null)}
                                                        className="form-check-input ms-0 me-2"
                                                        type="checkbox"
                                                        value=""
                                                        disabled={details.stage != 3}
                                                        checked={!!details.check_in_out?.slice(-1)[0]?.check_out}
                                                    />
                                                    <div>
                                                        {(() => {
                                                            const lastCheckInOut = details?.check_in_out?.slice(-1)[0];

                                                            if (lastCheckInOut?.check_out) {
                                                                return 'Checked out';
                                                            } else {
                                                                return 'Check out';
                                                            }
                                                        })()}

                                                        <p
                                                            className="mb-0 nrml-txt"
                                                            style={{ fontWeight: 300, fontSize: '12px', color: '#808080' }}
                                                        >
                                                            Check out at {currentTime} {timezone}
                                                        </p>
                                                    </div>
                                                </label>
                                            </form>
                                        </div>
                                    </div>
                                }
                                {
                                    details?.technician?.tech_type == 'individual' &&
                                    <div className='mt-3'>
                                        {
                                            addCloseout ?
                                                <div>
                                                    <textarea name="" className='w-100 p-3 border' placeholder='Add Closeout Note' onChange={(e) => setData({ note: e.target.value })}></textarea>
                                                    <div className='d-flex justify-content-end gap-2'>
                                                        <button className='btn btn-outline-primary' onClick={(e) => addCloseoutNote(e, null)}>Save</button>
                                                        <button className='btn btn-outline-danger' onClick={() => setAddClose(false)}>Cancel</button>
                                                    </div>
                                                </div> :
                                                <button className='btn btn-outline-dark' onClick={handleAddClose}>+ Add Closeout Note</button>
                                        }
                                        {
                                            details?.notes?.map((note) => (
                                                note.note_type == 'close_out_notes' &&
                                                <div className='px-4 py-3 mt-3' style={{ backgroundColor: 'rgb(227, 242, 253)' }}>
                                                    <h6>Closeout Note:</h6>
                                                    {note.note}
                                                </div>
                                            ))
                                        }
                                    </div>
                                }

                            </div>
                        </div>
                    )}
                </Droppable>

                {
                    details?.assigned_tech?.map((tech) => (
                        details?.technician?.tech_type == 'company' &&
                        <Droppable droppableId={tech.tech_id.toString()} key={tech.tech_id}>
                            {(provided) => (
                                <div ref={provided.innerRef} {...provided.droppableProps} className="card bg-white shadow-lg border-0 mb-4">
                                    <div className="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style={{ fontSize: 20, fontWeight: 600 }}>Tasks for {tech.engineer.name}</h3>
                                        <TaskModal id={id} details={details} techId={tech.tech_id} onSuccessMessage={onSuccessMessage} />

                                    </div>
                                    <div className="card-body bg-white">
                                        <div className="form-check px-4 py-3 mb-4 d-flex justify-content-between" style={{ backgroundColor: '#E3F2FD', cursor: 'pointer' }}>
                                            <div className="w-100">
                                                <form onSubmit={(e) => makeCheckin(e, tech.tech_id)} id={`checkinForm-${tech.tech_id}`} className="row">

                                                    <label className="form-check-label col-10" htmlFor={`checkin-${tech.tech_id}`}>
                                                        <input
                                                            onChange={(e) => makeCheckin(e, tech.tech_id)}
                                                            className="form-check-input ms-0 me-2"
                                                            type="checkbox"
                                                            value="1"
                                                            id={`checkin-${tech.tech_id}`}
                                                            disabled={details.stage != 3 || !details.ftech_id}
                                                            checked={
                                                                details.check_in_out?.some(
                                                                    (checkInOut) =>
                                                                        checkInOut.tech_id === tech.tech_id &&
                                                                        checkInOut.check_in &&
                                                                        !checkInOut.check_out
                                                                )
                                                            }

                                                        />
                                                        <div>
                                                            {details.check_in_out?.length > 0 ? (
                                                                details.check_in_out
                                                                    .slice()
                                                                    .reverse()
                                                                    .find((checkInOut) => checkInOut.tech_id === tech.tech_id)?.check_in &&
                                                                    !details.check_in_out
                                                                        .slice()
                                                                        .reverse()
                                                                        .find((checkInOut) => checkInOut.tech_id === tech.tech_id)?.check_out ? (
                                                                    'Checked in'
                                                                ) : details.check_in_out
                                                                    .slice()
                                                                    .reverse()
                                                                    .find((checkInOut) => checkInOut.tech_id === tech.tech_id)?.check_out ? (
                                                                    'Check in again'
                                                                ) : (
                                                                    'Check in'
                                                                )
                                                            ) : (
                                                                'Check in'
                                                            )}


                                                            <p className="mb-0 nrml-txt" style={{ fontWeight: 300, fontSize: '12px', color: '#808080' }}>
                                                                Check in at {currentTime} {timezone}
                                                            </p>
                                                        </div>
                                                    </label>

                                                    {details.check_in_out?.length > 0 &&
                                                        (details.check_in_out[details.check_in_out.length - 1].check_out ||
                                                            !details.check_in_out[details.check_in_out.length - 1].check_in) && (
                                                            <button
                                                                type="button"
                                                                data-id={`reason-${tech.id}`}
                                                                className="btn btn-dark col-2 addReasonButton"
                                                                onClick={() => handleAddReasonClick(tech.id)}
                                                            >
                                                                + Add reason
                                                            </button>
                                                        )}

                                                    <textarea
                                                        name="reason"
                                                        id={`reason-${tech.id}`}
                                                        className="col-12 mt-3 reasonTextarea"
                                                        placeholder="Enter Reason"
                                                        style={{ display: 'none' }}
                                                        onChange={(e) => setData({ checkin_note: e.target.value })}
                                                    />
                                                </form>
                                            </div>
                                        </div>
                                        {
                                            provided.placeholder &&

                                            <div className='w-full p-20' style={{ backgroundColor: 'rgb(231, 223, 223)' }}>{provided.placeholder}</div>
                                        }
                                        {
                                            details?.tasks?.filter((task) => task.tech_id != null && task.type !== 'closeout_note' && task.tech_id == tech.tech_id).map((task, index) => (
                                                <Draggable
                                                    key={task.id.toString()}
                                                    draggableId={task.id.toString()}
                                                    index={index}
                                                >
                                                    {(provided) => (
                                                        <div ref={provided.innerRef}
                                                            {...provided.draggableProps}
                                                            {...provided.dragHandleProps} className="px-4 py-3 mb-4 d-flex justify-content-between action-cards" style={{ backgroundColor: '#E3F2FD', cursor: 'pointer' }} draggable>
                                                            <div className="d-flex">
                                                                <form id="completeTaskForm" className='me-2' onSubmit={(e) => completeTaskForm(e, task.id, task.is_completed)}>
                                                                    <input type="checkbox" id="completeTaskCheckbox" onChange={(e) => completeTaskForm(e, task.id, task.is_completed)} checked={task.is_completed} />
                                                                </form>

                                                                <label className="form-check-label">
                                                                    <form onSubmit={(e) => submit(e, task.id)}>
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
                                                                                Uploaded File {task.file?.replace('docs/tasks/', '')}
                                                                            </span>
                                                                        }

                                                                        {
                                                                            task.type == 'upload_photo' &&
                                                                            <span>
                                                                                Upload/Take Photo {task.file?.replace('docs/tasks/', '')}
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
                                                                                Description: {task.description}</p>
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
                                                                                        <textarea name="desc" className="form-control" placeholder="Enter Description" id="floatingTextarea2" style={{ height: 100 }} defaultValue={task.description} onChange={(e) => setData({ ...data, desc: e.target.value })} />
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
                                                                    <button onClick={() => handleEdit(index)} className="btn edit-btn" style={{ height: 'max-content' }}>
                                                                        <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                                                                    </button>
                                                                }

                                                                {
                                                                    editable == index &&
                                                                    <button onClick={(e) => submit(e, task.id)} className="btn btn-success fw-bold" style={{ height: 'max-content' }}>
                                                                        Save
                                                                    </button>
                                                                }
                                                                {
                                                                    editable == index &&
                                                                    <button onClick={() => setEditable(null)} className="btn btn-danger fw-bold" style={{ height: 'max-content' }}>
                                                                        Cancel
                                                                    </button>
                                                                }
                                                                {
                                                                    editable != index &&
                                                                    <button onClick={(e) => deleteTask(e, task.id)} type="button" className="btn" style={{ height: 'max-content' }}>
                                                                        <i className="fa-solid fa-trash text-danger" aria-hidden="true" />
                                                                    </button>
                                                                }

                                                            </div>
                                                        </div>
                                                    )}
                                                </Draggable>

                                            ))
                                        }
                                        <div
                                            className="form-check px-4 py-3 mb-4 d-flex justify-content-between"
                                            style={{ backgroundColor: '#E3F2FD', cursor: 'pointer' }}
                                        >
                                            <div className="w-100">
                                                <form
                                                    id={`checkoutForm-${tech.tech_id}`}
                                                    onSubmit={(e) => makeCheckout(e, tech.tech_id)}
                                                    className="row"
                                                >

                                                    <label className="form-check-label col-10" htmlFor={`checkout-${tech.tech_id}`}>
                                                        <input
                                                            onChange={(e) => makeCheckout(e, tech.tech_id)}
                                                            className="form-check-input ms-0 me-2"
                                                            type="checkbox"
                                                            value=""
                                                            id={`checkout-${tech.tech_id}`}
                                                            disabled={details.stage != 3}
                                                            checked={
                                                                details.check_in_out
                                                                    .filter((checkInOut) => checkInOut.tech_id === tech.tech_id)
                                                                    .slice(-1)[0]?.check_out
                                                            }


                                                        />
                                                        <div>
                                                            {details.check_in_out?.length > 0 &&
                                                                details.check_in_out
                                                                    .filter((checkInOut) => checkInOut.tech_id === tech.tech_id)
                                                                    .slice(-1)[0]?.check_out ? (
                                                                "Checked out"
                                                            ) : (
                                                                "Check out"
                                                            )}

                                                            <p
                                                                className="mb-0 nrml-txt"
                                                                style={{ fontWeight: 300, fontSize: '12px', color: '#808080' }}
                                                            >
                                                                Check out at {currentTime} {timezone}
                                                            </p>
                                                        </div>
                                                    </label>
                                                </form>
                                            </div>
                                        </div>

                                        <div className='mt-3'>
                                            {
                                                addTechCloseout == tech.id ?
                                                    <div>
                                                        <textarea name="" className='w-100 p-3 border' placeholder='Add Closeout Note' onChange={(e) => setData({ note: e.target.value })}></textarea>
                                                        <div className='d-flex justify-content-end gap-2'>
                                                            <button className='btn btn-outline-primary' onClick={(e) => addCloseoutNote(e, tech.id)}>Save</button>
                                                            <button className='btn btn-outline-danger' onClick={() => setAddCloseOut(null)}>Cancel</button>
                                                        </div>
                                                    </div> :
                                                    <button className='btn btn-outline-dark' onClick={() => handleAddCloseout(tech.id)}>+ Add Closeout Note</button>
                                            }
                                            {
                                                details?.notes?.map((note) => (
                                                    note.note_type == 'close_out_notes' && note.tech_id == tech.id &&
                                                    <div className='px-4 py-3 mt-3' style={{ backgroundColor: 'rgb(227, 242, 253)' }}>
                                                        <h6>Closeout Note:</h6>
                                                        {note.note}
                                                    </div>
                                                ))
                                            }
                                        </div>
                                    </div>
                                </div>
                            )}
                        </Droppable>

                    ))
                }
            </DragDropContext>
        </div>
    )
}

export default Task