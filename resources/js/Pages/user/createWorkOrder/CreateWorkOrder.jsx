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

const CreateWorkOrder = ({ type }) => {

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'cus_id': '',
        'priority': '',
        'requested_by': '',
        'wo_manager': '',
        'order_type': type,
        'scope_work': '',
        'r_tools': '',
        'site_id': '',
        'techProvidedParts': [],
        'shipments': [],
        'instruction': '',
    });

    console.log(data);
    const [isSticky, setIsSticky] = useState(false);
    const sidebarRef = useRef(null);

    const overviewRef = useRef(null);
    const scopeRef = useRef(null);
    const toolRef = useRef(null);
    const techPartRef = useRef(null);
    const shipmentRef = useRef(null);
    const instructionRef = useRef(null);
    const contactRef = useRef(null);
    const locationRef = useRef(null);

    const scrollToSection = (ref) => {
        ref.current.scrollIntoView({ behavior: "smooth" });
    };

    const [successMessage, setSuccessMessage] = useState('');
    const [errorMessage, setErrorMessage] = useState('');

    const submit = (e) => {
        e.preventDefault();

        post(route('user.work.order.store'), {
            preserveScroll: true,
            onSuccess: () => {
                setSuccessMessage('New Work Order Created');
            }
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
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(scopeRef)}>Scope Of Work</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(toolRef)}>Tool Required</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(techPartRef)}>Technician Provided Parts</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(shipmentRef)}>Shipments</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }}>Documents For Technicians</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(instructionRef)}>Dispatch Instructions</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }}>Tasks</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }}>Deliverables</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }}>Contacts</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }}>Schedule</li>
                                <li className='fw-bold py-3 text-center border-bottom' style={{ cursor: 'pointer' }} onClick={() => scrollToSection(locationRef)}>Location</li>
                                <li className='fw-bold py-3 text-center' style={{ cursor: 'pointer' }}>Pay</li>
                            </ul>
                        </div>
                    </div>
                    <div className='w-75 px-0 mb-3'>
                        <Overview data={data} setData={setData} errors={errors} overviewRef={overviewRef} />
                        <ScopeOfWork data={data} setData={setData} scopeRef={scopeRef} />
                        <ToolRequired data={data} setData={setData} toolRef={toolRef} />
                        <TechProvidedPart data={data} setData={setData} techPartRef={techPartRef} />
                        <Shipment data={data} setData={setData} shipmentRef={shipmentRef} />
                        <DispatchInstruction data={data} setData={setData} instructionRef={instructionRef} />
                        <Location data={data} setData={setData} errors={errors} locationRef={locationRef} />
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