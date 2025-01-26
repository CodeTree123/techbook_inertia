import { DateTime } from 'luxon';
import React, { useState } from 'react'
import { Modal } from 'react-bootstrap'
import AsyncSelect from 'react-select/async';

const SearchTechnicianModal = () => {
    const [showCustomer, setShowCustomer] = useState(false);

    const handleCloseCustomer = () => setShowCustomer(false);
    const handleShowCustomer = () => {
        setShowCustomer(true)
        setData(null)
    };

    const [selectedTech, setSelectedTech] = useState(null);

    const [techData, setTechData] = useState();

    const handleSelect = async (selectedOption) => {
        setSelectedTech(selectedOption?.value);

        try {
            const response = await fetch(`/api/single-tech/${selectedOption?.value}`);
            const json = await response.json();

            if (json.success && json.data) {
                setTechData(json.data)
            }

            return []; // Return an empty array if no data is available
        } catch (error) {
            console.error('Error fetching employees:', error);
            return [];
        }
    }

    const loadOptions = async (inputValue) => {
        try {
            const response = await fetch(`/api/all-techs?search=${inputValue}`);
            const json = await response.json();

            if (json.success && json.data) {
                return json.data.map(tech => ({
                    value: tech.id,
                    label: tech.company_name,
                }));
            }
            setTechData(null);
            setSelectedTech(null)
            return []; // Return an empty array if no data is available
        } catch (error) {
            console.error('Error fetching customers:', error);
            return [];
        }
    };
    
    return (
        <>
            <li><a href="#" onClick={handleShowCustomer}>Search</a></li>
            <Modal show={showCustomer} onHide={handleCloseCustomer} size="xl">
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Find Technician</h5>
                    <button onClick={() => setShowCustomer(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <AsyncSelect
                        cacheOptions
                        loadOptions={loadOptions}
                        defaultOptions={selectedTech}
                        placeholder="Search By Id, Name Or Zipcode"
                        onChange={(selectedOption) => handleSelect(selectedOption)}
                    />
                    {
                        techData &&

                        <div className='mt-3'>
                            <div className='d-flex'>
                                <div className='w-50 pe-5'>
                                    <h2 className='fs-4'>{techData.technician_id} - {techData.company_name} {'(' + techData.tech_type + ')'} </h2>

                                    <div className='d-flex justify-content-start gap-3 mt-3'>
                                        {
                                            techData.email &&
                                            <a className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }} href={`mailto:${techData.email}`}>
                                                <i class="fa-regular fa-envelope"></i>
                                                {techData.email}
                                            </a>
                                        }

                                        {
                                            techData.phone &&
                                            <a className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }} href={`callto:${techData.phone}`}>
                                                <i class="fa-solid fa-phone"></i>
                                                {techData.phone}
                                            </a>
                                        }
                                    </div>

                                    {
                                        techData.notes &&
                                        <div className='rounded-3 px-2 py-2 mt-4' style={{ backgroundColor: 'rgb(238, 238, 238)' }}>
                                            <span><b>Note: </b> {techData.notes}</span>
                                        </div>
                                    }


                                    <div className='mt-4 pb-2 border-bottom'>
                                        <h4>Attachments</h4>
                                    </div>

                                    <div className='mt-4 '>
                                        {
                                            techData.coi_file &&
                                            <div>
                                                <span style={{ color: 'grey', fontSize: '12px' }}>Coi File, Expiry date: {DateTime.fromISO(techData?.coi_expire_date).toFormat('MM-dd-yy')}</span>
                                                <a className='border p-2 rounded d-flex align-items-center gap-2' href={'/technician/view-pdf/coi/' + techData.id} target='_blank'>
                                                    <i class="fa-solid fa-file-pdf text-danger fs-1"></i>
                                                    <span className='mb-0 fs-6 fw-bold'>{techData.coi_file}</span>
                                                </a>
                                            </div>
                                        }

                                        {
                                            techData.msa_file &&
                                            <div>
                                                <span style={{ color: 'grey', fontSize: '12px' }}>Msa File, Expiry date: {DateTime.fromISO(techData?.msa_expire_date).toFormat('MM-dd-yy')}</span>
                                                <a className='border p-2 rounded d-flex align-items-center gap-2' href={'/technician/view-pdf/msa/' + techData.id} target='_blank'>
                                                    <i class="fa-solid fa-file-pdf text-danger fs-1"></i>
                                                    <span className='mb-0 fs-6 fw-bold'>{techData.msa_file}</span>
                                                </a>
                                            </div>
                                        }

                                        {
                                            techData.nda_file &&
                                            <div>
                                                <span style={{ color: 'grey', fontSize: '12px' }}>Nda File</span>
                                                <a className='border p-2 rounded d-flex align-items-center gap-2' href={'/technician/view-pdf/nda/' + techData.id} target='_blank'>
                                                    <i class="fa-solid fa-file-pdf text-danger fs-1"></i>
                                                    <span className='mb-0 fs-6 fw-bold'>{techData.nda_file}</span>
                                                </a>
                                            </div>
                                        }

                                        {
                                            !techData.coi_file && !techData.coi_file && !techData.coi_file &&
                                            <p>No Attachment found</p>
                                        }
                                    </div>
                                </div>

                                <div className='w-50 ps-5'>
                                    <div className='mt-5'>
                                        <p>Address: <b>{techData.address_data['address']}, {techData.address_data['city']}, {techData.address_data['state']} {techData.address_data['zip_code']}</b></p>
                                        <p>Total Assignments: <b>{techData.wo_ct}</b></p>
                                    </div>

                                    <div className='mt-4 row'>
                                        <h4>Technician Rate Charts</h4>
                                        <div className='col-md-12'>
                                            <table className='table border'>
                                                <tbody>
                                                    <tr>
                                                        <td>Standard rate</td>
                                                        <td>${techData?.rate?.STD ?? 0}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Emergency rate</td>
                                                        <td>${techData?.rate?.EM ?? 0}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OT</td>
                                                        <td>${techData.rate?.OT ?? 0}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>SH</td>
                                                        <td>${techData.rate?.SH ?? 0}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Travel fee</td>
                                                        <td>${techData.travel_fee ?? 0}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                    <div className='mt-4 row'>
                                        <h4>Skillset</h4>

                                        <ul className='d-flex gap-2 flex-wrap'>
                                            {techData?.skills?.map((skill) => (
                                                <li className='d-flex align-items-center gap-2 px-3 py-1 rounded-5' style={{ backgroundColor: 'rgb(238, 238, 238)', width: 'max-content' }}>{skill.skill_name}</li>
                                            ))}
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    }
                </Modal.Body>
                <Modal.Footer>
                    <button onClick={() => setShowCustomer(false)} type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default SearchTechnicianModal