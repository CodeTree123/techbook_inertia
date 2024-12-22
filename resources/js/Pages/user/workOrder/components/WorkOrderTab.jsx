import React, { useEffect, useState } from 'react'
import { Tab, TabList, TabPanel, Tabs } from 'react-tabs'
import Details from '../Details/Details';
import FieldTech from '../FieldTech/FieldTech';

const WorkOrderTab = ({id, details, onSuccessMessage, onErrorMessage}) => {
    const [tabIndex, setTabIndex] = useState(0);

    useEffect(() => {
        const savedTabIndex = localStorage.getItem(`tabIndex-${id}`);
        if (savedTabIndex !== null) {
            setTabIndex(parseInt(savedTabIndex, 10));
        } else {
            setTabIndex(0);
        }
    }, [id]);

    useEffect(() => {
        localStorage.setItem(`tabIndex-${id}`, tabIndex.toString());
    }, [tabIndex, id]);


    return (
        <Tabs selectedIndex={tabIndex} onSelect={(index) => setTabIndex(index)}>
            <TabList className='btn-group w-100 ps-0 mb-4'>
                <Tab className={`btn tab w-100 ${tabIndex == 0 && 'tab-primary'}`}>Details</Tab>
                <Tab className={`btn tab w-100 ${tabIndex == 1 && 'tab-primary'}`}>Field Tech</Tab>
                <Tab className={`btn tab w-100 ${tabIndex == 2 && 'tab-primary'}`}>Notes</Tab>
                <Tab className={`btn tab w-100 ${tabIndex == 3 && 'tab-primary'}`}>WO Logs</Tab>
                <Tab className={`btn tab w-100 ${tabIndex == 4 && 'tab-primary'}`}>Site History</Tab>
            </TabList>

            <TabPanel>
                <Details id={id} details={details} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage}/>
            </TabPanel>
            <TabPanel>
                <FieldTech id={id} details={details} onSuccessMessage={onSuccessMessage}/>
            </TabPanel>
            <TabPanel>Content for Tab 3</TabPanel>
            <TabPanel>Content for Tab 4</TabPanel>
            <TabPanel>Content for Tab 5</TabPanel>
        </Tabs>
    )
}

export default WorkOrderTab