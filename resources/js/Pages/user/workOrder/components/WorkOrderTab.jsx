import React, { useEffect, useState } from 'react'
import { Tab, TabList, TabPanel, Tabs } from 'react-tabs'
import Details from '../Details/Details';
import FieldTech from '../FieldTech/FieldTech';
import Note from '../Note/Note';
import SiteHistory from '../SiteHistory/SiteHistory';
import WoLog from '../WoLog/WoLog';

const WorkOrderTab = ({id, details, onSuccessMessage, onErrorMessage}) => {
    const [tabIndex, setTabIndex] = useState(0);

    return (
        <Tabs selectedIndex={tabIndex} onSelect={(index) => setTabIndex(index)}>
            <TabList className='btn-group w-100 ps-0 mb-4'>
                <Tab className={`btn tab w-100 ${tabIndex == 0 && 'tab-primary'}`} style={{border: '1px solid #9BCFF5'}}>Details</Tab>
                <Tab className={`btn tab w-100 ${tabIndex == 1 && 'tab-primary'}`} style={{border: '1px solid #9BCFF5'}}>Field Tech</Tab>
                <Tab className={`btn tab w-100 ${tabIndex == 2 && 'tab-primary'}`} style={{border: '1px solid #9BCFF5'}}>Notes ({details?.notes.length})</Tab>
                <Tab className={`btn tab w-100 ${tabIndex == 3 && 'tab-primary'}`} style={{border: '1px solid #9BCFF5'}}>WO Logs</Tab>
                <Tab className={`btn tab w-100 ${tabIndex == 4 && 'tab-primary'}`} style={{border: '1px solid #9BCFF5'}}>Site History ({details?.site?.related_wo?.length -1})</Tab>
            </TabList>

            <TabPanel>
                <Details id={id} details={details} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage}/>
            </TabPanel>
            <TabPanel>
                <FieldTech id={id} details={{
                    stage: details?.stage,
                    site: {
                        address_1: details?.site?.address_1,
                        city: details?.site?.city,
                        co_ordinates: details?.site?.co_ordinates,
                        id: details?.site?.id,
                        location: details?.site?.location,
                        state: details?.site?.state,
                        time_zone: details?.site?.time_zone,
                        zipcode: details?.site?.zipcode
                    },
                    assigned_tech: details?.assigned_tech,
                    check_in_out: details?.check_in_out,
                    ftech_id: details?.ftech_id,
                    tech_remove_reasons: details?.tech_remove_reasons,
                    technician: details?.technician,
                    contacted_techs: details?.contacted_techs,
                }} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12}/>
            </TabPanel>
            <TabPanel>
                <Note id={id} details={details?.notes} timezone={details?.site?.time_zone} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage} is_cancelled={details.stage == 7} is_billing={details.status >= 12}/>
            </TabPanel>
            <TabPanel>
                <WoLog id={id} details={details?.time_logs} timezone={details?.site?.time_zone}/>
            </TabPanel>
            <TabPanel>
                <SiteHistory id={id} details={details?.site?.related_wo} timezone={details?.site?.time_zone} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage}/>
            </TabPanel>
        </Tabs>
    )
}

export default WorkOrderTab