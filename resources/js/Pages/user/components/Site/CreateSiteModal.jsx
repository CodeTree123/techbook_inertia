import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Modal } from 'react-bootstrap';
import AsyncSelect from 'react-select/async';

const CreateSiteModal = ({onSuccessMessage}) => {
    const [showTask, setShowTask] = useState(false);

    const handleCloseHold = () => setShowTask(false);
    const handleShowHold = () => {
        setShowTask(true)
        setData(null)
    };

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'customer_id': '',
        'site_id': '',
        'location': '',
        'address_1': '',
        'city': '',
        'state': '',
        'zipcode': '',
        'time_zone': '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.store.site'), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Site added successfully');
                setShowTask(false)
            }
        });
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

    return (
        <>
            <li><a href="#" onClick={handleShowHold}>New</a></li>
            <Modal show={showTask} onHide={handleCloseHold} size="xl">
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Register Site</h5>
                    <button onClick={() => setShowTask(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <form className='row'>
                        <div className='col-md-4'>
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

                        <div className='col-md-4'>
                            <label htmlFor="" className='form-label fw-bold'>Site Id</label>
                            <input type="text" className='form-control' placeholder='Enter the site id' style={{height: '36px'}} onChange={(e)=>setData({...data, site_id: e.target.value})}/>
                            {errors.site_id && <span className="text-danger">{errors.site_id}</span>}
                        </div>

                        <div className='col-md-4'>
                            <label htmlFor="" className='form-label fw-bold'>Location Name</label>
                            <input type="text" className='form-control' placeholder='Enter the location Name' style={{height: '36px'}} onChange={(e)=>setData({...data, location: e.target.value})}/>
                            {errors.location && <span className="text-danger">{errors.location}</span>}
                        </div>

                        <div className='col-md-4'>
                            <label htmlFor="" className='form-label fw-bold'>State</label>
                            <input type="text" className='form-control' placeholder='Enter state' style={{height: '36px'}} onChange={(e)=>setData({...data, state: e.target.value})} />
                            {errors.state && <span className="text-danger">{errors.state}</span>}
                        </div>

                        <div className='col-md-4'>
                            <label htmlFor="" className='form-label fw-bold'>Address 1</label>
                            <input type="text" className='form-control' placeholder='Enter address 1' style={{height: '36px'}} onChange={(e)=>setData({...data, address_1: e.target.value})} />
                            {errors.address_1 && <span className="text-danger">{errors.address_1}</span>}
                        </div>

                        <div className='col-md-4'>
                            <label htmlFor="" className='form-label fw-bold'>Address 2</label>
                            <input type="text" className='form-control' placeholder='Enter address 2' style={{height: '36px'}} />
                        </div>

                        <div className='col-md-4'>
                            <label htmlFor="" className='form-label fw-bold'>City</label>
                            <input type="text" className='form-control' placeholder='Enter city' style={{height: '36px'}} onChange={(e)=>setData({...data, city: e.target.value})} />
                            {errors.city && <span className="text-danger">{errors.city}</span>}
                        </div>

                        <div className='col-md-4'>
                            <label htmlFor="" className='form-label fw-bold'>Zipcode</label>
                            <input type="text" className='form-control' placeholder='Enter zipcode' style={{height: '36px'}} onChange={(e)=>setData({...data, zipcode: e.target.value})} />
                            {errors.zipcode && <span className="text-danger">{errors.zipcode}</span>}
                        </div>

                        <div className='col-md-4'>
                            <label htmlFor="" className='form-label fw-bold'>Timezone</label>
                            <select name="time_zone" id="time_zone" placeholder="Enter timezone" class="form-control" style={{height: '36px'}} onChange={(e)=>setData({...data, time_zone: e.target.value})}>
                                <option value="">Select Timezone</option>
                                <option value="PT">America/Los_Angeles (PT)</option>
                                <option value="MT">America/Denver (MT)</option>
                                <option value="CT">America/Chicago (CT)</option>
                                <option value="ET">America/New_York (ET)</option>
                                <option value="AKT">America/Anchorage (AKT)</option>
                                <option value="HST">Pacific/Honolulu (HST)</option>
                            </select>
                            {errors.time_zone && <span className="text-danger">{errors.time_zone}</span>}
                        </div>

                        <div className='col-md-12'>
                            <label htmlFor="" className='form-label fw-bold'>Property Description</label>
                            <textarea id="description" cols="15" rows="5" class="form-control" placeholder="Enter property descriptions" name="description" spellcheck="false"></textarea>
                        </div>

                    </form>
                </Modal.Body>
                <Modal.Footer>
                    <button onClick={() => setShowTask(false)} type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button onClick={(e) => submit(e)} type="button" className="btn btn-primary">Submit</button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default CreateSiteModal