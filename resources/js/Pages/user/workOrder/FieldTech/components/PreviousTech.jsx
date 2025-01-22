import React, { useState } from 'react'
import { Offcanvas } from 'react-bootstrap';

const PreviousTech = ({ reasons }) => {
    const [show, setShow] = useState(false);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    return (
        <>
            <button className='btn d-flex align-items-center justify-content-center gap-1 border-0' style={{ backgroundColor: '#9BCFF5' }} onClick={handleShow}>Previous Techs</button>
            <Offcanvas show={show} onHide={handleClose} placement="end">
                <Offcanvas.Header closeButton>
                    <Offcanvas.Title>Previous Technicians</Offcanvas.Title>
                </Offcanvas.Header>
                <Offcanvas.Body>
                    {
                        reasons.map((reason) => (
                            <div className='p-2 rounded border position-relative mb-3'>
                                <h5>#{reason?.deletion_technician?.technician_id} - {reason?.deletion_technician?.company_name}</h5>
                                <p className='mb-0'><b>Address: </b>{reason?.deletion_technician?.address_data?.address && reason?.deletion_technician?.address_data?.address + ', '}{reason?.deletion_technician?.address_data?.city && reason?.deletion_technician?.address_data?.city + ', '}{reason?.deletion_technician?.address_data?.state && reason?.deletion_technician?.address_data?.state + ', '}{reason?.deletion_technician?.address_data?.country && reason?.deletion_technician?.address_data?.country + ', '}{reason?.deletion_technician?.address_data?.zip_code && reason?.deletion_technician?.address_data?.zip_code}</p>
                                {
                                    reason.reason &&
                                    <p className='text-danger mb-0'><b className='text-dark'>Removal reason: </b>{reason.reason}</p>
                                }

                                <span className='badge bg-warning text-dark position-absolute top-0 end-0 text-capitalize' style={{fontSize: '10px'}}>{reason?.deletion_technician?.tech_type}</span>
                            </div>
                        ))
                    }
                </Offcanvas.Body>
            </Offcanvas>
        </>
    )
}

export default PreviousTech