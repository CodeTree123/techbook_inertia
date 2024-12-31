import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'

const ScheduleTable = ({ details, onSuccessMessage }) => {
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based
        const day = String(date.getDate()).padStart(2, '0');
        const year = String(date.getFullYear()).slice(-2); // Get last two digits of the year
        return `${month}-${day}-${year}`;
    };

    const formatTime = (timeString) => {
        const date = new Date(`1970-01-01T${timeString}Z`); // Append date to parse time
        let hours = date.getUTCHours(); // Use getUTCHours since we're simulating with UTC
        const minutes = String(date.getUTCMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12; // Convert 24-hour to 12-hour format
        return `${String(hours).padStart(2, '0')}:${minutes} ${ampm}`;
    };

    const [editableRow, setEditableRow] = useState(null);

    const handleEdit = (index) => {
        setEditableRow(index);
    }

    const handleCancel = () => {
        setEditableRow(null);
    }

    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({
        'on_site_by': '',
        'scheduled_time': '',
        'h_operation': '',
        'estimated_time': '',
    });

    console.log(data);
    

    const submit = (e, id) => {
        e.preventDefault();

        post(route('user.wo.updateSchedule', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Schedule time is Updated Successfully');
                setEditableRow(null);
                setData(null)
            },
            onError: (error) => {
                console.error('Error updating part:', error);
            }
        });
    };

    const deleteSchedule = (e, id) => {
        e.preventDefault();

        deleteItem(route('user.wo.deleteSchedule', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Schedule time is Deleted Successfully');
                setEditableRow(null);
                setData(null)
            },
            onError: (error) => {
                console.error('Error updating part:', error);
            }
        });
    };

    return (
        <>

            {details?.schedules?.length > 0 && (
                details?.schedule_type === 'single' ? (
                    // Render a single schedule if there's exactly one schedule
                    <form onSubmit={(e) => submit(e, details?.schedules[0]?.id)} className="position-relative p-3 mb-3" style={{ backgroundColor: '#E3F2FD' }}>
                        <p>Start at a specific date and time</p>
                        <b>
                            {new Date(details?.schedules[0]?.on_site_by).toLocaleDateString('en-US', { weekday: 'long' })},
                        </b>
                        <div>
                            <b>
                                {
                                    editableRow !== 0 &&
                                    <span className="nrml-txt">
                                        {formatDate(details?.schedules[0]?.on_site_by)}
                                        <span className='mx-1'>at</span>
                                        {formatTime(details?.schedules[0]?.scheduled_time)}
                                        ({details?.site?.time_zone})
                                    </span>
                                }
                                {
                                    editableRow === 0 &&
                                    <span>
                                        <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={details?.schedules[0]?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                        at
                                        <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold" defaultValue={details?.schedules[0]?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />
                                    </span>
                                }
                            </b>
                            <p>Approximate hours to complete</p>
                            {
                                editableRow !== 0 &&
                                <b className="nrml-txt">Hours of operation: {details?.schedules[0]?.h_operation}</b>
                            }
                            {
                                editableRow === 0 &&
                                <input type="text" name="h_operation" placeholder='Hours of operation' className="mb-2 border-bottom fw-bold" defaultValue={details?.schedules[0]?.h_operation} onChange={(e) => setData({ ...data, h_operation: e.target.value })} />
                            }
                            <br />
                            {
                                    editableRow !== 0 &&
                                    <b className="nrml-txt mb-2">Estimated Hours: {details?.schedules[0]?.estimated_time} hour(s)</b>
                                }
                            {
                                editableRow === 0 &&
                                <input type="text" name="h_operation" placeholder='Estimated hours' className="mb-2 border-bottom fw-bold" defaultValue={details?.schedules[0]?.estimated_time} onChange={(e) => setData({ ...data, estimated_time: e.target.value })} />
                            }
                            <p>Updated by {details?.employee?.name} <span className='mx-1'>on</span>
                                {formatDate(details?.updated_at)}  <span className='mx-1'>at</span>
                                {new Date(details?.updated_at).toLocaleTimeString('en-US', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                })}
                            </p>
                        </div>

                        <div className="d-flex action-group gap-2 position-absolute end-0 top-0 p-3">
                            {
                                editableRow !== 0 &&
                                <button type='button' onClick={() => handleEdit(0)} className="btn edit-btn">
                                    <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                                </button>
                            }
                            {
                                editableRow !== 0 &&
                                <button onClick={(e) => deleteSchedule(e, details?.schedules[0]?.id)} type="button" className="btn" style={{ height: "max-content;" }}>
                                    <i className="fa-solid fa-trash text-danger" aria-hidden="true"></i>
                                </button>
                            }
                            {
                                editableRow === 0 &&
                                <button type='submit' className="btn btn-success fw-bold">
                                    Save
                                </button>
                            }
                            {
                                editableRow === 0 &&
                                <button type='button' className="btn btn-danger fw-bold" onClick={() => handleCancel()}>
                                    Cancel
                                </button>
                            }
                        </div>
                    </form>
                ) : (
                    // Render all schedules if it's not a single schedule or there are multiple schedules
                    details?.schedules?.map((schedule, index) => (
                        <form onSubmit={(e) => submit(e, schedule.id)} className="position-relative p-3 mb-3" style={{ backgroundColor: '#E3F2FD' }} key={schedule.id}>
                            <p>Start at a specific date and time</p>
                            <b>
                                {new Date(schedule.on_site_by).toLocaleDateString('en-US', { weekday: 'long' })},
                            </b>
                            <div>
                                <b>
                                    {
                                        editableRow !== index &&
                                        <span className="nrml-txt">
                                            {formatDate(schedule.on_site_by)}
                                            <span className='mx-1'>at</span>
                                            {formatTime(schedule.scheduled_time)}
                                            ({details?.site.time_zone})
                                        </span>
                                    }
                                    {
                                        editableRow === index &&
                                        <span>
                                            <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={schedule.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                            at
                                            <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold" defaultValue={schedule.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />
                                        </span>
                                    }
                                </b>
                                <p>Approximate hours to complete</p>
                                {
                                    editableRow !== index &&
                                    <b className="nrml-txt">Hours of operation: {schedule.h_operation}</b>
                                }
                                {
                                    editableRow === index &&
                                    <input type="text" name="h_operation" placeholder='Hours of operation' className="mb-2 border-bottom fw-bold" defaultValue={schedule.h_operation} onChange={(e) => setData({ ...data, h_operation: e.target.value })} />
                                }
                                <br />
                                {
                                    editableRow !== index &&
                                    <b className="nrml-txt mb-2">Estimated Hours: {schedule?.estimated_time} hour(s)</b>
                                }
                                {
                                    editableRow === index &&
                                    <input type="text" name="h_operation" placeholder='Estimated hours' className="mb-2 border-bottom fw-bold" defaultValue={schedule?.estimated_time} onChange={(e) => setData({ ...data, estimated_time: e.target.value })} />
                                }
                                <p>Updated by {details?.employee.name} <span className='mx-1'>on</span>
                                    {formatDate(details?.updated_at)}  <span className='mx-1'>at</span>
                                    {new Date(details?.updated_at).toLocaleTimeString('en-US', {
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        hour12: true
                                    })}
                                </p>
                            </div>

                            <div className="d-flex action-group gap-2 position-absolute end-0 top-0 p-3">
                                {
                                    editableRow !== index &&
                                    <button type='button' onClick={() => handleEdit(index)} className="btn edit-btn">
                                        <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                                    </button>
                                }
                                {
                                    editableRow !== index &&
                                    <button onClick={(e) => deleteSchedule(e, schedule.id)} type="button" className="btn" style={{ height: "max-content;" }}>
                                        <i className="fa-solid fa-trash text-danger" aria-hidden="true"></i>
                                    </button>
                                }
                                {
                                    editableRow === index &&
                                    <button type='submit' className="btn btn-success fw-bold">
                                        Save
                                    </button>
                                }
                                {
                                    editableRow === index &&
                                    <button type='button' className="btn btn-danger fw-bold" onClick={() => handleCancel()}>
                                        Cancel
                                    </button>
                                }
                            </div>
                        </form>
                    ))
                )
            )
            }


        </>

    )
}

export default ScheduleTable