import React from 'react'
import { Accordion, Offcanvas } from 'react-bootstrap'

const OffCanvasData = ({ show, handleClose }) => {
    return (
        <Offcanvas show={show} onHide={handleClose} placement="end" className="w-25" style={{ backgroundColor: 'rgba(248, 249, 250, 1)' }}>
            <Offcanvas.Header closeButton>
                <Offcanvas.Title>Dispatch Instructions Updated</Offcanvas.Title>
            </Offcanvas.Header>
            <Offcanvas.Body>
                <div className=''>
                    <div className='d-flex align-items-center gap-3 mb-2'>
                        <i class="fa-regular fa-calendar"></i>
                        <p className='mb-0'>12/22/2024 <span className='text-secondary ms-2' style={{ fontSize: '13px' }}>(2 days ago)</span></p>
                    </div>
                    <div className='d-flex align-items-center gap-3 mb-2'>
                        <i class="fa-regular fa-clock"></i>
                        <p className='mb-0'>3:32 PM <span className='text-secondary ms-1' style={{ fontSize: '16px' }}>(EST)</span></p>
                    </div>
                    <div className='d-flex align-items-center gap-3 mb-2'>
                        <i class="fa-regular fa-user"></i>
                        <p className='mb-0'>Ahmed Dostagir ---&gt; Dilshan Ahmed</p>
                    </div>

                    <div className='mt-4'>
                        <Accordion defaultActiveKey={['0','1']} alwaysOpen>
                            <Accordion.Item eventKey="0" className='mb-2 border rounded'>
                                <Accordion.Header>Previous: <span style={{fontSize: '14px'}} className='ms-2'>(12/22/2024 at 3:32 PM (EST))</span></Accordion.Header>
                                <Accordion.Body>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                    eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                    minim veniam
                                </Accordion.Body>
                            </Accordion.Item>
                            <Accordion.Item eventKey="1" className='mb-2 border rounded'>
                                <Accordion.Header>Updated: <span style={{fontSize: '14px'}} className='ms-2'>(12/22/2024 at 3:32 PM (EST))</span></Accordion.Header>
                                <Accordion.Body>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                    eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                    minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                                    aliquip ex ea commodo consequat. Duis aute irure dolor in
                                    reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                    pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                                    culpa qui officia deserunt mollit anim id est laborum.
                                </Accordion.Body>
                            </Accordion.Item>
                        </Accordion>
                    </div>

                </div>
            </Offcanvas.Body>
        </Offcanvas>
    )
}

export default OffCanvasData