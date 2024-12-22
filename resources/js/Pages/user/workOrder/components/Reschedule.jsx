import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Button, Modal } from 'react-bootstrap';

const Reschedule = ({ id, scheduleData, onSuccessMessage }) => {

    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({
        'on_site_by': scheduleData?.on_site_by,
        'scheduled_time': scheduleData?.scheduled_time,
        'h_operation': scheduleData?.h_operation,
        'schedule_note': scheduleData?.schedule_note,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.reSchedule', id), {
            onSuccess: () => {
                onSuccessMessage('Schedule Updated Successfully');
                setShow(false);
            }
        });
    };

    const [show, setShow] = useState(false);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    return (
        <div className="col-12">
            <div className="border px-4 py-3 rounded shadow mb-0" role="alert">
                No Technician checked in yet !!!
                <p>Want to reschedule time?</p>
                <Button
                    variant="warning"
                    style={{ fontWeight: 600 }}
                    onClick={handleShow}
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
                                Schedule Date ({scheduleData?.on_site_by})
                            </label>
                            <input
                                type="date"
                                defaultValue={scheduleData?.on_site_by}
                                name="on_site_by"
                                className="form-control"
                                onChange={(e)=>setData({...data,on_site_by: e.target.value})}
                            />
                        </div>
                        <div className="mb-3">
                            <label className="form-label">Schedule Time</label>
                            <input
                                type="time"
                                defaultValue={scheduleData?.scheduled_time}
                                name="scheduled_time"
                                className="form-control"
                                onChange={(e)=>setData({...data,scheduled_time: e.target.value})}
                            />
                        </div>
                        <div className="mb-3">
                            <label className="form-label">Approximate Hours to Complete</label>
                            <input
                                type="text"
                                defaultValue={scheduleData?.h_operation}
                                name="h_operation"
                                className="form-control"
                                onChange={(e)=>setData({...data,h_operation: e.target.value})}
                            />
                        </div>
                        <div className="mb-3">
                            <label className="form-label">Schedule Note</label>
                            <textarea
                                defaultValue={scheduleData?.schedule_note}
                                name="schedule_note"
                                className="form-control"
                                rows={3}
                                onChange={(e)=>setData({...data,schedule_note: e.target.value})}
                            />
                        </div>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={handleClose}>
                            Close
                        </Button>
                        <Button variant="warning" onClick={(e)=>submit(e)}>Save</Button>
                    </Modal.Footer>
                </Modal>
            </div>
        </div>
    )
}

export default Reschedule