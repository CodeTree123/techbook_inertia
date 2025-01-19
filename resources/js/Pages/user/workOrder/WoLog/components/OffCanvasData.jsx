import { DateTime } from 'luxon'
import React from 'react'
import { Accordion, Offcanvas } from 'react-bootstrap'

const OffCanvasData = ({ show, handleClose, logData }) => {
    return (
        <Offcanvas show={show} onHide={handleClose} placement="end" className="w-25" style={{ backgroundColor: 'rgba(248, 249, 250, 1)' }}>
            <Offcanvas.Header closeButton>
                <Offcanvas.Title>{logData.event_title}</Offcanvas.Title>
            </Offcanvas.Header>
            <Offcanvas.Body>
                <div className=''>
                    <div className='d-flex align-items-center gap-3 mb-2'>
                        <i class="fa-regular fa-calendar"></i>
                        <p className='mb-0'>{DateTime.fromSQL(logData.recorded_at)
                            .setZone('America/Chicago')
                            .toFormat('MM/dd/yy')} <span className='text-secondary ms-2' style={{ fontSize: '13px' }}>(2 days ago)</span></p>
                    </div>
                    <div className='d-flex align-items-center gap-3 mb-2'>
                        <i class="fa-regular fa-clock"></i>
                        <p className='mb-0'>{DateTime.fromSQL(logData.recorded_at)
                            .setZone('America/Chicago')
                            .toFormat('hh:mm a')} <span className='text-secondary ms-1' style={{ fontSize: '16px' }}>(CT)</span></p>
                    </div>
                    <div className='d-flex align-items-center gap-3 mb-2'>
                        <i class="fa-regular fa-user"></i>
                        <p className='mb-0'>{logData.by_user} {logData.to_user && <>---&gt; {logData.to_user}</>}</p>
                    </div>

                    <div className='mt-4'>
                        <Accordion defaultActiveKey={['0', '1']} alwaysOpen>
                            {
                                logData.pre_log_id &&
                                <Accordion.Item eventKey="0" className='mb-2 border rounded'>
                                    <Accordion.Header>Previous: <span style={{ fontSize: '14px' }} className='ms-2'>(12/22/2024 at 3:32 PM (EST))</span></Accordion.Header>
                                    <Accordion.Body>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                        minim veniam
                                    </Accordion.Body>
                                </Accordion.Item>
                            }

                            <Accordion.Item eventKey="1" className='mb-2 border rounded'>
                                <Accordion.Header>Updated: <span style={{ fontSize: '14px' }} className='ms-2'>({DateTime.fromSQL(logData.recorded_at)
                            .setZone('America/Chicago')
                            .toFormat('MM/dd/yy')} at {DateTime.fromSQL(logData.recorded_at)
                                .setZone('America/Chicago')
                                .toFormat('hh:mm a')} (CT))</span></Accordion.Header>
                                <Accordion.Body>
                                    {
                                        logData.table_name == 'work_orders' && logData.column_name == 'new' &&
                                        <div dangerouslySetInnerHTML={{ __html: logData.value }} />

                                    }
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