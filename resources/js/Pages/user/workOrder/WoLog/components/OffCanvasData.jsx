import { DateTime } from 'luxon'
import React from 'react'
import { Accordion, Offcanvas } from 'react-bootstrap'

const OffCanvasData = ({ show, handleClose, logData }) => {
    
    const renderWorkOrderField = (logData, fieldName) => {
        if (logData.table_name !== 'work_orders' || logData.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'slug':
            case 'wo_requested':
            case 'scope_work':
            case 'r_tools':
            case 'instruction':
            case 'is_hold':
                return <div dangerouslySetInnerHTML={{ __html: logData?.value }} />;

            case 'priority':
                const priorityMap = {
                    1: 'P1',
                    2: 'P2',
                    3: 'P3',
                    4: 'P4',
                    5: 'P5',
                };
                return <p className="m-0">{priorityMap[logData?.value] || ''}</p>;

            case 'stage':
                const stageMap = {
                    1: 'New',
                    2: 'Need Dispatch',
                    3: 'Dispatch',
                    4: 'Closed',
                    5: 'Billing',
                    6: 'On Hold',
                    7: 'Cancelled',
                };
                return <p className="m-0">{stageMap[logData?.value] || ''}</p>;

            case 'status':
                const statusMap = {
                    1: 'Pending',
                    2: 'Contacted',
                    3: 'Confirm',
                    4: 'At Risk',
                    5: 'Delayed',
                    6: 'On Hold',
                    7: 'En Route',
                    8: 'Checked In',
                    9: 'Checked Out',
                    10: 'Needs Approval',
                    11: 'Issue',
                    12: 'Approved',
                    13: 'Invoiced',
                    14: 'Past Due',
                    15: 'Paid',
                };
                return <p className="m-0">{statusMap[logData?.value] || ''}</p>;

            case 'requested_by':
            case 'schedule_type':
            case 'em_id':
            case 'request_type':
            case 'requested_date':
            case 'site_id':
            case 'travel_cost':
            case 'ftech_id':
            case 'ftech_id_del':
                return <p className="m-0">{logData.value}</p>;

            default:
                return null;
        }
    };

    const renderPreWorkOrderField = (logData, fieldName) => {
        if (logData?.pre_log.table_name !== 'work_orders' || logData?.pre_log.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'slug':
            case 'wo_requested':
            case 'scope_work':
            case 'r_tools':
            case 'instruction':
            case 'is_hold':
                return <div dangerouslySetInnerHTML={{ __html: logData?.pre_log.value }} />;

            case 'priority':
                const priorityMap = {
                    1: 'P1',
                    2: 'P2',
                    3: 'P3',
                    4: 'P4',
                    5: 'P5',
                };
                return <p className="m-0">{priorityMap[logData?.pre_log.value] || ''}</p>;


            case 'stage':
                const stageMap = {
                    1: 'New',
                    2: 'Need Dispatch',
                    3: 'Dispatch',
                    4: 'Closed',
                    5: 'Billing',
                    6: 'On Hold',
                    7: 'Cancelled',
                };
                return <p className="m-0">{stageMap[logData?.pre_log.value] || ''}</p>;
            case 'status':
                const statusMap = {
                    1: 'Pending',
                    2: 'Contacted',
                    3: 'Confirm',
                    4: 'At Risk',
                    5: 'Delayed',
                    6: 'On Hold',
                    7: 'En Route',
                    8: 'Checked In',
                    9: 'Checked Out',
                    10: 'Needs Approval',
                    11: 'Issue',
                    12: 'Approved',
                    13: 'Invoiced',
                    14: 'Past Due',
                    15: 'Paid',
                };
                return <p className="m-0">{statusMap[logData?.pre_log.value] || ''}</p>;

            case 'requested_by':
            case 'schedule_type':
            case 'em_id':
            case 'request_type':
            case 'requested_date':
            case 'site_id':
            case 'travel_cost':
            case 'ftech_id':
            case 'ftech_id_del':
                return <p className="m-0">{logData?.pre_log.value}</p>;

            default:
                return null;
        }
    };

    const renderContactField = (logData, fieldName) => {
        if (logData.table_name !== 'contacts' || logData.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'new':
            case 'delete':
                return <div dangerouslySetInnerHTML={{ __html: logData?.value }} />;

            default:
                return <p className="m-0">{logData.value}</p>;
        }
    };

    const renderPreContactField = (logData, fieldName) => {
        if (logData?.pre_log.table_name !== 'contacts' || logData?.pre_log.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'new':
            case 'delete':
                return <div dangerouslySetInnerHTML={{ __html: logData?.pre_log.value }} />;

            default:
                return <p className="m-0">{logData?.pre_log.value}</p>;
        }
    };

    const renderScheduleField = (logData, fieldName) => {
        if (logData.table_name !== 'work_order_schedules' || logData.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'new':
            case 'delete':
            case 'reschedule':
                return <div dangerouslySetInnerHTML={{ __html: logData?.value }} />;

            default:
                return <p className="m-0">{logData.value}</p>;
        }
    };

    const renderPreScheduleField = (logData, fieldName) => {
        if (logData?.pre_log.table_name !== 'work_order_schedules' || logData?.pre_log.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'new':
            case 'delete':
            case 'reschedule':
                return <div dangerouslySetInnerHTML={{ __html: logData?.pre_log.value }} />;

            default:
                return <p className="m-0">{logData?.pre_log.value}</p>;
        }
    };

    const renderTechPartField = (logData, fieldName) => {
        if (logData.table_name !== 'tech_provided_parts' || logData.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'new':
            case 'delete':
                return <div dangerouslySetInnerHTML={{ __html: logData?.value }} />;

            default:
                return <p className="m-0">{logData.value}</p>;
        }
    };

    const renderPreTechPartField = (logData, fieldName) => {
        if (logData?.pre_log.table_name !== 'tech_provided_parts' || logData?.pre_log.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'new':
            case 'delete':
                return <div dangerouslySetInnerHTML={{ __html: logData?.pre_log.value }} />;

            default:
                return <p className="m-0">{logData?.pre_log.value}</p>;
        }
    };

    const renderShipmentField = (logData, fieldName) => {
        if (logData.table_name !== 'order_shipments' || logData.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'new':
            case 'delete':
                return <div dangerouslySetInnerHTML={{ __html: logData?.value }} />;

            default:
                return <p className="m-0">{logData.value}</p>;
        }
    };

    const renderPreShipmentField = (logData, fieldName) => {
        if (logData?.pre_log.table_name !== 'order_shipments' || logData?.pre_log.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'new':
            case 'delete':
                return <div dangerouslySetInnerHTML={{ __html: logData?.pre_log.value }} />;

            default:
                return <p className="m-0">{logData?.pre_log.value}</p>;
        }
    };

    const renderOtherExpensesField = (logData, fieldName) => {
        if (logData.table_name !== 'other_expenses' || logData.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'new':
            case 'delete':
                return <div dangerouslySetInnerHTML={{ __html: logData?.value }} />;

            default:
                return <p className="m-0">{logData.value}</p>;
        }
    };

    const renderPreOtherExpensesField = (logData, fieldName) => {
        if (logData?.pre_log.table_name !== 'other_expenses' || logData?.pre_log.column_name !== fieldName) {
            return null;
        }

        switch (fieldName) {
            case 'new':
            case 'delete':
                return <div dangerouslySetInnerHTML={{ __html: logData?.pre_log.value }} />;

            default:
                return <p className="m-0">{logData?.pre_log.value}</p>;
        }
    };

    const renderCheckInOutField = (logData, fieldName) => {
        if (logData.table_name !== 'check_in_outs' || logData.column_name !== fieldName) {
            return null;
        }

        return <div dangerouslySetInnerHTML={{ __html: logData?.value }} />;
    };

    const renderPreCheckInOutField = (logData, fieldName) => {
        if (logData?.pre_log.table_name !== 'check_in_outs' || logData?.pre_log.column_name !== fieldName) {
            return null;
        }

        return <div dangerouslySetInnerHTML={{ __html: logData?.pre_log.value }} />;
    };

    const renderTaskField = (logData, fieldName) => {
        if (logData.table_name !== 'tasks' || logData.column_name !== fieldName) {
            return null;
        }

        return <div dangerouslySetInnerHTML={{ __html: logData?.value }} />;
    };

    const renderPreTaskField = (logData, fieldName) => {
        if (logData?.pre_log.table_name !== 'tasks' || logData?.pre_log.column_name !== fieldName) {
            return null;
        }

        return <div dangerouslySetInnerHTML={{ __html: logData?.pre_log.value }} />;
    };

    const renderDocForTechField = (logData, fieldName) => {
        if (logData.table_name !== 'doc_for_technicians' || logData.column_name !== fieldName) {
            return null;
        }

        return <p className="m-0">{logData?.value}</p>;
    };

    const renderPreDocForTechField = (logData, fieldName) => {
        if (logData?.pre_log.table_name !== 'doc_for_technicians' || logData?.pre_log.column_name !== fieldName) {
            return null;
        }

        return <p className="m-0">{logData?.pre_log?.value}</p>;
    };

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

                    <div className='mt-4 log-accordion'>
                        <Accordion defaultActiveKey={['0', '1']} alwaysOpen>
                            {
                                logData.pre_log_id &&
                                <Accordion.Item eventKey="0" className='mb-2 border rounded'>
                                    <Accordion.Header>Previous: <span style={{ fontSize: '14px' }} className='ms-2'>({DateTime.fromSQL(logData?.pre_log.recorded_at)
                                        .setZone('America/Chicago')
                                        .toFormat('MM/dd/yy')} at {DateTime.fromSQL(logData?.pre_log.recorded_at)
                                            .setZone('America/Chicago')
                                            .toFormat('hh:mm a')} (CT))</span></Accordion.Header>
                                    <Accordion.Body>
                                        {/* Work Orders */}
                                        {renderPreWorkOrderField(logData, 'slug')}
                                        {renderPreWorkOrderField(logData, 'priority')}
                                        {renderPreWorkOrderField(logData, 'requested_by')}
                                        {renderPreWorkOrderField(logData, 'em_id')}
                                        {renderPreWorkOrderField(logData, 'wo_requested')}
                                        {renderPreWorkOrderField(logData, 'request_type')}
                                        {renderPreWorkOrderField(logData, 'requested_date')}
                                        {renderPreWorkOrderField(logData, 'scope_work')}
                                        {renderPreWorkOrderField(logData, 'r_tools')}
                                        {renderPreWorkOrderField(logData, 'instruction')}
                                        {renderPreWorkOrderField(logData, 'site_id')}
                                        {renderPreWorkOrderField(logData, 'travel_cost')}
                                        {renderPreWorkOrderField(logData, 'ftech_id')}
                                        {renderPreWorkOrderField(logData, 'ftech_id_del')}
                                        {renderPreWorkOrderField(logData, 'is_hold')}
                                        {renderPreWorkOrderField(logData, 'stage')}
                                        {renderPreWorkOrderField(logData, 'status')}
                                        {renderPreWorkOrderField(logData, 'schedule_type')}

                                        {renderPreContactField(logData, logData.column_name)}

                                        {renderPreScheduleField(logData, logData.column_name)}

                                        {renderPreTechPartField(logData, logData.column_name)}

                                        {renderPreShipmentField(logData, logData.column_name)}

                                        {renderPreOtherExpensesField(logData, logData.column_name)}

                                        {renderPreCheckInOutField(logData, logData.column_name)}

                                        {renderPreTaskField(logData, logData.column_name)}

                                        {renderPreDocForTechField(logData, logData.column_name)}
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
                                    {/* Work Orders */}
                                    {renderWorkOrderField(logData, 'slug')}
                                    {renderWorkOrderField(logData, 'priority')}
                                    {renderWorkOrderField(logData, 'requested_by')}
                                    {renderWorkOrderField(logData, 'em_id')}
                                    {renderWorkOrderField(logData, 'wo_requested')}
                                    {renderWorkOrderField(logData, 'request_type')}
                                    {renderWorkOrderField(logData, 'requested_date')}
                                    {renderWorkOrderField(logData, 'scope_work')}
                                    {renderWorkOrderField(logData, 'r_tools')}
                                    {renderWorkOrderField(logData, 'instruction')}
                                    {renderWorkOrderField(logData, 'site_id')}
                                    {renderWorkOrderField(logData, 'travel_cost')}
                                    {renderWorkOrderField(logData, 'ftech_id')}
                                    {renderWorkOrderField(logData, 'ftech_id_del')}
                                    {renderWorkOrderField(logData, 'is_hold')}
                                    {renderWorkOrderField(logData, 'stage')}
                                    {renderWorkOrderField(logData, 'status')}
                                    {renderWorkOrderField(logData, 'schedule_type')}

                                    {renderContactField(logData, logData.column_name)}

                                    {renderScheduleField(logData, logData.column_name)}

                                    {renderTechPartField(logData, logData.column_name)}

                                    {renderShipmentField(logData, logData.column_name)}

                                    {renderOtherExpensesField(logData, logData.column_name)}

                                    {renderCheckInOutField(logData, logData.column_name)}

                                    {renderTaskField(logData, logData.column_name)}

                                    {renderDocForTechField(logData, logData.column_name)}
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