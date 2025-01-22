import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { useEffect } from 'react';
import { Modal } from 'react-bootstrap'

const ContactModal = ({ id, showContactModal, handleContactCloseModal, selectedTechnician, setShowContactModal, onSuccessMessage }) => {
    const [activeFilter, setActiveFilter] = useState(0)

    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({
        is_contacted: false,
        res_note: '',
        to_email: selectedTechnician?.email,
        subject: '',
        body_text: ''
    });

    useEffect(() => {
        setData((prevData) => ({
            ...prevData,
            to_email: selectedTechnician?.email || ''
        }));
    }, [selectedTechnician]);

    const storeContactedTech = (e, techId) => {
        e.preventDefault();
        post(route('user.storeContactedTech', [id, techId]), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Contacted Technician Added Successfully');
                setShowContactModal(false)
            }
        });
    }

    const sendEmail = (e) => {
        e.preventDefault();
        post(route('user.sendmail.tech'), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Email Sent Successfully');
                setShowContactModal(false)
            }
        });
    }

    return (
        <Modal show={showContactModal} onHide={()=>handleContactCloseModal(setData)} size="lg" centered>
            <Modal.Header className='border-bottom-0' style={{ borderTop: '10px solid #afe1af' }}>
                <h3 className="modal-title text-center w-100" id="exampleModalLabel">Contact This Technician</h3>
            </Modal.Header>
            <Modal.Body>
                <div className='d-flex'>
                    <div className='w-50 pe-3'>
                        <h5 className='mb-3'>#{selectedTechnician?.technician_id} - {selectedTechnician?.company_name}</h5>
                        <p className='mb-3'>{
                            selectedTechnician?.address_data &&
                            <div className='d-flex align-items-center gap-2 rounded-5' style={{ width: 'max-content' }}>
                                <b>Address:</b> {[
                                    selectedTechnician?.address_data?.address,
                                    selectedTechnician?.address_data?.city,
                                    selectedTechnician?.address_data?.state,
                                    selectedTechnician?.address_data?.country,
                                    selectedTechnician?.address_data?.zip_code
                                ]
                                    .filter(Boolean)
                                    .join(', ')}
                            </div>
                        }</p>
                    </div>

                    <div className='w-50 ps-3'>
                        <div className='d-flex align-items-center justify-content-end gap-1'>
                            <input type="checkbox" className='' name="" id="contactedCheck" onChange={() => setData({ ...data, is_contacted: !data.is_contacted })} checked={data.is_contacted}/>
                            <label htmlFor="contactedCheck" className='fw-semibold'>Is contacted?</label>
                        </div>
                    </div>
                </div>

                {
                    data.is_contacted == false &&
                    <div className='p-1 rounded d-flex align-items-center gap-3' style={{ backgroundColor: '#F0F0F0' }}>
                        <div className={`${activeFilter == 0 && 'bg-white'} ${activeFilter == 0 && 'shadow'} ${activeFilter == 0 && 'rounded'} h-100 p-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ width: '50%', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(0)}>Email</div>
                        <div className={`${activeFilter == 1 && 'bg-white'} ${activeFilter == 1 && 'shadow'} ${activeFilter == 1 && 'rounded'} h-100 p-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ width: '50%', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(1)}>Phone</div>
                    </div>
                }

                {
                    data.is_contacted == false ?

                        <div>
                            {
                                activeFilter == 0 &&
                                <div>
                                    <label htmlFor="" className='mt-3'>Email</label>
                                    <input type="email" className='w-100 py-1 border-bottom' defaultValue={selectedTechnician?.email} onChange={(e)=>setData({...data, to_email: e.target.value})} />

                                    <label htmlFor="" className='mt-3'>Subject</label>
                                    <input type="text" className='w-100 py-1 border-bottom' onChange={(e)=>setData({...data, subject: e.target.value})} />

                                    <label htmlFor="" className='mt-3'>Message</label>
                                    <textarea name="" className='w-100 py-1 border-bottom' onChange={(e)=>setData({...data, body_text: e.target.value})}></textarea>

                                    <button className='w-100 py-2 border-bottom rounded border-0 mt-3 fw-semibold' style={{ backgroundColor: '#afe1af' }} onClick={(e)=>sendEmail(e)}>Submit</button>
                                </div>

                            }

                            {
                                activeFilter == 1 &&
                                <div>
                                    {
                                        selectedTechnician?.phone &&
                                        <a href={`callto:${selectedTechnician?.phone}`} className='d-flex align-items-center p-3 rounded mt-3 contact-hov' style={{ backgroundColor: '#F0F0F0' }}>
                                            <i class="fa-solid fa-tty pe-3 border-end" style={{ fontSize: '24px' }}></i>
                                            <span className='ps-3 fw-semibold' style={{ fontSize: '20px' }}>{selectedTechnician?.phone}</span>
                                        </a>
                                    }

                                    {
                                        selectedTechnician?.cell_phone &&
                                        <a href={`callto:${selectedTechnician?.cell_phone}`} className='d-flex align-items-center p-3 rounded mt-3 contact-hov' style={{ backgroundColor: '#F0F0F0' }}>
                                            <i class="fa-solid fa-mobile pe-3 border-end" style={{ fontSize: '24px' }}></i>
                                            <span className='ps-3 fw-semibold' style={{ fontSize: '20px' }}>{selectedTechnician?.cell_phone}</span>
                                        </a>
                                    }

                                </div>
                            }
                        </div> : <div>
                            <label htmlFor="" className='mt-3'>Response Note</label>
                            <textarea name="" className='w-100 py-1 border-bottom' onChange={(e) => setData({ ...data, res_note: e.target.value })}></textarea>

                            <button className='w-100 py-2 border-bottom rounded border-0 mt-3 fw-semibold' style={{ backgroundColor: '#afe1af' }} onClick={(e)=>storeContactedTech(e, selectedTechnician.id)}>Submit</button>
                        </div>
                }

            </Modal.Body>
        </Modal>
    )
}

export default ContactModal