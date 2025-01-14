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
            on_site_by: data.on_site_by || '',
            scheduled_time: data.scheduled_time || '',
            h_operation: data.h_operation || '',
            estimated_time: data.estimated_time || '',
        };

        setData({
            ...data,
            schedules: [...data?.schedules, newSchedule],
            on_site_by: '',
            scheduled_time: '',
            h_operation: '',
            estimated_time: '',
        });

        setAddSchedule(false);
    };

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
                        <form onSubmit={(e) => submit(e, data?.schedules[0]?.id)} className="position-relative p-3 mb-3" style={{ backgroundColor: '#E3F2FD' }}>
                            <p>Start at a specific date and time</p>
                            <b>
                                {new Date(data?.schedules[0]?.on_site_by).toLocaleDateString('en-US', { weekday: 'long' })},
                            </b>
                            <div>
                                <b>
                                    {
                                        editableRow !== 0 &&
                                        <span className="nrml-txt">
                                            {DateTime.fromISO(data?.schedules[0]?.on_site_by).toFormat("MM-dd-yy")}
                                            <span className='mx-1'>at</span>
                                            {formatTime(data?.schedules[0]?.scheduled_time)}
                                        </span>
                                    }
                                    {
                                        editableRow === 0 &&
                                        <span>
                                            <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={data?.schedules[0]?.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                            at
                                            <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold" defaultValue={data?.schedules[0]?.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />
                                        </span>
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
                                    <button onClick={(e) => deleteSchedule(e, data?.schedules[0]?.id)} type="button" className="btn" style={{ height: "max-content;" }}>
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
                {
                    addSchedule ?
                        <form onSubmit={submit} className="py-3 border-bottom">
                            <div>
                                <div>
                                    <label htmlFor>Schedule Date</label>
                                    <input type="date" name="on_site_by" placeholder="Enter Date" className="mb-2 border-bottom w-100" onChange={(e) => setData({ ...data, on_site_by: e.target.value })} style={{ fontWeight: 600 }} />

                                    <label htmlFor>Schedule Time</label>

                                    <input type="time" name="scheduled_time" placeholder="Enter Name" className="mb-2 border-bottom w-100" onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} style={{ fontWeight: 600 }} />

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