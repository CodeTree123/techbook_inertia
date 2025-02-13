import { useForm } from '@inertiajs/react';
import React, { useEffect, useState } from 'react'
import { Button, Modal } from 'react-bootstrap';

const Reschedule = ({ id, is_ftech, scheduleData, onSuccessMessage, onErrorMessage, is_cancelled, is_billing }) => {

    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({
        'type': scheduleData?.type,
        'on_site_by': scheduleData?.on_site_by,
        'end_date': scheduleData?.end_date,
        'scheduled_time': scheduleData?.scheduled_time,
        'end_time': scheduleData?.end_time,
        'h_operation': scheduleData?.h_operation,
        'estimated_time': scheduleData?.estimated_time,
    });

    const submit = (e) => {
        e.preventDefault();

        if (!is_ftech) {
            onErrorMessage('Assign Technician First');
        } else {
            post(route('user.wo.reSchedule', id), {
                onSuccess: () => {
                    onSuccessMessage('Schedule Updated Successfully');
                    setShow(false);
                }
            });
        }
    };

    const [show, setShow] = useState(false);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    useEffect(()=>(
        setData({
            'type': scheduleData?.type,
        'on_site_by': scheduleData?.on_site_by,
        'end_date': scheduleData?.end_date,
        'scheduled_time': scheduleData?.scheduled_time,
        'end_time': scheduleData?.end_time,
        'h_operation': scheduleData?.h_operation,
        'estimated_time': scheduleData?.estimated_time,
        })
    ),[setData, scheduleData])

    return (
        <div className="col-12">
            <div className="border px-4 py-3 rounded shadow mb-0" role="alert">
                No Technician checked in yet !!!
                <p>Want to reschedule time?</p>
                <Button
                    variant="warning"
                    style={{ fontWeight: 600 }}
                    onClick={handleShow}
                    disabled={is_cancelled || is_billing}
                >
                    Reschedule
                </Button>
                <Modal show={show} onHide={handleClose} centered>
                    <Modal.Header closeButton>
                        <Modal.Title>Reschedule Time</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="mb-3">
                            <label className="form-label">
                                Schedule Category
                            </label>
                            <select
                                name="type"
                                className="form-control"
                                onChange={(e) => setData({ ...data, type: e.target.value })}>
                                    <option value="hard_time" selected={scheduleData?.type === 'hard_time'}>Start at a specific date and time</option>
                                    <option value="between_hours" selected={scheduleData?.type === 'between_hours'}>Complete work between specific hours</option>
                                    <option value="date_range" selected={scheduleData?.type === 'date_range'}>Arrive at anytime over a date range</option>
                            </select>
                        </div>
                        <div className="mb-3">
                            <label className="form-label">
                                {data?.type == 'date_range' ? 'From Date' : 'Schedule Date'} ({scheduleData?.on_site_by})
                            </label>
                            <input
                                type="date"
                                defaultValue={scheduleData?.on_site_by}
                                name="on_site_by"
                                className="form-control"
                                onChange={(e) => setData({ ...data, on_site_by: e.target.value })}
                            />
                        </div>
                        {data?.type == 'date_range' && <>
                            <div className='mb-3'>
                                <label htmlFor className="form-label">To Date</label>
                                <input type="date" name="end_date" defaultValue={scheduleData?.end_date} placeholder="Enter Date" className="form-control" onChange={(e) => setData({ ...data, end_date: e.target.value })} style={{ fontWeight: 600 }} />
                            </div>

                        </>}

                        <div className="mb-3">
                            <label className="form-label">{data?.type == 'between_hours' ? 'Starting Hours' : 'Schedule Time'}</label>
                            <input
                                type="time"
                                defaultValue={scheduleData?.scheduled_time}
                                name="scheduled_time"
                                className="form-control"
                                onChange={(e) => setData({ ...data, scheduled_time: e.target.value })}
                            />
                        </div>
                        {
                            data?.type == 'between_hours' &&
                            <>
                                <div className='mb-3'>
                                    <label htmlFor className="form-label">Ending Hour</label>
                                    <input type="time" name="end_time" defaultValue={scheduleData?.end_time} placeholder="Enter Name" className="form-control" onChange={(e) => setData({ ...data, end_time: e.target.value })} style={{ fontWeight: 600 }} />
                                </div>
                            </>
                        }
                        <div className="mb-3">
                            <label className="form-label">Approximate Hours to Complete</label>
                            <input
                                type="text"
                                defaultValue={scheduleData?.h_operation}
                                name="h_operation"
                                className="form-control"
                                onChange={(e) => setData({ ...data, h_operation: e.target.value })}
                            />
                        </div>
                        <div className="mb-3">
                            <label className="form-label">Estimated Time</label>
                            <input
                                type="text"
                                defaultValue={scheduleData?.estimated_time}
                                name="estimated_time"
                                className="form-control"
                                onChange={(e) => setData({ ...data, estimated_time: e.target.value })}
                            />
                        </div>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={handleClose}>
                            Close
                        </Button>
                        <Button variant="warning" onClick={(e) => submit(e)}>Save</Button>
                    </Modal.Footer>
                </Modal>
            </div>
        </div>
    )
}

export default Reschedule