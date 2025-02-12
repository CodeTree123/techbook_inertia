import { DateTime } from 'luxon';
import React, { useState } from 'react'
import { Dropdown } from 'react-bootstrap';

const Schedule = ({ data, setData, scheduleRef }) => {
    const [addSchedule, setAddSchedule] = useState(false);

    const timezoneMap = {
        'PT': 'America/Los_Angeles',
        'MT': 'America/Denver',
        'CT': 'America/Chicago',
        'ET': 'America/New_York',
        'AKT': 'America/Anchorage',
        'HST': 'Pacific/Honolulu',
    };

    const submit = (e) => {
        e.preventDefault();
        const newSchedule = {
            type: data.type || 'hard_time',
            on_site_by: data.on_site_by || '',
            end_date: data.end_date || '',
            scheduled_time: data.scheduled_time || '',
            end_time: data.end_time || '',
            h_operation: data.h_operation || '',
            estimated_time: data.estimated_time || '',
        };

        setData({
            ...data,
            schedules: [...data?.schedules, newSchedule],
            type: 'hard_time',
            on_site_by: '',
            end_date: '',
            scheduled_time: '',
            end_time: '',
            h_operation: '',
            estimated_time: '',
        });

        setAddSchedule(false);
    };

    const deleteSchedule = (index) => {
        console.log(index);
        
        setData((prevData) => ({
            ...prevData,
            schedules: prevData.schedules.filter((_, i) => i != index),
        }));
    }

    const selectedTimezone = timezoneMap[data?.site?.time_zone];

    // editing

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based
        const day = String(date.getDate()).padStart(2, '0');
        const year = String(date.getFullYear()).slice(-2); // Get last two digits of the year
        return `${month}-${day}-${year}`;
    };

    const formatTime = (timeString) => {
        const date = new Date(`1970-01-01T${timeString}Z`);
        let hours = date.getUTCHours();
        const minutes = String(date.getUTCMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        return `${String(hours).padStart(2, '0')}:${minutes} ${ampm}`;
    };

    const [editableRow, setEditableRow] = useState(null);
    const handleEdit = (index) => {
        setEditableRow(index);
    }

    const handleCancel = () => {
        setEditableRow(null);
    }

    const handleUpdate = (e, scheduleindex) => {
        e.preventDefault();

        const updatedSchedule = data.schedules.map((schedule, index) =>
            index === scheduleindex
                ? {
                    ...schedule,
                    type: data.type || schedule.type,
                    on_site_by: data.on_site_by || schedule.on_site_by,
                    end_date: data.end_date || schedule.end_date,
                    scheduled_time: data.scheduled_time || schedule.scheduled_time,
                    end_time: data.end_time || schedule.end_time,
                    h_operation: data.h_operation || schedule.h_operation,
                    estimated_time: data.estimated_time || schedule.estimated_time,
                }
                : schedule
        );

        // Update the state
        setData({
            ...data,
            schedules: updatedSchedule,
            type: 'hard_time',
            on_site_by: '',
            end_date: '',
            scheduled_time: '',
            end_time: '',
            h_operation: '',
            estimated_time: '',
        });

        // Exit edit mode
        setEditableRow(null);
    };


    return (
        <div ref={scheduleRef} className="card bg-white border mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Schedule</h3>
                <Dropdown>
                    <Dropdown.Toggle variant="outline-dark" id="dropdown-basic">
                        {data.schedule_type == 'single' ? 'Single Schedule' : data.schedule_type == 'multiple' ? 'Multiple Schedule' : '+ Schedule Type'}
                    </Dropdown.Toggle>

                    <Dropdown.Menu>
                        <Dropdown.Item onClick={(e) => setData({ ...data, schedule_type: 'single' })}>Single Schedule</Dropdown.Item>
                        <Dropdown.Item onClick={(e) => setData({ ...data, schedule_type: 'multiple' })}>Multiple Schedule</Dropdown.Item>
                    </Dropdown.Menu>
                </Dropdown>

            </div>
            <div className="card-body bg-white">
                {data?.schedules?.length > 0 && (
                    data?.schedule_type === 'single' ? (
                        // Render a single schedule if there's exactly one schedule
                        <form onSubmit={(e) => handleUpdate(e, 0)} className="position-relative p-3 mb-3" style={{ backgroundColor: '#E3F2FD' }}>
                            <p>{data?.schedules[0]?.type === 'hard_time'
                                ? 'Start at a specific date and time'
                                : data?.schedules[0]?.type === 'between_hours'
                                    ? 'Complete work between specific hours'
                                    : 'Arrive at anytime over a date range'}
                            </p>
                            <b>
                                {data?.schedules[0]?.type != 'date_range' && new Date(data?.schedules[0]?.on_site_by).toLocaleDateString('en-US', { weekday: 'long' }) + ','}
                            </b>
                            <div>
                                <b>
                                    {/* Hard Start */}
                                    {
                                        data?.schedules[0]?.type === 'hard_time' && editableRow !== 0 &&
                                        <span className="nrml-txt">
                                            {DateTime.fromISO(data?.schedules[0]?.on_site_by).toFormat("MM-dd-yy")}
                                            <span className='mx-1'>at</span>
                                            {formatTime(data?.schedules[0]?.scheduled_time)}
                                            ({data?.site?.time_zone})
                                        </span>
                                    }
                                    {
                                        data?.schedules[0]?.type === 'hard_time' && editableRow === 0 &&
                                        <span>
                                            <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={data?.schedules[0]?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                            at
                                            <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold" defaultValue={data?.schedules[0]?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />
                                        </span>
                                    }

                                    {/* Between Hours */}
                                    {
                                        data?.schedules[0]?.type === 'between_hours' && editableRow !== 0 &&
                                        <>
                                            <span className="nrml-txt">
                                                {DateTime.fromISO(data?.schedules[0]?.on_site_by).toFormat("MM-dd-yy")}
                                            </span>
                                            <br />
                                            <span className='me-1'>From: </span>
                                            {formatTime(data?.schedules[0]?.scheduled_time)}
                                            ({data?.site?.time_zone})
                                            <span className='mx-1'>To: </span>
                                            {formatTime(data?.schedules[0]?.end_time)}
                                            ({data?.site?.time_zone})
                                        </>

                                    }
                                    {
                                        data?.schedules[0]?.type === 'between_hours' && editableRow === 0 &&
                                        <>
                                            <span>
                                                <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={data?.schedules[0]?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                            </span>
                                            <br />
                                            <span>
                                                From: <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold me-2" defaultValue={data?.schedules[0]?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />
                                                To: <input type="time" name="end_time" className="mb-2 border-bottom fw-bold" defaultValue={data?.schedules[0]?.end_time} onChange={(e) => setData({ ...data, end_time: e.target.value })} />
                                            </span>
                                        </>
                                    }

                                    {/* Date Range */}

                                    {
                                        data?.schedules[0]?.type === 'date_range' && editableRow !== 0 &&
                                        <>
                                            <span className="nrml-txt">
                                                From: {DateTime.fromISO(data?.schedules[0]?.on_site_by).toFormat("MM-dd-yy")}
                                            </span>
                                            <span className="nrml-txt ms-2">
                                                To: {DateTime.fromISO(data?.schedules[0]?.end_date).toFormat("MM-dd-yy")}
                                            </span>
                                            <br />
                                            <span className='me-1'>At </span>
                                            {formatTime(data?.schedules[0]?.scheduled_time)}
                                            ({data?.site?.time_zone})
                                        </>

                                    }
                                    {
                                        data?.schedules[0]?.type === 'date_range' && editableRow === 0 &&
                                        <>
                                            <span>
                                                From: <input type="date" name="on_site_by" className="mb-2 me-2 border-bottom fw-bold" defaultValue={data?.schedules[0]?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                                To: <input type="date" name="end_date" className="mb-2 border-bottom fw-bold" defaultValue={data?.schedules[0]?.end_date} onChange={(e) => setData({ ...data, end_date: e.target.value })} />
                                            </span>
                                            <br />
                                            <span>
                                                At <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold me-2" defaultValue={data?.schedules[0]?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />

                                            </span>
                                        </>
                                    }
                                </b><br />
                                {
                                    editableRow !== 0 &&
                                    <b className="nrml-txt">Hours of operation: {data?.schedules[0]?.h_operation}</b>
                                }
                                {
                                    editableRow === 0 &&
                                    <input type="text" name="h_operation" placeholder='Hours of operation' className="mb-2 border-bottom fw-bold" defaultValue={data?.schedules[0]?.h_operation} onChange={(e) => setData({ ...data, h_operation: e.target.value })} />
                                }
                                <br />
                                {
                                    editableRow !== 0 &&
                                    <b className="nrml-txt mb-2">Approximate hours to complete: {data?.schedules[0]?.estimated_time} hour(s)</b>
                                }
                                {
                                    editableRow === 0 &&
                                    <input type="text" name="h_operation" placeholder='Estimated hours' className="mb-2 border-bottom fw-bold" defaultValue={data?.schedules[0]?.estimated_time} onChange={(e) => setData({ ...data, estimated_time: e.target.value })} />
                                }
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
                                    <button onClick={() => deleteSchedule(0)} type="button" className="btn" style={{ height: "max-content;" }}>
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
                        data?.schedules?.map((schedule, index) => (
                            <form onSubmit={(e) => handleUpdate(e, index)} className="position-relative p-3 mb-3" style={{ backgroundColor: '#E3F2FD' }} key={schedule.id}>
                                <p>{schedule?.type === 'hard_time'
                                    ? 'Start at a specific date and time'
                                    : schedule?.type === 'between_hours'
                                        ? 'Complete work between specific hours'
                                        : 'Arrive at anytime over a date range'}
                                </p>
                                <b>
                                    {schedule.type != 'date_range' && new Date(schedule.on_site_by).toLocaleDateString('en-US', { weekday: 'long' }) + ','}
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
                                                ({data?.site?.time_zone})
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
                                                ({data?.site?.time_zone})
                                                <span className='mx-1'>To: </span>
                                                {formatTime(schedule?.end_time)}
                                                ({data?.site?.time_zone})
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
                                                ({data?.site?.time_zone})
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
                                    </b><br />
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
                                </div>

                                <div className="d-flex action-group gap-2 position-absolute end-0 top-0 p-3">
                                    {
                                        editableRow !== index &&
                                        <button type='button' onClick={() => setEditableRow(index)} className="btn edit-btn">
                                            <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                                        </button>
                                    }
                                    {
                                        editableRow !== index &&
                                        <button onClick={() => deleteSchedule(index)} type="button" className="btn" style={{ height: "max-content;" }}>
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
                {
                    addSchedule ?
                        <form onSubmit={submit} className="py-3 border-bottom">
                            <div>
                                <div>
                                    <label htmlFor>Schedule Date</label>
                                    <select name="type" className="mb-2 border-bottom w-100" onChange={(e) => setData({ ...data, type: e.target.value })} style={{ fontWeight: 600 }} >
                                        <option value="hard_time" selected>Arrive at a specific date and time</option>
                                        <option value="between_hours">Complete work between specific hours</option>
                                        <option value="date_range">Arrive at a anytime over a date range</option>
                                    </select>

                                    <label htmlFor>{data?.type == 'date_range' ? 'From Date' : 'Schedule Date'}</label>
                                    <input type="date" name="on_site_by" placeholder="Enter Date" className="mb-2 border-bottom w-100" onChange={(e) => setData({ ...data, on_site_by: e.target.value })} style={{ fontWeight: 600 }} />

                                    {data?.type == 'date_range' && <>
                                        <label htmlFor>To Date</label>
                                        <input type="date" name="end_date" placeholder="Enter Date" className="mb-2 border-bottom w-100" onChange={(e) => setData({ ...data, end_date: e.target.value })} style={{ fontWeight: 600 }} />
                                    </>}

                                    <label htmlFor>{data?.type == 'between_hours' ? 'Starting Hours' : 'Schedule Time'}</label>
                                    <input type="time" name="scheduled_time" placeholder="Enter Name" className="mb-2 border-bottom w-100" onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} style={{ fontWeight: 600 }} />

                                    {
                                        data?.type == 'between_hours' &&
                                        <>
                                            <label htmlFor>Ending Hour</label>
                                            <input type="time" name="end_time" placeholder="Enter Name" className="mb-2 border-bottom w-100" onChange={(e) => setData({ ...data, end_time: e.target.value })} style={{ fontWeight: 600 }} />
                                        </>
                                    }

                                    <label htmlFor>Hours Of Operation</label>
                                    <input type="text" name="h_operation" placeholder="Enter Hours Of Operation" className="mb-2 border-bottom w-100" onChange={(e) => setData({ ...data, h_operation: e.target.value })} style={{ fontWeight: 600 }} />

                                    <label htmlFor>Estimated Time (hours)</label>
                                    <input type="number" name="estimated_time" placeholder="Enter Estimated Time" min={1} max={24} className="mb-2 border-bottom w-100" onChange={(e) => setData({ ...data, estimated_time: e.target.value })} style={{ fontWeight: 600 }} />
                                </div>
                            </div>
                            <div className="d-flex action-group gap-2">
                                <button type='submit' className="btn btn-success fw-bold" style={{ height: 'max-content' }}>
                                    Save
                                </button>
                                <button type='button' onClick={() => setAddSchedule(false)} className="btn btn-danger fw-bold" style={{ height: 'max-content' }}>
                                    Cancel
                                </button>
                            </div>
                        </form> :
                        <button type='button' onClick={() => setAddSchedule(true)} className="btn btn-outline-dark addSchedule" style={{ display: 'block' }} disabled={data.schedule_type == 'single' && data?.schedules.length == 1}>+ Add Schedule</button>
                }

            </div>
        </div>
    )
}

export default Schedule