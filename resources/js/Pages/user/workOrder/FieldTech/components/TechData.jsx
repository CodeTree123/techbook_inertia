import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Modal } from 'react-bootstrap';
import Select from "react-select";

const TechData = ({ id, techData, onSuccessMessage, totalhours, assignedEng }) => {

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        reason: '',
        techs: [],
        name: '',
        role: '',
        phone: '',
        email: '',
        tech_type: techData.tech_type,
        company_name: techData.company_name,
    });

    const removeTech = (e) => {
        e.preventDefault();
        post(route('user.wo.removeTech', [id, techData.id]), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Technician Removed Successfully');
                setShowModal(false);
            }
        });
    }

    const [showModal, setShowModal] = useState(false);

    const handleCloseModal = () => {
        setShowModal(false);
    };

    const handleShowModal = () => {
        setShowModal(true);
    };

    const [showAssignModal, setShowAssignModal] = useState(false);

    const handleCloseAssignModal = () => {
        setShowAssignModal(false);
    };

    const handleShowAssignModal = () => {
        setShowAssignModal(true);
    };

    const [selectedOptions, setSelectedOptions] = useState([]);

    const optionsForEng = techData?.engineers?.map((engineer) => ({
        value: engineer.id,
        label: engineer.name
    })) || [];

    const handleChange = (selectedOptions) => {
        setSelectedOptions(selectedOptions);
        setData({ ...data, techs: selectedOptions })
    };

    const addTech = (e) => {
        e.preventDefault();
        post(route('technician.engineer.assign', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Technician Assigned Successfully');
                setShowAssignModal(false);
            }
        });
    }

    const formatDate = (dateString) => {
        if (!dateString) return ''; // Handle null or undefined dates
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('en-US', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }).format(date);
    };


    // Editing

    const [editable, setEdittable] = useState(false);

    const handleEdit = () => {
        setEdittable(true)
        setData(null)
    }

    const editTech = (e) => {
        e.preventDefault();
        post(route('user.wo.editTech', techData.id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Technician Updated Successfully');
                setEdittable(false);
            }
        });
    }

    return (
        <div className='bg-white border rounded-3 p-5 position-relative'>
            <div className='position-absolute' style={{ top: '10px', right: '10px' }}>
                {
                    !editable && <button className='btn btn-outline-primary me-2' onClick={() => handleEdit()}>Edit Tech</button>

                }

                {
                    editable && <button className='btn btn-outline-success me-2' onClick={(e) => editTech(e)}>Save</button>

                }

                {
                    editable && <button className='btn btn-outline-danger me-2' onClick={() => setEdittable(false)}>Cancel</button>

                }

                <button onClick={handleShowModal} className='btn btn-outline-danger'>Remove Tech</button>
            </div>
            <div className='mb-3'>
                <h2>{techData.technician_id} - {!editable && techData.company_name} {!editable && '(' + techData.tech_type + ')'} </h2>
                {
                    editable &&
                    <input type="text" name="" id="" onChange={(e) => setData({ ...data, company_name: e.target.value })} className='form-control' value={techData.company_name} />
                }

                {
                    editable &&
                    <select name="" className='form-select mt-3' id="" onChange={(e) => setData({ ...data, tech_type: e.target.value })}>
                        <option value="individual" selected={techData.tech_type == 'individual'}>Individual</option>
                        <option value="company" selected={techData.tech_type == 'company'}>Company</option>
                    </select>
                }
            </div>
            <div className='d-flex justify-content-start gap-3'>
                {
                    techData.email &&
                    <a className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }} href={`mailto:${techData.email}`}>
                        <i class="fa-regular fa-envelope"></i>
                        {techData.email}
                    </a>
                }

                {
                    techData.phone &&
                    <a className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }} href={`callto:${techData.phone}`}>
                        <i class="fa-solid fa-phone"></i>
                        {techData.phone}
                    </a>
                }
            </div>
            {
                editable &&
                <input type="email" name="" id="" className='form-control mt-3' onChange={(e) => setData({ ...data, email: e.target.value })} placeholder='Email' value={techData.email} />
            }
            {
                editable &&
                <input type="text" name="" id="" className='form-control mt-3' placeholder='Phone' onChange={(e) => setData({ ...data, phone: e.target.value })} value={techData.phone} />
            }

            <div className='mt-4'>
                <p>Address: <b>{techData.address_data['address']}, {techData.address_data['city']}, {techData.address_data['state']} {techData.address_data['zip_code']}</b></p>
                <p>Total Assignments: <b>{techData.wo_ct}</b></p>
            </div>

            <div className='rounded-4 px-4 py-3 mt-4 d-flex justify-content-between align-items-center gap-3' style={{ backgroundColor: '#ECDBE0' }}>

                <div className=' d-flex justify-content-start align-items-center gap-3'>
                    <div className='rounded-4 bg-dark d-flex justify-content-center align-items-center' style={{ width: '50px', height: '50px' }}>
                        <i class="fa-regular fa-clock text-white p-2" style={{ fontSize: '25px' }}></i>
                    </div>
                    <h3 className='mb-0'>Time Spent on this project</h3>
                </div>

                <h2>{totalhours} Hours</h2>
            </div>

            <div className='mt-4 row'>
                <h4>Technician Rate Charts</h4>
                <div className='col-md-3'>
                    <table className='table border'>
                        <tbody>
                            <tr>
                                <td>Standard rate</td>
                                <td>${techData.rate['STD'] ?? 0}</td>
                            </tr>
                            <tr>
                                <td>Emergency rate</td>
                                <td>${techData.rate['EM'] ?? 0}</td>
                            </tr>
                            <tr>
                                <td>OT</td>
                                <td>${techData.rate['OT'] ?? 0}</td>
                            </tr>
                            <tr>
                                <td>SH</td>
                                <td>${techData.rate['SH'] ?? 0}</td>
                            </tr>
                            <tr>
                                <td>Travel fee</td>
                                <td>${techData.travel_fee ?? 0}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            {
                techData.tech_type == 'company' &&
                <div className='mt-4 pb-2 border-bottom'>
                    <h4>Assigned Technicians</h4>
                </div>
            }
            {
                techData.tech_type == 'company' &&
                <div>
                    <button className='btn btn-outline-dark mt-4' onClick={handleShowAssignModal}>+ Add Technician</button>

                    <div className='mt-4'>
                        {
                            assignedEng?.map((eng) => (
                                <div className='mb-3 d-flex justify-content-start align-items-center gap-2'>
                                    {eng?.engineer?.avatar ? (
                                        <img
                                            src={`${window.location.protocol}//${window.location.host}/${eng.engineer.avatar}`}
                                            style={{
                                                width: "50px",
                                                height: "50px",
                                                borderRadius: "50%",
                                                objectFit: "cover",
                                            }}
                                            alt=""
                                        />
                                    ) : (
                                        <div
                                            className="bg-primary d-flex justify-content-center align-items-center text-white text-uppercase fs-3"
                                            style={{
                                                width: "50px",
                                                height: "50px",
                                                borderRadius: "50%",
                                            }}
                                        >
                                            {eng?.engineer?.name?.charAt(0)}
                                        </div>
                                    )}
                                    <div>
                                        <h5 className='mb-0 fs-6'>{eng?.engineer?.name} <span className='fw-light ms-2' style={{ fontSize: '12px' }}>({formatDate(eng?.created_at)})</span></h5>
                                        <p className='mb-0' style={{ fontSize: '12px' }}>{eng?.engineer?.role}</p>
                                    </div>
                                </div>
                            ))
                        }
                    </div>

                </div>
            }



            <Modal show={showModal} onHide={handleCloseModal}>
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Remove This Technician</h5>
                    <button onClick={() => setShowModal(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <div className="mb-3">
                        <label htmlFor="exampleFormControlTextarea1" className="form-label">Removal Reason</label>
                        <textarea className="form-control" rows={3} defaultValue={""} onChange={(e) => setData({ reason: e.target.value })} />
                    </div>
                </Modal.Body>
                <Modal.Footer>
                    <button onClick={() => setShowModal(false)} type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button onClick={(e) => removeTech(e)} type="button" className="btn btn-outline-danger">Remove Tech</button>
                </Modal.Footer>
            </Modal>

            <Modal show={showAssignModal} onHide={handleCloseAssignModal}>
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Assign Technician</h5>
                    <button onClick={() => setShowAssignModal(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <Select
                        options={optionsForEng}
                        isMulti
                        onChange={handleChange}
                        value={selectedOptions}
                        placeholder="Select options..."
                    />
                    <div>
                        <div className="mb-3">
                            <label htmlFor="exampleFormControlInput1" className="form-label">Name</label>
                            <input type="text" className="form-control" id="exampleFormControlInput1" onChange={(e) => setData({ ...data, name: e.target.value })} />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="exampleFormControlInput1" className="form-label">Role</label>
                            <input type="text" className="form-control" id="exampleFormControlInput1" onChange={(e) => setData({ ...data, role: e.target.value })} />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="exampleFormControlInput1" className="form-label">Email</label>
                            <input type="email" className="form-control" id="exampleFormControlInput1" onChange={(e) => setData({ ...data, email: e.target.value })} />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="exampleFormControlInput1" className="form-label">Phone</label>
                            <input type="text" className="form-control" id="exampleFormControlInput1" onChange={(e) => setData({ ...data, phone: e.target.value })} />
                        </div>
                    </div>
                </Modal.Body>
                <Modal.Footer>
                    <button onClick={() => setShowAssignModal(false)} type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button onClick={(e) => addTech(e)} type="button" className="btn btn-outline-primary">Assign Technician</button>
                </Modal.Footer>
            </Modal>
        </div>
    )
}

export default TechData