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

    const { data, setData, post, delete:deleteItem, errors, processing, recentlySuccessful } = useForm({
        'on_site_by': '',
        'scheduled_time': '',
        'h_operation': '',
        'schedule_note': '',
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
            {
                details?.schedules?.map((schedule, index) => (
                    <form onSubmit={(e) => submit(e, schedule.id)} className="position-relative p-3 mb-3" style={{ backgroundColor: '#E3F2FD' }}>
                        <p>Start at a specific date and time</p>
                        <b>
                            {new Date(schedule.on_site_by).toLocaleDateString('en-US', { weekday: 'long' })},
                        </b>
                        <div>
                            <b>
                                {
                                    editableRow != index &&
                                    <span className="nrml-txt">
                                        {formatDate(schedule.on_site_by)}
                                        <span className='mx-1'>at</span>
                                        {formatTime(schedule.scheduled_time)}
                                        ({details.site.time_zone})
                                    </span>
                                }
                                {
                                    editableRow == index &&
                                    <span>
                                        <input type="date" name="on_site_by" className="mb-2 border-bottom fw-bold" defaultValue={schedule.on_site_by} onChange={(e) => setData({ ...data, on_site_by: e.target.value })} />
                                        at
                                        <input type="time" name="scheduled_time" className="mb-2 border-bottom fw-bold" defaultValue={schedule.scheduled_time} onChange={(e) => setData({ ...data, scheduled_time: e.target.value })} />
                                        {/* <select name="time_zone" className="mb-2 border-bottom fw-bold">
                                        <option value>Select Timezone</option>
                                        <option value="PT">
                                            America/Los_Angeles (PT)
                                        </option>
                                        <option value="MT">
                                            America/Denver (MT)
                                        </option>
                                        <option value="CT">
                                            America/Chicago (CT)
                                        </option>
                                        <option value="ET" selected>
                                            America/New_York (ET)
                                        </option>
                                        <option value="AKT">
                                            America/Anchorage (AKT)
                                        </option>
                                        <option value="HST">
                                            Pacific/Honolulu (HST)
                                        </option>
                                        <option value="PT/MT">
                                            America/Los_Angeles (PT/MT)
                                        </option>
                                        <option value="CT/MT">
                                            America/Chicago (CT/MT)
                                        </option>
                                        <option value="CT/ET">
                                            America/New_York (CT/ET)
                                        </option>
                                    </select> */}
                                    </span>
                                }

                                {/* <span class="nrml-txt">12-20-24</span>
                                              <span>
                                                  <input type="date" name="on_site_by" class="mb-2 border-bottom nrml-inp fw-bold"
                                                      value="2024-12-20">

                                              </span> at
                                              <span class="nrml-txt">09:00 AM</span>
                                              <span>
                                                  <input type="time" name="scheduled_time" class="mb-2 border-bottom nrml-inp fw-bold" value="12:00">
                                              </span>
                                              <span class="nrml-txt">(ET)</span>
                                              <span>
                                                                                                          <select name="time_zone" class="mb-2 border-bottom nrml-inp fw-bold">
                                                      <option value="">Select Timezone</option>
                                                          <option value="PT" >
                                                          America/Los_Angeles (PT)
                                                      </option>
  <option value="MT" >
                                                          America/Denver (MT)
                                                      </option>
  <option value="CT" >
                                                          America/Chicago (CT)
                                                      </option>
  <option value="ET"  selected >
                                                          America/New_York (ET)
                                                      </option>
  <option value="AKT" >
                                                          America/Anchorage (AKT)
                                                      </option>
  <option value="HST" >
                                                          Pacific/Honolulu (HST)
                                                      </option>
  <option value="PT/MT" >
                                                          America/Los_Angeles (PT/MT)
                                                      </option>
  <option value="CT/MT" >
                                                          America/Chicago (CT/MT)
                                                      </option>
  <option value="CT/ET" >
                                                          America/New_York (CT/ET)
                                                      </option>
                                                      </select>
                                              </span> */}
                            </b>
                            <p>Approximate hours to complete</p>
                            {
                                editableRow != index &&
                                <b className="nrml-txt">{schedule.h_operation}</b>
                            }

                            {
                                editableRow == index &&
                                <input type="text" name="h_operation" placeholder='Hours of operation' className="mb-2 border-bottom fw-bold" defaultValue={schedule.h_operation} onChange={(e) => setData({ ...data, h_operation: e.target.value })} />
                            }

                            <p>updated by {details.employee.name} <span className='mx-1'>on</span>
                                {formatDate(details.updated_at)}  <span className='mx-1'>at</span>
                                {new Date(details.updated_at).toLocaleTimeString('en-US', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                })}
                            </p>

                            {
                                schedule.schedule_note &&
                                <i style={{ color: '#6a6a6a' }} className="nrml-txt">Note: {schedule.schedule_note}</i>
                            }
                            {
                                editableRow == index &&
                                <textarea name="schedule_note" placeholder='Schedule Note' className="mb-2 border-bottom fw-bold" defaultValue={schedule.schedule_note} onChange={(e) => setData({ ...data, schedule_note: e.target.value })} />
                            }

                        </div>

                        <div className="d-flex action-group gap-2 position-absolute end-0 top-0 p-3">
                            {
                                editableRow != index &&
                                <button type='button' onClick={() => handleEdit(index)} className="btn edit-btn">
                                    <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                                </button>
                            }
                            {
                                editableRow != index &&
                                <button onClick={(e)=>deleteSchedule(e,schedule.id)} type="button" className="btn" style={{height: "max-content;"}}>
                                    <i className="fa-solid fa-trash text-danger" aria-hidden="true"></i>
                                </button>}
                            {
                                editableRow == index &&
                                <button type='submit' className="btn btn-success fw-bold">
                                    Save
                                </button>
                            }
                            {
                                editableRow == index &&
                                <button type='button' className="btn btn-danger fw-bold" onClick={() => handleCancel()}>
                                    Cancel
                                </button>
                            }
                        </div>
                    </form>
                ))
            }

        </>

    )
}

export default ScheduleTable