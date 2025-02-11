import React, { useState } from 'react'
import sidebarIcon from '../../../../../../public/assets/images/sidebar.png'
import OffCanvasData from './components/OffCanvasData';
import { DateTime } from 'luxon';
const WoLog = ({ id, details }) => {
    const [show, setShow] = useState(false);
    const [logData, setLogData] = useState(false);
    const timezone = 'CT'
    const handleClose = () => setShow(false);

    const handleShow = (log) => {
        setShow(true)
        setLogData(log)
    };

    const timezoneMap = {
        'PT': 'America/Los_Angeles',
        'MT': 'America/Denver',
        'CT': 'America/Chicago',
        'CT/MT': 'America/Chicago',
        'ET': 'America/New_York',
        'AKT': 'America/Anchorage',
        'HST': 'Pacific/Honolulu',
    };

    const selectedTimezone = 'America/Chicago';

    const [sortOrder, setSortOrder] = useState('desc');

    const handleSortToggle = () => {
        setSortOrder((prevOrder) => (prevOrder === 'desc' ? 'asc' : 'desc')); // Toggle between asc and desc
    };

    const sortedDetails = [...details].sort((a, b) => {
        const dateA = new Date(a.updated_at);
        const dateB = new Date(b.updated_at);
        return sortOrder === 'desc' ? dateB - dateA : dateA - dateB; // Sort based on the current order
    });

    return (
        <div>
            <div className='bg-white border rounded py-3 px-3 row mb-3'>
                <table className='table table-striped table-hover'>
                    <thead className='border-0'>
                        <tr>
                            <th className='text-start border-0'>Event</th>
                            <th className='text-start border-0'>Date <button onClick={handleSortToggle} className='p-0 border-0 bg-transparent'>
                                {sortOrder === 'desc' ?
                                    <i class="fa-solid fa-arrow-up-wide-short"></i> : <i class="fa-solid fa-arrow-up-short-wide"></i>}
                            </button>
                            </th>
                            <th className='text-start border-0'>By User</th>
                            <th className='text-start border-0'>To User</th>
                            <th className='text-start border-0'></th>
                        </tr>
                    </thead>
                    <tbody className='border-0'>
                        {
                            sortedDetails.map((log) => (
                                <tr className='rounded-3'>
                                    <td className='border-0 fw-bold' style={{ borderRadius: '10px 0 0 10px' }}>
                                        <div className='d-flex justify-content-start align-items-center'>
                                            <p style={{ cursor: 'pointer' }} className='mb-0' onClick={() => handleShow(log)}>
                                                <img src={sidebarIcon} className='me-2' alt="" style={{ width: '20px' }} />
                                            </p>
                                            {log.event_title}
                                        </div>
                                    </td>
                                    <td className='border-0 fw-bold'>
                                        {DateTime.fromSQL(log.recorded_at, { zone: 'Asia/Dhaka' })
                                            .setZone('America/Chicago')
                                            .toFormat('MM/dd/yy hh:mm a')}
                                    </td>

                                    <td className='border-0 fw-bold'>{log.by_user}</td>
                                    <td className='border-0 fw-bold'>{log.to_user}</td>
                                    <td className='border-0 fw-bold w-25' style={{ borderRadius: '0 10px 10px 0' }}></td>

                                </tr>
                            ))
                        }
                        <OffCanvasData show={show} handleClose={handleClose} logData={logData} />
                    </tbody>
                </table>
            </div>
        </div>
    )
}

export default WoLog