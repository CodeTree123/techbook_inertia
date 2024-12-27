import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Modal } from 'react-bootstrap';
import AsyncSelect from 'react-select/async';

const SearchSiteModal = ({ onSuccessMessage }) => {
    const [showTask, setShowTask] = useState(false);

    const handleCloseHold = () => setShowTask(false);
    const handleShowHold = () => {
        setShowTask(true)
        setData(null)
    };

    const [selectedSite, setSelectedSite] = useState(null);

    const [siteData, setSiteData] = useState()

    const handleSelect = async (selectedOption) => {
        setSelectedSite(selectedOption?.value);

        try {
            const response = await fetch(`/api/single-site/${selectedOption?.value}`);
            const json = await response.json();

            if (json.success && json.data) {
                setSiteData(json.data)
            }

            return []; // Return an empty array if no data is available
        } catch (error) {
            console.error('Error fetching employees:', error);
            return [];
        }
    }
    
    

    const loadSiteOptions = async (inputValue) => {
        try {
            const response = await fetch(`/api/all-sites?search=${inputValue}`);
            const json = await response.json();

            if (json.success && json.data) {
                return json.data.map(employee => ({
                    value: employee.id,
                    label: employee.site_id + ' ' + employee.location,
                }));
            }

            return []; // Return an empty array if no data is available
        } catch (error) {
            console.error('Error fetching employees:', error);
            return [];
        }
    };
    return (
        <>
            <li><a href="#" onClick={handleShowHold}>Search</a></li>
            <Modal show={showTask} onHide={handleCloseHold} size="xl">
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Search Site</h5>
                    <button onClick={() => setShowTask(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <AsyncSelect
                        cacheOptions
                        loadOptions={loadSiteOptions}
                        defaultOptions={selectedSite}
                        placeholder="Search and select sites"
                        onChange={(selectedOption) => handleSelect(selectedOption)}
                    />
                    {
                        siteData &&

                        <div>
                            <table>
                                <tr>
                                    <td className='fw-bold pe-2'>Company/Customer:</td>
                                    <td>{siteData?.customer?.company_name}</td>
                                </tr>
                                <tr>
                                    <td className='fw-bold pe-2'>Address:</td>
                                    <td>{siteData.address_1}</td>
                                </tr>
                                <tr>
                                    <td className='fw-bold pe-2'>City:</td>
                                    <td>{siteData.city}</td>
                                </tr>
                                <tr>
                                    <td className='fw-bold pe-2'>State:</td>
                                    <td>{siteData.state}</td>
                                </tr>
                                <tr>
                                    <td className='fw-bold pe-2'>Zipcode:</td>
                                    <td>{siteData.zipcode}</td>
                                </tr>
                                <tr>
                                    <td className='fw-bold pe-2'>Timezone:</td>
                                    <td>{siteData.time_zone}</td>
                                </tr>
                            </table>
                        </div>
                    }
                </Modal.Body>
                <Modal.Footer>
                    <button onClick={() => setShowTask(false)} type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default SearchSiteModal