import { useForm } from '@inertiajs/react';
import React, { useEffect, useState } from 'react'
import { Modal } from 'react-bootstrap'
import AsyncSelect from 'react-select/async';

const DistanceSearchModal = ({ onSuccessMessage, onErrorMessage }) => {
    const [showCustomer, setShowCustomer] = useState(false);

    const handleCloseCustomer = () => setShowCustomer(false);
    const handleShowCustomer = () => {
        setShowCustomer(true)
    };

    const [autoComplete, setAutoComplete] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [selectedOption, setSelectedOption] = useState(null);
    const [clickCount, setClickCount] = useState(0);

    const [responseData, setResponseData] = useState(null);
    const [responseError, setResponseErrors] = useState(null);
    const [loaderVisible, setLoaderVisible] = useState(false);

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        destination: '',
        latitude: '',
        longitude: '',
        respondedTechnicians: [],
        numberOfTech: 10
    });

    const loadOptions = async (query) => {
        try {
            const response = await fetch("/distance/geocode/autocomplete/search", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ query }),
            });
            const data = await response.json();

            // Check if the data is a valid object
            if (data && data.full_name && data.latitude !== undefined && data.longitude !== undefined) {
                const result = {
                    label: data.full_name,
                    value: data.full_name,
                    lat: data.latitude,
                    lng: data.longitude,
                };
                setAutoComplete([result]); // Save the result for defaultOptions
                return [result]; // Return it as an array for AsyncSelect
            } else {
                console.log("Incomplete or invalid data received from server.");
            }
            return []; // Return an empty array if no valid data is received
        } catch (error) {
            console.error("Error fetching data:", error);
            return [];
        }
    };

    const handleSelect = async (selectedOption) => {
        setSelectedOption(selectedOption);
    };

    const findMore = async (cnt) => {
        setIsLoading(true);
    
        // Use prevCount to calculate both clickCount and radiusValue
        setClickCount(prevCount => {
            const updatedCount = prevCount === 0 ? cnt : prevCount + cnt;
            const radiusValue = updatedCount * 50;
    
            googleSearch(radiusValue);
            return updatedCount;
        });
    };

    const googleSearch = async (radiusValue) => {
        if (selectedOption) {
            const destination = selectedOption.label;
            const latitude = selectedOption.lat;
            const longitude = selectedOption.lng;

            setData((prevData) => ({
                ...prevData,
                radiusValue,
                destination,
                latitude,
                longitude,
            }));

            try {
                setLoaderVisible(true); // Show loader while processing
                const response = await fetch(`/user/find/tech/for/work/worder`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        destination,
                        radiusValue,
                        latitude,
                        longitude,
                    }),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    onErrorMessage(errorData?.errors); // Handle server errors
                    setLoaderVisible(false);
                    return;
                }

                const responseData = await response.json();
                setResponseData(responseData); // Update response state
                sessionStorage.setItem(`workOrder_${id}`, JSON.stringify(responseData)); // Store in sessionStorage
            } catch (error) {
                console.error('Error fetching closest techs:', error);
            } finally {
                setLoaderVisible(false); // Hide loader after fetch
            }
        } else {
            console.error('Selected option is null.');
        }
    }


    // useEffect(() => {
    //     const fetchData = async () => {
    //         setIsLoading(true);
    //         const initialData = await loadOptions('');
    //         setAutoComplete(initialData);
    //         setIsLoading(false);
    //     };
    //     fetchData();
    // }, []);

    return (
        <>
            <li><a href="#" onClick={handleShowCustomer}>Distance Search</a></li>
            <Modal show={showCustomer} onHide={handleCloseCustomer} size="xl">
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Measure Distance Technician</h5>
                    <button onClick={() => setShowCustomer(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <div className='row'>
                        <div className='col-12 mb-4'>
                            <h5>Get Distance of Technician From The Project Site</h5>
                        </div>
                        <div className='col-md-9'>
                            <label htmlFor="" className='form-label fw-bold'>Provide your project site address below :</label>
                            <AsyncSelect
                                cacheOptions
                                loadOptions={loadOptions}
                                defaultOptions={autoComplete}
                                placeholder="Search By Id, Name Or Zipcode"
                                isLoading={isLoading}
                                onChange={(selectedOption) => handleSelect(selectedOption)}
                            />
                        </div>
                        <div className='col-md-3 d-flex flex-column justify-content-end'>
                            <button onClick={() => googleSearch()} className='btn btn-outline-success' style={{ height: '42px' }}>
                                <i class="fa-solid fa-globe me-2"></i>
                                Start Finding
                            </button>
                        </div>

                        <div className='col-12'>
                            {loaderVisible ? 'loading...' :
                                responseData?.technicians?.map((tech) => (
                                    <div className='py-3 border-bottom'>
                                        <h5 className='mb-2'>{tech.company_name} <span className='text-secondary' style={{ fontSize: '14px' }}>({tech.distance} ~ {tech.duration})</span></h5>
                                        <div className='d-flex gap-2 justify-contetn-start align-items-center'>
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
                                    </div>
                                ))
                            }
                            {
                                responseData && <div className='d-flex justify-content-end align-items-center mt-4 gap-2'>
                                    <p className='mb-0'>{responseData.radiusMessage}</p>
                                    <button className='btn btn-outline-primary' disabled={responseData.radiusMessage == 'Showing result for 0-150 miles distance'} onClick={()=>findMore(-1)}>Previous 50 miles</button>

                                    <button className='btn btn-outline-primary' disabled={responseData.radiusMessage == 'Showing result for 450-500 miles distance'} onClick={()=>findMore(1)}>Next 50 miles</button>
                                </div>
                            }
                        </div>
                    </div>
                </Modal.Body>
                <Modal.Footer>
                    <button onClick={() => setShowCustomer(false)} type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default DistanceSearchModal