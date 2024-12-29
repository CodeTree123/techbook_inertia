import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Modal } from 'react-bootstrap';
import AsyncSelect from 'react-select/async';

const ImportSite = () => {
    const [showCustomer, setShowCustomer] = useState(false);

    const handleCloseCustomer = () => setShowCustomer(false);
    const handleShowCustomer = () => {
        setShowCustomer(true)
    };


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

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'customer_id': ''
    });

    const submit = (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('site_excel_file', data.site_excel_file);

        post(route('user.site.import'), formData, {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Site Imported Successfully');
                setShowCustomer(false)
            }
        });
    };


    return (
        <>
            <li><a href="#" onClick={handleShowCustomer}>Import Site</a></li>
            <Modal show={showCustomer} onHide={handleCloseCustomer} size="xl">
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Import Site</h5>
                    <button onClick={() => setShowCustomer(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <div className='row'>
                        <div className='col-md-5'>
                            <label htmlFor="" className='form-label fw-bold'>Select Customer</label>
                            <AsyncSelect
                                cacheOptions
                                loadOptions={loadOptions}
                                defaultOptions
                                placeholder="Select customer"
                                onChange={(selectedOption) => setData({ ...data, customer_id: selectedOption?.value })}
                            />
                            {errors.customer_id && <span className="text-danger">{errors.customer_id}</span>}
                        </div>

                        <div className='col-md-5'>
                            <label htmlFor="" className='form-label fw-bold'>Site Id</label>
                            <input type="file" className='form-control' placeholder='Enter the site id' style={{ height: '36px' }} onChange={(e) => setData({ ...data, site_excel_file: e.target.files[0] })}/>
                            {errors.site_excel_file && <span className="text-danger">{errors.site_excel_file}</span>}
                        </div>

                        <div className='col-2 d-flex align-items-end'>
                            <button className='btn btn-primary w-100' onClick={(e)=>submit(e)}>Import</button>
                        </div>

                        <div className='col-12 my-5 d-flex flex-column align-items-center'>
                            <p>Click the below button to download sample site import csv file</p>
                            <a href='/user/download/sample/site/import/excel' className='btn btn-primary'>Download</a>
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

export default ImportSite