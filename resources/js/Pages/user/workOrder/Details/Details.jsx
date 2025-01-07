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

const Details = ({id, details, onSuccessMessage, onErrorMessage}) => {
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
                    <Overview id={id} details={details} onSuccessMessage={onSuccessMessage}/>
                    <ScopeOfWork id={id} details={details} onSuccessMessage={onSuccessMessage}/>
                    <ToolRequired id={id} details={details} onSuccessMessage={onSuccessMessage}/>
                    <TechProvidedPart id={id} details={details} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage}/>
                    <PartByTC/>
                    <Shipment id={id} details={details} onSuccessMessage={onSuccessMessage} />
                    <DocForTech id={id} details={details} onSuccessMessage={onSuccessMessage} />
                    <Dispatched id={id} details={details} onSuccessMessage={onSuccessMessage} />
                    <Task id={id} details={details} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage}/>
                    <Deliverable id={id} details={details} onSuccessMessage={onSuccessMessage} />
                </div>
                <div className='col-5'>
                    <Contact id={id} details={details} onSuccessMessage={onSuccessMessage} />
                    <Schedule id={id} details={details} onSuccessMessage={onSuccessMessage} />
                    <Location id={id} details={details} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage} />
                    <PaySheet id={id} details={details} onSuccessMessage={onSuccessMessage} />
                    <ProfitSheet/>
                    <TimeLog id={id} details={details} onSuccessMessage={onSuccessMessage} />
                </div>
            </div>
        </div>
    )
}

export default Details