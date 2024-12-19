import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import ScheduleTable from './ScheduleTable';

const Schedule = ({id, details, onSuccessMessage}) => {
    const [addSchedule, setAddSchedule] = useState(false);

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'on_site_by': '',
        'scheduled_time': '',
        'h_operation': '',
        'schedule_note': '',
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

    return (
        <div className="card bg-white shadow-lg border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Schedule</h3>
            </div>
            <div className="card-body bg-white">
                <ScheduleTable details={details} onSuccessMessage={onSuccessMessage}/>
                {
                    addSchedule ?
                        <form onSubmit={(e)=>submit(e)} className="py-3 border-bottom">
                            <div>
                                <div>
                                    <label htmlFor>Schedule Date</label>
                                    <input type="date" name="on_site_by" placeholder="Enter Date" className="mb-2 border-bottom w-100" onChange={(e)=>setData({...data, on_site_by: e.target.value})} style={{ fontWeight: 600 }} />

                                    <label htmlFor>Schedule Time</label>
                                    <input type="time" name="scheduled_time" placeholder="Enter Name" className="mb-2 border-bottom w-100" onChange={(e)=>setData({...data, scheduled_time: e.target.value})} style={{ fontWeight: 600 }} />

                                    <label htmlFor>Hours Of Operation</label>
                                    <input type="text" name="h_operation" placeholder="Enter Hours Of Operation" className="mb-2 border-bottom w-100 text-primary" onChange={(e)=>setData({...data, h_operation: e.target.value})} style={{ fontWeight: 600 }} />

                                    <label htmlFor>Schedule Note</label>
                                    <textarea type="text" name="schedule_note" placeholder="Enter Hours Of Operation" className="mb-2 border-bottom w-100" defaultValue={""} onChange={(e)=>setData({...data, schedule_note: e.target.value})} style={{ fontWeight: 600 }}  />
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
                        </form>:
                        <button type='button' onClick={() => setAddSchedule(true)} className="btn btn-outline-dark addSchedule" style={{ display: 'block' }}>+ Add Schedule</button>
                }

                
            </div>
        </div>

    )
}

export default Schedule