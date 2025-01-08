import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import ScheduleTable from './ScheduleTable';
import { Dropdown } from 'react-bootstrap';
import { DateTime } from 'luxon';

const Schedule = ({ id, details, onSuccessMessage }) => {
    
    const [addSchedule, setAddSchedule] = useState(false);

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'on_site_by': '',
        'scheduled_time': '',
        'h_operation': '',
        'estimated_time': '',
        'schedule_type': '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.createSchedule', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('New Schdule Created Successfully');
                setAddSchedule(false);
                setData(null)
            },
            onError: (error) => {
                console.error('Error updating part:', error);
            }
        });
    };

    const updateScheduleType = (e, value) => {
        e.preventDefault();

        post(route('user.wo.updateScheduleType', [id, value]), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Schdule Type Updated Successfully');
                setData(null)
            },
            onError: (error) => {
                console.error('Error updating part:', error);
            }
        });
    };


    const timezoneMap = {
        'PT': 'America/Los_Angeles',
        'MT': 'America/Denver',
        'CT': 'America/Chicago',
        'ET': 'America/New_York',
        'AKT': 'America/Anchorage',
        'HST': 'Pacific/Honolulu',
    };

    const selectedTimezone = timezoneMap[details?.site?.time_zone];

    return (
        <div className="card bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Schedule</h3>
                <Dropdown>
                    <Dropdown.Toggle variant="outline-dark" id="dropdown-basic">
                        {details.schedule_type == 'single' ? 'Single Schedule' : details.schedule_type == 'multiple' ? 'Multiple Schedule' : '+ Schedule Type'}
                    </Dropdown.Toggle>

                    <Dropdown.Menu>
                        <Dropdown.Item onClick={(e) => updateScheduleType(e, 'single')}>Single Schedule</Dropdown.Item>
                        <Dropdown.Item onClick={(e) => updateScheduleType(e, 'multiple')}>Multiple Schedule</Dropdown.Item>
                    </Dropdown.Menu>
                </Dropdown>

            </div>
            <div className="card-body bg-white">
                <h6 className='mb-3'>Current Site Time: {DateTime.now().setZone(selectedTimezone).toLocaleString(DateTime.TIME_SIMPLE)} ({selectedTimezone})</h6>
                <ScheduleTable details={details} onSuccessMessage={onSuccessMessage} />
                {
                    addSchedule ?
                        <form onSubmit={(e) => submit(e)} className="py-3 border-bottom">
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
                        <button type='button' onClick={() => setAddSchedule(true)} className="btn btn-outline-dark addSchedule" style={{ display: 'block' }} disabled={details.schedule_type == 'single' && details?.schedules.length == 1}>+ Add Schedule</button>
                }

            </div>
        </div>

    )
}

export default Schedule