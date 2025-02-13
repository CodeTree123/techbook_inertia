import React from 'react'
import Overview from './components/Overview'
import ScopeOfWork from './components/ScopeOfWork'
import ToolRequired from './components/ToolRequired'
import TechProvidedPart from './components/TechProvidedPart'
import PartByTC from './components/PartByTC'
import Shipment from './components/Shipment'
import DocForTech from './components/DocForTech'
import Dispatched from './components/Dispatched'
import Contact from './components/Contact'
import Schedule from './components/Schedule'
import Location from './components/Location'
import PaySheet from './components/PaySheet'
import ProfitSheet from './components/ProfitSheet'
import Task from './components/Task'
import TimeLog from './components/TimeLog'
import Deliverable from './components/Deliverable'
import WorkRequested from './components/WorkRequested'

const Details = ({ id, details, onSuccessMessage, onErrorMessage }) => {
    return (
        <div>
            <div className="mb-4">
                <div className="btn-group" role="group" aria-label="Basic example">
                    <button type="button" className="btn btn-outline-dark">Copy</button>
                    <button type="button" className="btn btn-outline-dark">Print</button>
                    <button type="button" className="btn btn-outline-dark">Save As Template</button>
                </div>
            </div>

            <div className='row'>
                <div className='col-7'>
                    <Overview id={id} details={{
                        company_name: details?.customer?.company_name,
                        priority: details?.priority,
                        p_o: details?.p_o,
                        requested_by: details?.requested_by,
                        employee_name: details?.employee?.name,
                        source: details?.source,
                        
                    }} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12}/>
                    <WorkRequested id={id} details={{
                        wo_requested: details?.wo_requested,
                        requested_date: details?.requested_date,
                        request_type: details?.request_type,
                        order_title: details?.order_title,
                    }} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <ScopeOfWork id={id} details={{ scope_work: details?.scope_work }} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <ToolRequired id={id} details={{ r_tools: details?.r_tools, }} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <TechProvidedPart id={id} details={{
                        ftech_id: details?.ftech_id,
                        tech_provided_parts: details?.tech_provided_parts
                    }} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <PartByTC />
                    <Shipment id={id} details={{
                        shipments: details?.shipments
                    }} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <DocForTech id={id} details={{ docs_for_tech: details?.docs_for_tech }} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <Dispatched id={id} details={{ instruction: details.instruction, }} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <Task id={id} details={{
                        tasks: details?.tasks,
                        ftech_id: details?.ftech_id,
                        stage: details?.stage,
                        check_in_out: details?.check_in_out,
                        site_id: details?.site_id,
                        technician: {
                            tech_type: details?.technician?.tech_type,
                            company_name: details?.technician?.company_name
                        },
                        notes: details?.notes,
                        assigned_tech: details?.assigned_tech
                    }} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <Deliverable id={id} details={details?.tasks} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                </div>
                <div className='col-5'>
                    <Contact id={id} details={details?.contacts} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <Schedule id={id} details={{
                        site: {
                            time_zone: details?.site?.time_zone
                        },
                        schedule_type: details.schedule_type,
                        updated_at: details.updated_at,
                        employee: {
                            name: details?.employee?.name
                        },
                        schedules: details?.schedules
                    }} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <Location id={id} details={{
                        slug: details.slug,
                        site_id: details?.site_id,
                        site: {
                            site_id: details?.site?.site_id,
                            address_1: details?.site?.address_1,
                            city: details?.site?.city,
                            co_ordinates: details?.site?.co_ordinates,
                            id: details?.site?.id,
                            location: details?.site?.location,
                            state: details?.site?.state,
                            time_zone: details?.site?.time_zone,
                            zipcode: details?.site?.zipcode
                        }
                    }} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <PaySheet id={id} details={{
                        technician: {
                            rate: details?.technician?.rate,
                            terms: details?.technician?.terms,
                        },
                        check_in_out: details?.check_in_out?.map(item => ({ total_hours: item.total_hours })) || [],
                        tech_provided_parts: details?.tech_provided_parts,
                        other_expenses: details?.other_expenses,
                        travel_cost: details?.travel_cost,
                        paysheet: details?.paysheet
                    }} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                    <ProfitSheet/>
                    <TimeLog details={{
                        check_in_out: details?.check_in_out,
                        site: {
                            time_zone: details?.site?.time_zone
                        },
                        ftech_id: details?.ftech_id,
                        technician: {
                            company_name: details?.technician?.company_name
                        }
                    }} onSuccessMessage={onSuccessMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12} />
                </div>
            </div>
        </div>
    )
}

export default Details