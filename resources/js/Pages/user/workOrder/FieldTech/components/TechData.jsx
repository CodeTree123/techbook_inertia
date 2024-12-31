import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Modal } from 'react-bootstrap';
import Select from "react-select";

const TechData = ({ id, stage, techData, onSuccessMessage, totalhours, assignedEng, setTechnicians }) => {

    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({
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
                setTechnicians([]);
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

    const deleteAssignees = (e, assigneeID) => {
        e.preventDefault();
        deleteItem(route('user.wo.deleteAssignees', assigneeID), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Technician Deleted Successfully');
            }
        });
    }

    const [assigneeEditable, setAssigneeEditable] = useState(null)

    const handleAssigneeEdit = (index) => {
        setAssigneeEditable(index);
        setData(null)
    }
    const handleAssigneeCancel = () => {
        setAssigneeEditable(null);
        setData(null)
    }

    const editAssignees = (e, assigneeID) => {
        e.preventDefault();
        post(route('user.wo.editAssignees', assigneeID), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Technician Updated Successfully');
                setAssigneeEditable(null);
            }
        });
    }

    return (
        <div className='bg-white border rounded-3 p-3 position-relative'>
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

                <button onClick={handleShowModal} className='btn btn-outline-danger'  disabled={stage != 3}>Remove Tech</button>
            </div>
            <div className='mb-3 row'>
                <div className='col-md-8 pe-5'>
                    <h2 className='fs-4'>{techData.technician_id} - {!editable && techData.company_name} {!editable && '(' + techData.tech_type + ')'} </h2>
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

                    <div className='d-flex justify-content-start gap-3 mt-3'>
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

                        {
                            editable &&
                            <input type="email" name="" id="" className='form-control mt-3' onChange={(e) => setData({ ...data, email: e.target.value })} placeholder='Email' value={techData.email} />
                        }
                        {
                            editable &&
                            <input type="text" name="" id="" className='form-control mt-3' placeholder='Phone' onChange={(e) => setData({ ...data, phone: e.target.value })} value={techData.phone} />
                        }
                    </div>

                    <div className='rounded-4 px-2 py-2 mt-4 d-flex justify-content-between align-items-center gap-3' style={{ backgroundColor: '#ECDBE0' }}>

                        <div className=' d-flex justify-content-start align-items-center gap-3'>
                            <div className='rounded-4 bg-dark d-flex justify-content-center align-items-center' style={{ width: '40px', height: '40px' }}>
                                <i class="fa-regular fa-clock text-white p-2" style={{ fontSize: '20px' }}></i>
                            </div>
                            <h3 className='mb-0 fs-5'>Time Spent on this project</h3>
                        </div>

                        <h2 className='mb-0 fs-5'>{totalhours} Hours</h2>
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

                            <div className='mt-4 ms-1 row gap-3'>
                                {
                                    assignedEng?.map((eng, index) => (
                                        <div className='d-flex justify-content-start align-items-center gap-2 border p-2 position-relative' style={{ width: '49%' }}>
                                            <div>
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
                                            </div>

                                            {
                                                assigneeEditable != index ?
                                                    <div>
                                                        <h5 className='mb-0 fs-6'>{eng?.engineer?.name} <span className='fw-light ms-2' style={{ fontSize: '12px' }}>({formatDate(eng?.created_at)})</span>
                                                        </h5>
                                                        <p className='mb-0' style={{ fontSize: '12px' }}>{eng?.engineer?.role}</p>

                                                        <div className='d-flex gap-2 mt-2'>
                                                            <a className="d-flex align-items-center gap-2 p-1 rounded-2" href={`callto:${eng?.engineer?.phone}`} style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <i className="fa-solid fa-phone" aria-hidden="true" style={{ fontSize: '12px' }} />
                                                            </a>
                                                            <a className="d-flex align-items-center gap-2 p-1 rounded-2" href={`mailto:${eng?.engineer?.email}`} style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <i class="fa-regular fa-envelope" style={{ fontSize: '12px' }}></i>
                                                            </a>
                                                        </div>
                                                    </div> :
                                                    <div className='row ms-2'>
                                                        <input type="text" placeholder='Name'
                                                            defaultValue={eng?.engineer?.name}
                                                            className='form-control w-75 mb-2' onChange={(e)=>setData({...data,name: e.target.value})} />

                                                        <input type="text" placeholder='Role'
                                                            defaultValue={eng?.engineer?.role}
                                                            className='form-control w-75 mb-2' onChange={(e)=>setData({...data,role: e.target.value})} />

                                                        <input type="email" placeholder='Email'
                                                            defaultValue={eng?.engineer?.email}
                                                            className='form-control w-75 mb-2' onChange={(e)=>setData({...data,email: e.target.value})} />

                                                        <input type="text" placeholder='Phone'
                                                            defaultValue={eng?.engineer?.phone}
                                                            className='form-control w-75' onChange={(e)=>setData({...data,phone: e.target.value})} />
                                                    </div>
                                            }

                                            <div className='position-absolute end-0'>
                                                {
                                                    assigneeEditable != index &&
                                                    <span className='text-primary mx-2' style={{ cursor: 'pointer' }} onClick={() => handleAssigneeEdit(index)}><i class="fa-solid fa-pen"></i></span>
                                                }
                                                {
                                                    assigneeEditable != index &&
                                                    <span className='text-danger mx-2' style={{ cursor: 'pointer' }} onClick={(e) => deleteAssignees(e, eng.id)}><i class="fa-solid fa-trash"></i></span>
                                                }
                                                {
                                                    assigneeEditable == index &&
                                                    <span className='text-success mx-2' style={{ cursor: 'pointer' }} onClick={(e)=>editAssignees(e, eng?.engineer?.id)}><i class="fa-regular fa-floppy-disk"></i></span>
                                                }
                                                {
                                                    assigneeEditable == index &&
                                                    <span className='text-danger mx-2' style={{ cursor: 'pointer' }} onClick={ handleAssigneeCancel}><i class="fa-solid fa-ban"></i></span>
                                                }
                                            </div>
                                        </div>
                                    ))
                                }
                            </div>

                        </div>
                    }
                </div>

                <div className='col-md-4'>
                    <div className='mt-5'>
                        <p>Address: <b>{techData.address_data['address']}, {techData.address_data['city']}, {techData.address_data['state']} {techData.address_data['zip_code']}</b></p>
                        <p>Total Assignments: <b>{techData.wo_ct}</b></p>
                    </div>

                    <div className='mt-4 row'>
                        <h4>Technician Rate Charts</h4>
                        <div className='col-md-12'>
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
                </div>

            </div>

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
                    <button onClick={(e) => removeTech(e)} type="button" className="btn btn-outline-danger" disabled={stage != 3}>Remove Tech</button>
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