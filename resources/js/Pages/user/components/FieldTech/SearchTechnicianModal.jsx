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

                        <div className='row mt-3 w-100 mx-auto'>
                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Name:</h6>
                                <h6 className='mb-0'>{techData?.company_name}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Phone:</h6>
                                <a href={`callto:${techData?.phone}`} className='mb-0'>{techData?.phone}</a>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Preference:</h6>
                                <h6 className='mb-0'>{techData?.preference}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Address:</h6>
                                <h6 className='mb-0'>{techData?.address_data?.address}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Country:</h6>
                                <h6 className='mb-0'>{techData?.address_data?.country}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>City:</h6>
                                <h6 className='mb-0'>{techData?.address_data?.city}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>State:</h6>
                                <h6 className='mb-0'>{techData?.address_data?.state}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Zip Code:</h6>
                                <h6 className='mb-0'>{techData?.address_data?.zip_code}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Email:</h6>
                                <a href={`mailto:${techData?.email}`} className='mb-0'>{techData?.email}</a>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Primary Contact Email                                :</h6>
                                <h6 className='mb-0'>{techData?.primary_contact_email}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Primary Contact:
                                </h6>
                                <h6 className='mb-0'>{techData?.primary_contact}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Title:</h6>
                                <h6 className='mb-0'>{techData?.title}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Cell Phone:
                                </h6>
                                <h6 className='mb-0'>{techData?.cell_phone}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Rate:</h6>
                                <h6 className='mb-0'>STD: ${techData?.rate.STD}, EM: ${techData?.rate.EM}, OT: ${techData?.rate.OT}, SH: ${techData?.rate.SH}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Radius:</h6>
                                <h6 className='mb-0'>{techData?.radius}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Travel Fee:
                                </h6>
                                <h6 className='mb-0'>${techData?.travel_fee}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>C WorkOrder Count:</h6>
                                <h6 className='mb-0'>{techData?.c_wo_ct}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>WorkOrder Count:</h6>
                                <h6 className='mb-0'>${techData?.wo_ct}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Status
                                    :</h6>
                                <h6 className='mb-0'>{techData?.status}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>COI Expire Date:
                                </h6>
                                <h6 className='mb-0'>{techData?.coi_expire_date}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>MSA Expire Date:
                                </h6>
                                <h6 className='mb-0'>{techData?.msa_expire_date}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>NDA:</h6>
                                <h6 className='mb-0'>{techData?.nda}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Terms:</h6>
                                <h6 className='mb-0'>{techData?.terms}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>COI File:
                                </h6>
                                <h6 className='mb-0'>{techData?.coi_file}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>MSA File:
                                </h6>
                                <h6 className='mb-0'>{techData?.msa_file}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>NDA File:
                                </h6>
                                <h6 className='mb-0'>{techData?.nda_file}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Source:</h6>
                                <h6 className='mb-0'>{techData?.source}</h6>
                            </div>

                            <div className='col-12 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Notes:</h6>
                                <h6 className='mb-0'>{techData?.notes}</h6>
                            </div>

                            <div className='col-12 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Skillsets:</h6>
                                <h6 className='mb-0'>
                                    {/* {techData?.skills?.map(skill => skill.skill.skill_name).join(', ')} */}
                                </h6>

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