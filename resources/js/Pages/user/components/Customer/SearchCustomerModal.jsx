import React, { useState } from 'react'
import { Modal } from 'react-bootstrap';
import AsyncSelect from 'react-select/async';

const SearchCustomerModal = ({ onSuccessMessage }) => {
    const [showCustomer, setShowCustomer] = useState(false);

    const handleCloseCustomer = () => setShowCustomer(false);
    const handleShowCustomer = () => {
        setShowCustomer(true)
        setData(null)
    };

    const [selectedCustomer, setSelectedCustomer] = useState(null);

    const [customerData, setCustomerData] = useState()

    const handleSelect = async (selectedOption) => {
        setSelectedCustomer(selectedOption?.value);

        try {
            const response = await fetch(`/api/single-customer/${selectedOption?.value}`);
            const json = await response.json();

            if (json.success && json.data) {
                setCustomerData(json.data)
            }

            return []; // Return an empty array if no data is available
        } catch (error) {
            console.error('Error fetching employees:', error);
            return [];
        }
    }

    const loadOptions = async (inputValue) => {
        try {
            const response = await fetch(`/api/all-customers?search=${inputValue}`);
            const json = await response.json();

            if (json.success && json.data) {
                return json.data.map(customer => ({
                    value: customer.id,
                    label: customer.company_name,
                }));
            }

            return []; // Return an empty array if no data is available
        } catch (error) {
            console.error('Error fetching customers:', error);
            return [];
        }
    };

    return (
        <>
            <li><a href="#" onClick={handleShowCustomer}>Search Customer</a></li>
            <Modal show={showCustomer} onHide={handleCloseCustomer} size="xl">
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Search Customer</h5>
                    <button onClick={() => setShowCustomer(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <AsyncSelect
                        cacheOptions
                        loadOptions={loadOptions}
                        defaultOptions={selectedCustomer}
                        placeholder="Search and select customers"
                        onChange={(selectedOption) => handleSelect(selectedOption)}
                    />
                    {
                        customerData &&

                        <div className='row mt-3 w-100 mx-auto'>
                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Customer Id:</h6>
                                <h6 className='mb-0'>{customerData?.customer_id}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Company Name:</h6>
                                <h6 className='mb-0'>{customerData?.company_name}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Customer Type:</h6>
                                <h6 className='mb-0'>{customerData?.customer_type}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Address:</h6>
                                <h6 className='mb-0'>{customerData?.address?.address}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Country:</h6>
                                <h6 className='mb-0'>{customerData?.address?.country}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>City:</h6>
                                <h6 className='mb-0'>{customerData?.address?.city}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>State:</h6>
                                <h6 className='mb-0'>{customerData?.address?.state}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Zip Code:</h6>
                                <h6 className='mb-0'>{customerData?.address?.zip_code}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Email:</h6>
                                <h6 className='mb-0'>{customerData?.email}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Phone:</h6>
                                <h6 className='mb-0'>{customerData?.phone}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Standard first hour Rate
                                :</h6>
                                <h6 className='mb-0'>${customerData?.s_rate_f}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Standard additional hour Rate
                                :</h6>
                                <h6 className='mb-0'>${customerData?.s_rate_a}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Emergency first hour Rate
                                :</h6>
                                <h6 className='mb-0'>{customerData?.e_rate_f}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Emergency Additional hour Rate
                                :</h6>
                                <h6 className='mb-0'>${customerData?.e_rate_a}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Weekend Additional hour Rate
                                :</h6>
                                <h6 className='mb-0'>${customerData?.w_rate_a}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Weekend first hour Rate
                                :</h6>
                                <h6 className='mb-0'>{customerData?.w_rate_f}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Sunday and Holiday first hour Rate
                                :</h6>
                                <h6 className='mb-0'>${customerData?.sh_rate_f}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Sunday and Holiday Additional hour Rate
                                :</h6>
                                <h6 className='mb-0'>${customerData?.sh_rate_a}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Travel
                                :</h6>
                                <h6 className='mb-0'>{customerData?.travel}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Billing Term
                                :</h6>
                                <h6 className='mb-0'>{customerData?.billing_term}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Type Of Phone System
                                :</h6>
                                <h6 className='mb-0'>{customerData?.type_phone}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Type Of Wireless
                                :</h6>
                                <h6 className='mb-0'>{customerData?.type_wireless}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Type Of CCTV
                                :</h6>
                                <h6 className='mb-0'>{customerData?.type_cctv}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Type Of POS
                                :</h6>
                                <h6 className='mb-0'>{customerData?.type_pos}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Team
                                :</h6>
                                <h6 className='mb-0'>{customerData?.team}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Sales Person Assigned
                                :</h6>
                                <h6 className='mb-0'>{customerData?.sales_person}</h6>
                            </div>

                            <div className='col-4 d-flex align-items-center gap-2 border py-2'>
                                <h6 className='fw-bold pe-2 mb-0'>Project Manager Assigned
                                :</h6>
                                <h6 className='mb-0'>{customerData?.project_manager}</h6>
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

export default SearchCustomerModal