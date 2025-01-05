import { Link } from '@inertiajs/react'
import { DateTime } from 'luxon'
import React from 'react'

const SiteHistory = ({ id, details, timezone, onSuccessMessage, onErrorMessage }) => {

    const timezoneMap = {
        'PT': 'America/Los_Angeles',
        'MT': 'America/Denver',
        'CT': 'America/Chicago',
        'ET': 'America/New_York',
        'CT/MT': 'America/Chicago',
        'AKT': 'America/Anchorage',
        'HST': 'Pacific/Honolulu',
    };

    const selectedTimezone = timezoneMap[timezone] || 'America/Chicago';

    return (
        <div>
            <div className='bg-white border rounded py-3 px-3 row mb-3'>
                <table className='table table-striped table-hover'>
                    <thead className='border-0'>
                        <tr>
                            <th className='text-start border-0'>Work Order</th>
                            <th className='text-start border-0'>Date</th>
                            <th className='text-start border-0'>Scope Of Work</th>
                            <th className='text-start border-0'>Problem Code</th>
                            <th className='text-start border-0'>Resolution Code</th>
                        </tr>
                    </thead>
                    <tbody className='border-0'>
                        {details?.map((wo) => (
                            wo.id != id &&
                            <tr className='rounded-3'>
                                <td className='border-0 fw-bold' style={{ borderRadius: '10px 0 0 10px' }}>
                                    <Link href={`/user/work/order/view/layout/user/dashboard/inertia/${wo.id}`}>{wo.order_id}</Link>
                                </td>
                                <td className='border-0 fw-bold'>{DateTime.fromISO(wo.created_at, { zone: selectedTimezone }).toFormat('M/d/yyyy')} at {DateTime.fromISO(wo.created_at, { zone: selectedTimezone }).toFormat('HH:mma')} ({timezone})</td>
                                <td
                                    className="border-0 fw-bold position-relative tooltip-trig"

                                >
                                    <div style={{
                                        whiteSpace: 'nowrap',    // Prevent wrapping
                                        overflow: 'hidden',      // Hide overflowing content
                                        textOverflow: 'ellipsis', // Show ellipsis for overflowed text
                                        maxWidth: '400px',       // Set an appropriate max width
                                    }}>
                                        {wo?.scope_work ? wo.scope_work.replace(/<[^>]*>/g, '') : ''}
                                    </div>
                                    {
                                        wo?.scope_work &&
                                        <div className='position-absolute p-2 border rounded shadow bg-white w-100 tooltip-custom' style={{ bottom: '50px' }}>
                                            {wo?.scope_work ? wo.scope_work.replace(/<[^>]*>/g, '') : ''}
                                        </div>
                                    }

                                </td>

                                <td className='border-0 fw-bold'>#595</td>
                                <td className='border-0 fw-bold' style={{ borderRadius: '0 10px 10px 0' }}>#59552</td>
                            </tr>
                        ))}

                    </tbody>
                </table>
            </div>
        </div>
    )
}

export default SiteHistory