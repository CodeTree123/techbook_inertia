import { useForm } from '@inertiajs/react';
import React, { useEffect, useState } from 'react'
import TechData from './components/TechData';
import { Modal } from 'react-bootstrap';
import PreviousTech from './components/PreviousTech';
import ContactedTech from './components/ContactedTech';
import ContactModal from './components/ContactModal';

const FieldTech = ({ id, details, onSuccessMessage, onErrorMessage, is_cancelled, is_billing }) => {

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
        if (details.stage <= 3) {
            post(route('user.wo.assignTechToWo', [id, techId]), {
                preserveScroll: true,
                onSuccess: () => {
                    onSuccessMessage('Technician Assigned Successfully');
                    setShowModal(false);
                    setSelectedTechnician(null);
                }
            });
        } else {
            onErrorMessage('Not Allowed to Assign Technician');
        }

    }


    // google api
    const [oldResponseData, setOldResponseData] = useState([]);
    const [responseData, setResponseData] = useState(null);
    const [responseError, setResponseErros] = useState(null);
    const [loaderVisible, setLoaderVisible] = useState(false);
    const [clickCount, setClickCount] = useState(0);
    const [perPage, setPerPage] = useState(1);

    const handleGooglePagination = (cnt) => {
        setPerPage((prevPerPage) => {
            const newPerPage = prevPerPage + cnt;
            // Call closestTech with the updated perPage value
            closestTech(details.site.city + ', ' + details.site.state + ', ' + details.site.zipcode, clickCount, newPerPage);
            return newPerPage;
        });
    };

    const closestTech = async (destination, cnt, newPerPage) => {
        setLoaderVisible(true);

        setClickCount((prevCount) => {
            const updatedCount = prevCount === 0 ? cnt : prevCount + cnt;
            const radiusValue = updatedCount * 50;

            // Call fetchTechnicians with the updated clickCount and perPage values
            fetchTechnicians(destination, radiusValue, newPerPage);
            return updatedCount;
        });
    };

    const fetchTechnicians = async (destination, radiusValue, page) => {
        const respondedTechnicians = oldResponseData ? oldResponseData.map((technician) => technician.id) : [];

        try {
            const response = await fetch(`/user/find/tech/for/work/worder?page=${page}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    destination,
                    radiusValue,
                    respondedTechnicians,
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
            setOldResponseData([...oldResponseData, ...responseData.technicians]);
            setResponseData(responseData);

            sessionStorage.setItem(`workOrder_${id}`, JSON.stringify(responseData));
        } catch (error) {
            console.error("Error fetching closest techs:", error);
            setLoaderVisible(false);
        }
    };


    useEffect(() => {
        const storedData = sessionStorage.getItem(`workOrder_${id}`);
        if (storedData) {
            setResponseData(JSON.parse(storedData));
        }
    }, [id]);


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

    const [showContactModal, setShowContactModal] = useState(false);

    const handleContactCloseModal = (setData) => {
        setData({ is_contacted: false })
        setShowContactModal(false);
        setSelectedTechnician(null);
    };

    const handleContactShowModal = (technician) => {
        setSelectedTechnician(technician);
        setShowContactModal(true);
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
                            <div className='col-3 d-flex justify-content-end gap-2'>
                                <ContactedTech contacted_techs={details?.contacted_techs} onSuccessMessage={onSuccessMessage} />
                                <PreviousTech reasons={details?.tech_remove_reasons} />
                            </div>
                            <input type="text" placeholder='Search technician here' className='px-4 py-2 col-3 border border-success rounded-5' onChange={(e) => handleSearch(e)} />
                            <div className='col-2'>
                                <button className='btn w-100 d-flex align-items-center justify-content-center gap-1 border-0' disabled={is_cancelled || is_billing} style={{ backgroundColor: '#9BCFF5' }} onClick={() => closestTech(details.site.city + ', ' + details.site.state + ', ' + details.site.zipcode, null, 1)}>
                                    Nearby Techs
                                </button>
                            </div>
                        </div>
                        <div className='p-5 rounded-3' style={{ backgroundColor: '#F9F9F8' }}>
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
                                                <div className='col-8 d-flex justify-content-start flex-wrap gap-2 mx-0'>
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
                                                        tech?.rate?.STD &&
                                                        <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                            <b>STD:</b> ${tech?.rate?.STD}
                                                        </div>
                                                    }

                                                    {
                                                        tech?.rate?.EM &&
                                                        <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                            <b>EM:</b> ${tech?.rate?.EM}
                                                        </div>
                                                    }

                                                    {
                                                        tech?.rate?.OT &&
                                                        <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                            <b>OT:</b> ${tech.rate.OT}
                                                        </div>
                                                    }

                                                    {
                                                        tech?.rate?.SH &&
                                                        <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                            <b>SH:</b> ${tech?.rate?.SH}
                                                        </div>
                                                    }

                                                    {
                                                        tech?.address_data &&
                                                        <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                            <b>Address:</b> {[
                                                                tech?.address_data?.address,
                                                                tech?.address_data?.city,
                                                                tech?.address_data?.state,
                                                                tech?.address_data?.country,
                                                                tech?.address_data?.zip_code
                                                            ]
                                                                .filter(Boolean)
                                                                .join(', ')}
                                                        </div>
                                                    }

                                                </div>
                                                <div className='col-4 d-flex gap-2 justify-content-end'>
                                                    {
                                                        !details.ftech_id ?
                                                            <button onClick={() => handleShowModal(tech)} className='btn btn-light border' disabled={details.stage >= 3 || is_cancelled || is_billing} style={{ height: 'max-content' }}>Assign</button> :
                                                            <button className='btn btn-light border' disabled>Assigned</button>
                                                    }

                                                    {
                                                        tech?.contacted ? <button className='btn btn-success' disabled>Contacted</button> : <button className='btn btn-dark' onClick={() => handleContactShowModal(tech)} disabled={is_cancelled || is_billing} style={{ height: 'max-content' }}>Contact</button>
                                                    }

                                                </div>

                                            </div>
                                            <div className='position-absolute top-0 end-0 badge text-bg-warning'>{tech.tech_type}</div>
                                        </div>
                                    ))}
                                    {
                                        technicians.length != 0 &&
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
                                    }

                                </ul>
                            )}
                            {
                                responseData && <p>{responseData.radiusMessage}</p>
                            }
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
                                                    <h3 className='mb-0'>{tech.company_name} <span className='text-secondary' style={{ fontSize: '14px' }}>({tech.distance} ~ {tech.duration})</span></h3>
                                                </div>
                                                <div className='row mt-3'>
                                                    <div className='col-8 d-flex flex-wrap justify-content-start gap-2 mx-0'>
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
                                                            tech?.rate?.STD &&
                                                            <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <b>STD:</b> ${tech?.rate?.STD}
                                                            </div>
                                                        }

                                                        {
                                                            tech?.rate?.EM &&
                                                            <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <b>EM:</b> ${tech?.rate?.EM}
                                                            </div>
                                                        }

                                                        {
                                                            tech?.rate?.OT &&
                                                            <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <b>OT:</b> ${tech?.rate?.OT}
                                                            </div>
                                                        }

                                                        {
                                                            tech?.rate?.SH &&
                                                            <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <b>SH:</b> ${tech?.rate?.SH}
                                                            </div>
                                                        }

                                                        {
                                                            tech?.address_data &&
                                                            <div className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>
                                                                <b>Address:</b> {[
                                                                    tech?.address_data?.address,
                                                                    tech?.address_data?.city,
                                                                    tech?.address_data?.state,
                                                                    tech?.address_data?.country,
                                                                    tech?.address_data?.zip_code
                                                                ]
                                                                    .filter(Boolean)
                                                                    .join(', ')}

                                                            </div>
                                                        }
                                                    </div>
                                                    <div className='col-4 d-flex gap-2 justify-content-end'>
                                                        {
                                                            !details.ftech_id ?
                                                                <button onClick={() => handleShowModal(tech)} className='btn btn-light border' disabled={details.stage >= 3 || is_cancelled} style={{ height: 'max-content' }}>Assign</button> :
                                                                <button className='btn btn-light border' disabled>Assigned</button>
                                                        }

                                                        {
                                                            tech?.contacted ? <button className='btn btn-success' disabled>Contacted</button> : <button className='btn btn-dark' onClick={() => handleContactShowModal(tech)} disabled={is_cancelled || is_billing} style={{ height: 'max-content' }}>Contact</button>
                                                        }
                                                    </div>

                                                </div>
                                                <div className='position-absolute top-0 end-0 badge text-bg-warning'>{tech.tech_type}</div>
                                            </div>
                                        ))}

                                        {
                                            responseData && responseData?.technicians?.length != 0 &&
                                            <div className="pagination justify-content-end align-items-center gap-1">
                                                {
                                                    responseData && <p className='mb-0'>{responseData.radiusMessage}</p>
                                                }
                                                {
                                                    responseData && <p className='mb-0 text-success'>({responseData.shownTech} from {responseData.techCount} technician found)</p>
                                                }
                                                <button
                                                    onClick={() => handleGooglePagination(-1)}
                                                    className="btn btn-outline-primary"
                                                    disabled={perPage <= 1} // Optional: Disable the button when `perPage` is 1
                                                >
                                                    Previous Page
                                                </button>
                                                <button
                                                    onClick={() => handleGooglePagination(1)}
                                                    className="btn btn-outline-primary"
                                                >
                                                    Next Page
                                                </button>

                                                <button
                                                    disabled={responseData?.radiusMessage === 'Showing result for 0-150 miles distance'}
                                                    onClick={() => closestTech(details.site.city + ', ' + details.site.state + ', ' + details.site.zipcode, -1, perPage)}
                                                    className='btn btn-outline-primary'
                                                >
                                                    Previous 50 miles
                                                </button>
                                                <button
                                                    disabled={responseData?.radiusMessage === 'Showing result for 450-500 miles distance'}
                                                    onClick={() => closestTech(details.site.city + ', ' + details.site.state + ', ' + details.site.zipcode, 1, perPage)}
                                                    className='btn btn-outline-primary'
                                                >
                                                    Next 50 miles
                                                </button>
                                            </div>
                                        }
                                    </ul>
                                )
                            }

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


                            <ContactModal id={id} showContactModal={showContactModal} handleContactCloseModal={handleContactCloseModal} selectedTechnician={selectedTechnician} setShowContactModal={setShowContactModal} onSuccessMessage={onSuccessMessage} />
                        </div>
                    </> :
                    <>
                        <TechData id={id} stage={details.stage} techData={details?.technician} onSuccessMessage={onSuccessMessage} totalhours={totalhours} assignedEng={details.assigned_tech} setTechnicians={setTechnicians} is_cancelled={is_cancelled} is_billing={is_billing} />
                    </>
            }
        </div>
    )
}

export default FieldTech