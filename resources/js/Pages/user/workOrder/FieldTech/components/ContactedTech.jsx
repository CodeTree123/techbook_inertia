import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Offcanvas } from 'react-bootstrap';

const ContactedTech = ({ contacted_techs, onSuccessMessage }) => {
    const [show, setShow] = useState(false);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    const [editable, setEditable] = useState(null)

    const handleCancel = () => {
        setEditable(null)
        setData({
            subject: '',
            res_note: '',
            message: '',
            is_responded: '',
        })
    }


    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({
        subject: '',
        res_note: '',
        message: '',
        is_responded: '',
    });

    const updateContactedTech = (e, techId) => {
        e.preventDefault();
        post(route('user.updateContactedTech', techId), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Contacted Technician Updated Successfully');
                setEditable(null)
            }
        });
    }

    const deleteContactedTech = (e, techId) => {
        e.preventDefault();
        deleteItem(route('user.deleteContactedTech', techId), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Contacted Technician Deleted Successfully');
            }
        });
    }

    return (
        <>
            <button className='btn d-flex align-items-center justify-content-center gap-1 border-0' style={{ backgroundColor: '#AFE1AF' }} onClick={handleShow}>Contacted Techs</button>
            <Offcanvas show={show} onHide={handleClose} placement="end">
                <Offcanvas.Header closeButton>
                    <Offcanvas.Title>Contacted Technicians</Offcanvas.Title>
                </Offcanvas.Header>
                <Offcanvas.Body>
                    {
                        contacted_techs.map((tech, index) => (
                            <div className='p-2 rounded border position-relative mb-3 contactedTechBox'>
                                <h5>#{tech?.tech?.technician_id} - {tech?.tech_name}</h5>
                                <p className='mb-0'><b>Address: </b>{tech?.tech?.address_data?.address && tech?.tech?.address_data?.address + ', '}{tech?.tech?.address_data?.city && tech?.tech?.address_data?.city + ', '}{tech?.tech?.address_data?.state && tech?.tech?.address_data?.state + ', '}{tech?.tech?.address_data?.country && tech?.tech?.address_data?.country + ', '}{tech?.tech?.address_data?.zip_code && tech?.tech?.address_data?.zip_code}</p>

                                {
                                    tech?.tech?.email && editable != index &&
                                    <p className='mb-0'>
                                        <b>Email: </b> {tech?.tech?.email}
                                    </p>
                                }

                                {
                                    tech?.tech?.phone && editable != index &&
                                    <p className='mb-0'>
                                        <b>Phone: </b> {tech?.tech?.phone}
                                    </p>
                                }

                                {
                                    tech?.res_note && editable != index &&
                                    <p className='mb-0'>
                                        <b>Response Note: </b> {tech?.res_note}
                                    </p>
                                }

                                {
                                    tech?.subject && editable != index &&
                                    <p className='mb-0'>
                                        <b>Response Note: </b> {tech?.subject}
                                    </p>
                                }

                                {
                                    tech?.message && editable != index &&
                                    <p className='mb-0'>
                                        <b>Response Note: </b> {tech?.message}
                                    </p>
                                }

                                {
                                    editable != index &&
                                    <p className='mb-0'>
                                        <b>Responded: </b> {tech?.is_responded == 1 ? <span className='text-success'>Responded</span> : <span className='text-danger'>Not Responded</span>}
                                    </p>
                                }

                                {
                                    editable == index &&
                                    <>
                                        <label htmlFor="" className='mt-2'>Response Note</label>
                                        <textarea name="" id="" className='w-100 border py-1 px-2' onChange={(e) => setData({ ...data, res_note: e.target.value })}>{tech?.res_note}</textarea>
                                        <label htmlFor="" className='mt-2'>Subject</label>
                                        <input type="email" className='w-100 border py-1 px-2' defaultValue={tech?.subject} onChange={(e) => setData({ ...data, subject: e.target.value })} />
                                        <label htmlFor="" className='mt-2'>Message</label>
                                        <textarea name="" id="" className='w-100 border py-1 px-2' onChange={(e) => setData({ ...data, message: e.target.value })}>{tech?.message}</textarea>
                                        <label htmlFor="" className='mt-2'>Is Responded?</label>
                                        <select name="" id="" className='w-100 border py-1 px-2' onChange={(e) => setData({ ...data, is_responded: e.target.value })}>
                                            <option value="1" selected={tech?.is_responded == 1}>Yes</option>
                                            <option value="0" selected={tech?.is_responded == 0}>No</option>
                                        </select>
                                    </>
                                }


                                <span className='badge bg-warning text-dark position-absolute top-0 end-0 text-capitalize' style={{ fontSize: '10px' }}>{tech?.tech?.tech_type}</span>

                                {
                                    editable != index &&
                                    <div className='rounded-5 d-flex gap-1 border py-1 px-2 shadow position-absolute top-0 end-0 bg-white contactedTechAction' style={{ width: 'max-content', zIndex: '999' }}>
                                        <button className='bg-transparent border-0 text-primary' onClick={() => setEditable(index)}>
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button className='bg-transparent border-0 text-danger' onClick={(e)=>deleteContactedTech(e, tech.id)}>
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                }

                                {
                                    editable == index &&
                                    <div className='rounded-5 d-flex gap-1 border py-1 px-2 shadow position-absolute top-0 end-0 bg-white' style={{ width: 'max-content', zIndex: '999' }}>
                                        <button className='bg-transparent border-0 text-success' onClick={(e) => updateContactedTech(e, tech.id)}>
                                            <i class="fa-regular fa-floppy-disk"></i>
                                        </button>

                                        <button className='bg-transparent border-0 text-danger' onClick={handleCancel}>
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    </div>
                                }

                            </div>
                        ))
                    }
                </Offcanvas.Body>
            </Offcanvas>
        </>
    )
}

export default ContactedTech