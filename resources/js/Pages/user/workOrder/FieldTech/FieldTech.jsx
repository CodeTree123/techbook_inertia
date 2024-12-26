import { useForm } from '@inertiajs/react';
import React, { useEffect, useState } from 'react'
import TechData from './components/TechData';
import { Modal } from 'react-bootstrap';

const FieldTech = ({ id, details, onSuccessMessage, onErrorMessage }) => {

    const [search, setSearch] = useState('');
    const [technicians, setTechnicians] = useState([]);
    const [loading, setLoading] = useState(false);

    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);

    const handleSearch = (e) => {
        setSearch(e.target.value);
        setCurrentPage(1)
    }

    useEffect(() => {
        if (search.trim() === '') {
            setTechnicians([]);
            return;
        }

        const delayDebounceFn = setTimeout(() => {
            setLoading(true);
            fetch(`/api/all-techs?search=${encodeURIComponent(search)}&page=${currentPage}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        setTechnicians(data.data || []);
                        setTotalPages(data.pagination.last_page);
                    }
                })
                .catch((error) => {
                    console.error('Error fetching technicians:', error);
                })
                .finally(() => {
                    setLoading(false);
                });
        }, 500);

        return () => clearTimeout(delayDebounceFn); // Cleanup on re-renders
    }, [search, currentPage]);

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        send_email: false
    });

    const assignTech = (e, techId) => {
        e.preventDefault();
        post(route('user.wo.assignTechToWo', [id, techId]), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Technician Assigned Successfully');
                setShowModal(false);
                setSelectedTechnician(null);
            }
        });
    }


    // google api
    const [responseData, setResponseData] = useState(null);
    const [responseError, setResponseErros] = useState(null);
    const [loaderVisible, setLoaderVisible] = useState(false);


    const closestTech = async (destination) => {
        setLoaderVisible(true);

        try {
            const response = await fetch(`/user/find/tech/for/work/worder`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    destination,
                }),
            });

            if (!response.ok) {
                const errorData = await response.json();
                onErrorMessage(errorData?.errors);
                setLoaderVisible(false);
                return;
            }

            const responseData = await response.json();
            setLoaderVisible(false);
            setResponseData(responseData);
        } catch (error) {
            console.error('Error fetching closest techs:', error);
            setLoaderVisible(false);
        }
    };
    console.log(responseData);

    // Modal

    const [showModal, setShowModal] = useState(false);

    const handleCloseModal = () => {
        setShowModal(false);
        setSelectedTechnician(null);
    };

    const handleShowModal = (technician) => {
        setSelectedTechnician(technician);
        setShowModal(true);
    };

    const [selectedTechnician, setSelectedTechnician] = useState(null);

    const totalhours = details?.check_in_out.reduce((sum, item) => {
        const hours = Number(item?.total_hours) || 0; // Default to 0 if total_hours is not a valid number
        return sum + hours;
    }, 0);


    return (
        <div>
            {
                !details?.ftech_id ?
                    <>
                        <div className='row justify-content-end'>
                            <input type="text" placeholder='Search technician here' className='px-4 py-2 col-3 border border-success rounded-5' onChange={(e) => handleSearch(e)} />
                            <div className='col-2'>
                                <button className='btn w-100 d-flex align-items-center justify-content-center gap-1' style={{ backgroundColor: '#9BCFF5' }} onClick={() => closestTech(details.site.city + ', ' + details.site.state + ', ' + details.site.zipcode)}>
                                    <i className="fa-brands fa-google" style={{ fontSize: 16 }} aria-hidden="true" />
                                    Find Tech
                                </button>
                            </div>
                        </div>
                        <div className='p-5 rounded-3' style={{ backgroundColor: '#F9F9F8' }}>
                            {/* <div className='bg-white px-4 py-4 rounded-4 border mb-3'>
                                <h4>Previous Records</h4>
                                {
                                    details?.tech_remove_reasons?.map((reason)=>(
                                        <p className='text-danger'>{reason.reason}</p>
                                    ))
                                }
                            </div> */}
                            {loading ? (
                                <p>Loading...</p>
                            ) : (
                                <ul>
                                    {technicians?.map((tech) => (
                                        <div key={tech.id} className='bg-white px-4 py-4 rounded-4 border position-relative mb-3'>
                                            <div className='d-flex align-items-center gap-3'>
                                                <div className="bg-primary d-flex justify-content-center align-items-center text-white" style={{ width: 30, height: 30, borderRadius: '50%' }}>{tech.company_name.charAt(0)}</div>
                                                <h3 className='mb-0'>{tech.company_name}</h3>
                                            </div>
                                            <div className='row mt-3'>
                                                <div className='col-8 d-flex justify-content-start gap-2 mx-0'>
                                                    {
                                                        tech.email &&
                                                        <a className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }} href={`mailto:${tech.email}`}>
                                                            <i class="fa-regular fa-envelope"></i>
                                                            {tech.email}
                                                        </a>
                                                    }

                                                    {
                                                        tech.phone &&
                                                        <a className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }} href={`callto:${tech.phone}`}>
                                                            <i class="fa-solid fa-phone"></i>
                                                            {tech.phone}
                                                        </a>
                                                    }

                                                    {
                                                        tech.rate['STD'] &&
                                                        <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                            <b>STD:</b> ${tech.rate['STD']}
                                                        </div>
                                                    }

                                                    {
                                                        tech.rate['EM'] &&
                                                        <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                            <b>EM:</b> ${tech.rate['EM']}
                                                        </div>
                                                    }

                                                    {
                                                        tech.rate['OT'] &&
                                                        <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                            <b>OT:</b> ${tech.rate['OT']}
                                                        </div>
                                                    }

                                                    {
                                                        tech.rate['SH'] &&
                                                        <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                            <b>SH:</b> ${tech.rate['SH']}
                                                        </div>
                                                    }

                                                </div>
                                                <div className='col-4 d-flex gap-2 justify-content-end'>
                                                    {
                                                        !details.ftech_id ?
                                                            <button onClick={() => handleShowModal(tech)} className='btn btn-light border'>Assign</button> :
                                                            <button className='btn btn-light border' disabled>Assigned</button>
                                                    }

                                                    <button className='btn btn-dark'>Contact</button>
                                                </div>

                                            </div>
                                            <div className='position-absolute top-0 end-0 badge text-bg-warning'>{tech.tech_type}</div>
                                        </div>
                                    ))}
                                    <div className="pagination justify-content-end align-items-center gap-1">
                                        <button
                                            disabled={currentPage === 1}
                                            onClick={() => setCurrentPage((prev) => Math.max(prev - 1, 1))}
                                            className='btn btn-outline-primary'
                                        >
                                            Previous
                                        </button>
                                        <span>
                                            Page {currentPage} of {totalPages}
                                        </span>
                                        <button
                                            disabled={currentPage === totalPages}
                                            onClick={() => setCurrentPage((prev) => Math.min(prev + 1, totalPages))}
                                            className='btn btn-outline-primary'
                                        >
                                            Next
                                        </button>
                                    </div>

                                </ul>
                            )}
                            <Modal show={showModal} onHide={handleCloseModal}>
                                <Modal.Header>
                                    <h5 className="modal-title" id="exampleModalLabel">Assign This Technician</h5>
                                    <button onClick={() => setShowModal(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                                </Modal.Header>
                                <Modal.Body>
                                    <h6><b>Company Name :</b> {selectedTechnician?.company_name}</h6>
                                    <h6><b>Technician ID :</b> {selectedTechnician?.technician_id}</h6>
                                    <h6><b>Status :</b> {selectedTechnician?.status}</h6>
                                    <div className="form-check">
                                        <input className="form-check-input" type="checkbox" checked={data.send_email} id="flexCheckChecked" onChange={(e) => setData({ send_email: !data.send_email })} />
                                        <label className="form-check-label" htmlFor="flexCheckChecked">
                                            Send email attached workorder to the tech.
                                        </label>
                                    </div>
                                </Modal.Body>
                                <Modal.Footer>
                                    <button onClick={() => setShowModal(false)} type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button onClick={(e) => assignTech(e, selectedTechnician.id)} type="button" className="btn btn-dark">Assign</button>
                                </Modal.Footer>
                            </Modal>
                            <p className='text-danger'>{responseError?.errors}</p>
                            {
                                loaderVisible ? (
                                    <p>Loading...</p>
                                ) : (
                                    <ul>
                                        {responseData?.technicians?.map((tech) => (
                                            <div key={tech.id} className='bg-white px-4 py-4 rounded-4 border position-relative mb-3'>
                                                <div className='d-flex align-items-center gap-3'>
                                                    <div className="bg-primary d-flex justify-content-center align-items-center text-white" style={{ width: 30, height: 30, borderRadius: '50%' }}>{tech.company_name.charAt(0)}</div>
                                                    <h3 className='mb-0'>{tech.company_name}</h3>
                                                </div>
                                                <div className='row mt-3'>
                                                    <div className='col-8 d-flex justify-content-start gap-2 mx-0'>
                                                        {
                                                            tech.email &&
                                                            <a className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }} href={`mailto:${tech.email}`}>
                                                                <i class="fa-regular fa-envelope"></i>
                                                                {tech.email}
                                                            </a>
                                                        }

                                                        {
                                                            tech.phone &&
                                                            <a className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }} href={`callto:${tech.phone}`}>
                                                                <i class="fa-solid fa-phone"></i>
                                                                {tech.phone}
                                                            </a>
                                                        }

                                                        {
                                                            tech.rate['STD'] &&
                                                            <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <b>STD:</b> ${tech.rate['STD']}
                                                            </div>
                                                        }

                                                        {
                                                            tech.rate['EM'] &&
                                                            <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <b>EM:</b> ${tech.rate['EM']}
                                                            </div>
                                                        }

                                                        {
                                                            tech.rate['OT'] &&
                                                            <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <b>OT:</b> ${tech.rate['OT']}
                                                            </div>
                                                        }

                                                        {
                                                            tech.rate['SH'] &&
                                                            <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <b>SH:</b> ${tech.rate['SH']}
                                                            </div>
                                                        }

                                                    </div>
                                                    <div className='col-4 d-flex gap-2 justify-content-end'>
                                                        {
                                                            !details.ftech_id ?
                                                                <button onClick={(e) => assignTech(e, tech.id)} className='btn btn-light border'>Assign</button> :
                                                                <button className='btn btn-light border' disabled>Assigned</button>
                                                        }

                                                        <button className='btn btn-dark'>Contact</button>
                                                    </div>

                                                </div>
                                                <div className='position-absolute top-0 end-0 badge text-bg-warning'>{tech.tech_type}</div>
                                            </div>
                                        ))}
                                    </ul>
                                )
                            }
                        </div>
                    </> :
                    <>
                        <TechData id={id} techData={details?.technician} onSuccessMessage={onSuccessMessage} totalhours={totalhours} assignedEng={details.assigned_tech} />
                    </>
            }
        </div>
    )
}

export default FieldTech