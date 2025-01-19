import React, { useEffect, useRef, useState } from 'react'
import MainLayout from '../layout/MainLayout'
import { Head, useForm } from '@inertiajs/react'
import Overview from './components/Overview'
import ScopeOfWork from './components/ScopeOfWork'
import ToolRequired from './components/ToolRequired'
import Location from './components/Location'
import TechProvidedPart from './components/TechProvidedPart'
import Shipment from './components/Shipment'
import DispatchInstruction from './components/DispatchInstruction'
import Contact from './components/Contact'
import Schedule from './components/Schedule'
import WorkRequested from './components/WorkRequested'
import PaySheet from './components/PaySheet'
import DocForTech from './components/DocForTech'
import Task from './components/Task'

const CreateWorkOrder = ({ type }) => {

    const [techDocFiles, setTechDocFiles] = useState([]);
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'cus_id': '',
        'priority': '',
        'requested_by': '',
        'wo_requested': '',
        'purchase_order': '',
        'problem_code': '',
        'requested_date': '',
        'request_type': '',
        'wo_manager': '',
        'order_type': type,
        'scope_work': '',
        'r_tools': '',
        'site_id': '',
        'techProvidedParts': [],
        'shipments': [],
        'instruction': '',
        'contacts': [],
        'schedule_type': 'single',
        'schedules': [],
        'travel_cost': '',
        'otherExpenses': [],
        'tasks': [],
        'techDocs': [],
        'taskFiles': [],
    });
    

    const [isSticky, setIsSticky] = useState(false);
    const sidebarRef = useRef(null);

    const overviewRef = useRef(null);
    const woReqRef = useRef(null);
    const scopeRef = useRef(null);
    const toolRef = useRef(null);
    const techPartRef = useRef(null);
    const shipmentRef = useRef(null);
    const docTechRef = useRef(null);
    const instructionRef = useRef(null);
    const taskRef = useRef(null);
    const contactRef = useRef(null);
    const scheduleRef = useRef(null);
    const locationRef = useRef(null);
    const payRef = useRef(null);

    const scrollToSection = (ref) => {
        const offset = 130; // Adjust by 130px above
        const elementPosition = ref.current.getBoundingClientRect().top; // Get the position relative to the viewport
        const offsetPosition = window.scrollY + elementPosition - offset; // Calculate the adjusted position

        window.scrollTo({
            top: offsetPosition,
            behavior: "smooth",
        });
    };

    const [successMessage, setSuccessMessage] = useState('');
    const [errorMessage, setErrorMessage] = useState('');
    console.log(data);
    
    const submit = (e) => {
        e.preventDefault();
        
        post(route('user.work.order.store'), {
            preserveScroll: true,
            onSuccess: () => {
                setSuccessMessage('New Work Order Created');
            },
            headers: {
                'Content-Type': 'multipart/form-data', // Ensure multipart headers
            },
        });
    };    

    useEffect(() => {
        if (errorMessage) {
            const timer = setTimeout(() => {
                setErrorMessage('');
            }, 1500);
            return () => clearTimeout(timer);
        }
    }, [errorMessage]);

    useEffect(() => {
        if (successMessage) {
            const timer = setTimeout(() => {
                setSuccessMessage('');
            }, 1500);
            return () => clearTimeout(timer);
        }
    }, [successMessage]);

    useEffect(() => {
        const handleScroll = () => {
            const sidebar = sidebarRef.current;
            if (!sidebar) return;

            const scrollTop = window.scrollY;
            const sidebarTop = sidebar.getBoundingClientRect().top;

            setIsSticky(scrollTop > 150 && sidebarTop < 150);
        };

        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    useEffect(() => {
        const storedData = sessionStorage.getItem('formData');
        if (storedData) {
            setData(JSON.parse(storedData));
        }
    }, []);

    useEffect(() => {
        sessionStorage.setItem('formData', JSON.stringify(data));
    }, [data]);

    return (
        <MainLayout>
            <Head title={'New ' + (type == 1 ? 'Service' : type == 2 ? 'Project' : type == 3 ? 'Install' : '') + ' | Techbook'} />
            <div className='container-fluid total-bg'>
                <div className='bg-white border rounded py-3 px-1 row justify-content-between align-items-center mt-3 mb-3'>
                    <h2 class="fs-4 mb-0 col-md-6">New Work Order ({type == 1 ? 'Service' : type == 2 ? 'Project' : type == 3 ? 'Install' : ''})</h2>

                    <div className='d-flex gap-2 col-md-6 justify-content-end'>
                        <button onClick={(e) => submit(e)} className='btn fw-bold px-5' style={{ backgroundColor: 'rgb(175, 225, 175)' }}>Publish</button>
                    </div>
                </div>

                <div className='row justify-content-between align-items-start mt-3'>
                    <div className={`w-25 ps-0 pe-3 mb-3 sticky-sidebar ${isSticky ? 'sticky' : ''}`} ref={sidebarRef}>
                        <div className='bg-white border rounded'>
                            <ul>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(overviewRef)}>Overview</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(woReqRef)}>Work Request</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(scopeRef)}>Scope Of Work</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(toolRef)}>Tool Required</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(techPartRef)}>Technician Provided Parts</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(shipmentRef)}>Shipments</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(docTechRef)}>Documents For Technicians</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(instructionRef)}>Dispatch Instructions</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(taskRef)}>Tasks</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(contactRef)}>Contacts</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(scheduleRef)}>Schedule</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(locationRef)}>Location</li>
                                <li className='fw-bold py-3 text-center' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(payRef)}>Pay</li>
                            </ul>
                        </div>
                    </div>
                    <div className='w-75 px-0 mb-3'>
                        <Overview data={data} setData={setData} errors={errors} overviewRef={overviewRef} />
                        <WorkRequested data={data} setData={setData} errors={errors} woReqRef={woReqRef} />
                        <ScopeOfWork data={data} setData={setData} scopeRef={scopeRef} />
                        <ToolRequired data={data} setData={setData} toolRef={toolRef} />
                        <TechProvidedPart data={data} setData={setData} techPartRef={techPartRef} />
                        <Shipment data={data} setData={setData} shipmentRef={shipmentRef} />
                        <DocForTech data={data} setData={setData} docTechRef={docTechRef} techDocFiles={techDocFiles} setTechDocFiles={setTechDocFiles} />
                        <DispatchInstruction data={data} setData={setData} instructionRef={instructionRef} />
                        <Task data={data} setData={setData} taskRef={taskRef}/>
                        <Contact data={data} setData={setData} contactRef={contactRef} />
                        <Schedule data={data} setData={setData} scheduleRef={scheduleRef} />
                        <Location data={data} setData={setData} errors={errors} locationRef={locationRef} />
                        <PaySheet data={data} setData={setData} errors={errors} payRef={payRef} />
                    </div>
                </div>
            </div>
            {successMessage && (
                <div className="alert alert-success alert-dismissible fade show position-fixed" style={{ bottom: '50px', right: '50px', height: 'max-content' }} role="alert">
                    <span>{successMessage}</span>
                    <button type="button" className="btn-close" onClick={() => setSuccessMessage(null)} />
                </div >
            )}
            {errorMessage && (
                <div className="alert alert-danger alert-dismissible fade show position-fixed" style={{ bottom: '50px', right: '50px', height: 'max-content' }} role="alert">
                    <span>{errorMessage}</span>
                    <button type="button" className="btn-close" onClick={() => setErrorMessage(null)} />
                </div >
            )
            }
        </MainLayout>
    )
}

export default CreateWorkOrder