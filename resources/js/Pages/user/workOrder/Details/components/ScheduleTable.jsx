import { useForm } from '@inertiajs/react';
import { DateTime } from 'luxon';
import React, { useState } from 'react'

const ScheduleTable = ({ details, onSuccessMessage, is_cancelled, is_billing }) => {
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
        'end_date': '',
        'scheduled_time': '',
        'end_time': '',
        'h_operation': '',
        'estimated_time': '',
    });


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
                        <p>{details?.schedules[0]?.type === 'hard_time'
                            ? 'Start at a specific date and time'
                            : details?.schedules[0]?.type === 'between_hours'
                                ? 'Complete work between specific hours'
                                : 'Arrive at anytime over a date range'}
                        </p>
                        <b>
                            {details?.schedules[0]?.type != 'date_range' && new Date(details?.schedules[0]?.on_site_by).toLocaleDateString('en-US', { weekday: 'long' }) + ','}
                        </b>
                        <div>
                            <b>
                                {/* Hard Start */}
                                {
                                    details?.schedules[0]?.type === 'hard_time' && editableRow !== 0 &&
                                    <span className="nrml-txt">
                                        {DateTime.fromISO(details?.schedules[0]?.on_site_by).toFormat("MM-dd-yy")}
                                        <span className='mx-1'>at</span>
                                        {formatTime(details?.schedules[0]?.scheduled_time)}
                                        ({details?.site?.time_zone})
                                    </span>
                                }
                                {
                                    details?.schedules[0]?.type === 'hard_time' && editableRow === 0 &&
                                    <span>
                                        <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={details?.schedules[0]?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                        at
                                        <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold" defaultValue={details?.schedules[0]?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />
                                    </span>
                                }

                                {/* Between Hours */}
                                {
                                    details?.schedules[0]?.type === 'between_hours' && editableRow !== 0 &&
                                    <>
                                        <span className="nrml-txt">
                                            {DateTime.fromISO(details?.schedules[0]?.on_site_by).toFormat("MM-dd-yy")}
                                        </span>
                                        <br />
                                        <span className='me-1'>From: </span>
                                        {formatTime(details?.schedules[0]?.scheduled_time)}
                                        ({details?.site?.time_zone})
                                        <span className='mx-1'>To: </span>
                                        {formatTime(details?.schedules[0]?.end_time)}
                                        ({details?.site?.time_zone})
                                    </>

                                }
                                {
                                    details?.schedules[0]?.type === 'between_hours' && editableRow === 0 &&
                                    <>
                                        <span>
                                            <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={details?.schedules[0]?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                        </span>
                                        <br />
                                        <span>
                                            From: <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold me-2" defaultValue={details?.schedules[0]?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />
                                            To: <input type="time" name="end_time" className="mb-2 border-bottom fw-bold" defaultValue={details?.schedules[0]?.end_time} onChange={(e) => setData({ ...data, end_time: e.target.value })} />
                                        </span>
                                    </>
                                }

                                {/* Date Range */}

                                {
                                    details?.schedules[0]?.type === 'date_range' && editableRow !== 0 &&
                                    <>
                                        <span className="nrml-txt">
                                            From: {DateTime.fromISO(details?.schedules[0]?.on_site_by).toFormat("MM-dd-yy")}
                                        </span>
                                        <span className="nrml-txt ms-2">
                                            To: {DateTime.fromISO(details?.schedules[0]?.end_date).toFormat("MM-dd-yy")}
                                        </span>
                                        <br />
                                        <span className='me-1'>At </span>
                                        {formatTime(details?.schedules[0]?.scheduled_time)}
                                        ({details?.site?.time_zone})
                                    </>

                                }
                                {
                                    details?.schedules[0]?.type === 'date_range' && editableRow === 0 &&
                                    <>
                                        <span>
                                            From: <input type="date" name="on_site_by" className="mb-2 me-2 border-bottom fw-bold" defaultValue={details?.schedules[0]?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                            To: <input type="date" name="end_date" className="mb-2 border-bottom fw-bold" defaultValue={details?.schedules[0]?.end_date} onChange={(e) => setData({ ...data, end_date: e.target.value })} />
                                        </span>
                                        <br />
                                        <span>
                                            At <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold me-2" defaultValue={details?.schedules[0]?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />

                                        </span>
                                    </>
                                }
                            </b><br />
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
                                <b className="nrml-txt mb-2">Approximate hours to complete: {details?.schedules[0]?.estimated_time} hour(s)</b>
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
                                <button type='button' onClick={() => handleEdit(0)} disabled={is_cancelled || is_billing} className="btn edit-btn border-0">
                                    <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                                </button>
                            }
                            {
                                editableRow !== 0 &&
                                <button onClick={(e) => deleteSchedule(e, details?.schedules[0]?.id)} disabled={is_cancelled || is_billing} type="button" className="btn border-0" style={{ height: "max-content;" }}>
                                    <i className="fa-solid fa-trash text-danger" aria-hidden="true"></i>
                                </button>
                            }
                            {
                                editableRow === 0 &&
                                <button type='submit' className="btn btn-success border-0 fw-bold" disabled={is_cancelled || is_billing}>
                                    Save
                                </button>
                            }
                            {
                                editableRow === 0 &&
                                <button type='button' className="btn btn-danger border-0 fw-bold" onClick={() => handleCancel()} disabled={is_cancelled || is_billing}>
                                    Cancel
                                </button>
                            }
                        </div>
                    </form>
                ) : (
                    // Render all schedules if it's not a single schedule or there are multiple schedules
                    details?.schedules?.map((schedule, index) => (
                        <form onSubmit={(e) => submit(e, schedule.id)} className="position-relative p-3 mb-3" style={{ backgroundColor: '#E3F2FD' }} key={schedule.id}>
                            <p>{schedule?.type === 'hard_time'
                                ? 'Start at a specific date and time'
                                : schedule?.type === 'between_hours'
                                    ? 'Complete work between specific hours'
                                    : 'Arrive at anytime over a date range'}
                            </p>
                            <b>
                                <b>
                                    {schedule.type != 'date_range' && new Date(schedule.on_site_by).toLocaleDateString('en-US', { weekday: 'long' }) + ','}
                                </b>
                            </b>
                            <div>
                                <b>

                                    {/* Hard Start */}
                                    {
                                        schedule.type === 'hard_time' && editableRow !== index &&
                                        <span className="nrml-txt">
                                            {DateTime.fromISO(schedule?.on_site_by).toFormat("MM-dd-yy")}
                                            <span className='mx-1'>at</span>
                                            {formatTime(schedule?.scheduled_time)}
                                            ({details?.site?.time_zone})
                                        </span>
                                    }
                                    {
                                        schedule?.type === 'hard_time' && editableRow === index &&
                                        <span>
                                            <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={schedule?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                            at
                                            <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold" defaultValue={schedule?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />
                                        </span>
                                    }

                                    {/* Between Hours */}
                                    {
                                        schedule?.type === 'between_hours' && editableRow !== index &&
                                        <>
                                            <span className="nrml-txt">
                                                {DateTime.fromISO(schedule?.on_site_by).toFormat("MM-dd-yy")}
                                            </span>
                                            <br />
                                            <span className='me-1'>From: </span>
                                            {formatTime(schedule?.scheduled_time)}
                                            ({details?.site?.time_zone})
                                            <span className='mx-1'>To: </span>
                                            {formatTime(schedule?.end_time)}
                                            ({details?.site?.time_zone})
                                        </>

                                    }
                                    {
                                        schedule?.type === 'between_hours' && editableRow === index &&
                                        <>
                                            <span>
                                                <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={schedule?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                            </span>
                                            <br />
                                            <span>
                                                From: <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold me-2" defaultValue={schedule?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />
                                                To: <input type="time" name="end_time" className="mb-2 border-bottom fw-bold" defaultValue={schedule?.end_time} onChange={(e) => setData({ ...data, end_time: e.target.value })} />
                                            </span>
                                        </>
                                    }

                                    {/* Date Range */}
                                    {
                                        schedule?.type === 'date_range' && editableRow !== index &&
                                        <>
                                            <span className="nrml-txt">
                                                From: {DateTime.fromISO(schedule?.on_site_by).toFormat("MM-dd-yy")}
                                            </span>
                                            <span className="nrml-txt ms-2">
                                                To: {DateTime.fromISO(schedule?.end_date).toFormat("MM-dd-yy")}
                                            </span>
                                            <br />
                                            <span className='me-1'>At </span>
                                            {formatTime(schedule?.scheduled_time)}
                                            ({details?.site?.time_zone})
                                        </>

                                    }
                                    {
                                        schedule?.type === 'date_range' && editableRow === index &&
                                        <>
                                            <span>
                                                From: <input type="date" name="on_site_by" className="mb-2 me-2 border-bottom fw-bold" defaultValue={schedule?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                                To: <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={schedule?.end_date} onChange={(e) => setData({ ...data, end_date: e.target.value })} />
                                            </span>
                                            <br />
                                            <span>
                                                At <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold me-2" defaultValue={schedule?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />

                                            </span>
                                        </>
                                    }
                                </b>
                                <br />
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
                                    <b className="nrml-txt mb-2">Approximate hours to complete: {schedule?.estimated_time} hour(s)</b>
                                }
                                {
                                    editableRow === index &&
                                    <input type="text" name="h_operation" placeholder='Estimated hours' className="mb-2 border-bottom fw-bold" defaultValue={schedule?.estimated_time} onChange={(e) => setData({ ...data, estimated_time: e.target.value })} />
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