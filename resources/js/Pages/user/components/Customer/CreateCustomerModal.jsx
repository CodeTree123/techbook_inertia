import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Modal } from 'react-bootstrap';

const CreateCustomerModal = ({ onSuccessMessage }) => {
    const [showModal, setShowModal] = useState(false);

    const handleCloseModal = () => setShowModal(false);
    const handleShowModal = () => {
        setShowModal(true)
        setData(null)
    };

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'company_name': '',
        'address': '',
        'address2': '',
        'country': '',
        'city': '',
        'state': '',
        'zip_code': '',
        'email': '',
        'customer_type': '',
        'phone': '',
        's_rate_a': '',
        's_rate_f': '',
        'e_rate_a': '',
        'e_rate_f': '',
        'w_rate_f': '',
        'w_rate_a': '',
        'sh_rate_a': '',
        'sh_rate_f': '',
        'travel': '',
        'billing_term': '',
        'type_phone': '',
        'type_pos': '',
        'type_wireless': '',
        'type_cctv': '',
        'team': '',
        'sales_person': '',
        'project_manager': '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.customer.reg'), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('New customer added');
                setShowModal(false)
            }
        });
    };


    return (
        <>
            <li><a href="#" onClick={handleShowModal}>New Customer</a></li>
            <Modal show={showModal} onHide={handleCloseModal} size="xl">
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Add New Customer</h5>
                    <button onClick={() => setShowModal(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <div className='row'>
                        <div className="form-group col-4">
                            <label><h6>Company Name</h6></label>
                            <input type="text" className="form-control" placeholder="Enter Company Name" name="company_name" style={{ height: '36px' }} onChange={(e) => setData({ ...data, company_name: e.target.value })} />
                            {errors.company_name && <p className='text-danger'>{errors.company_name}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="customer_type">
                                <h6>Customer Type</h6>
                            </label>
                            <select name="customer_type" className="form-control" style={{ height: '36px' }} onChange={(e) => setData({ ...data, customer_type: e.target.value })}>
                                <option value>Select Customer Type</option>
                                <option value="Customer">Customer</option>
                                <option value="Prospecting">Prospecting</option>
                                <option value="Etc">Etc</option>
                            </select>
                            {errors.customer_type && <p className='text-danger'>{errors.customer_type}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="email">
                                <h6>Email</h6>
                            </label>
                            <input type="text" className="form-control" name="email" placeholder="Enter Email" style={{ height: '36px' }} onChange={(e) => setData({ ...data, email: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_zip_code_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="phone">
                                <h6>Phone</h6>
                            </label>
                            <input type="number" className="form-control" name="phone" placeholder="Enter Phone" style={{ height: '36px' }} onChange={(e) => setData({ ...data, phone: e.target.value })} />
                            {errors.phone && <p className='text-danger'>{errors.phone}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="s_rate">
                                <h6>Standard First Hour Rate</h6>
                            </label>
                            <input type="numeric" className="form-control" name="s_rate_f" placeholder="Standard First Hour Rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, s_rate_f: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_s_rate_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="s_rate">
                                <h6>Standard Additional Hour Rate</h6>
                            </label>
                            <input type="numeric" className="form-control" name="s_rate_a" placeholder="Standard Additional Hour Rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, s_rate_a: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_s_rate_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="e_rate">
                                <h6>Emergency First Hour Rate</h6>
                            </label>
                            <input type="numeric" className="form-control" name="e_rate_f" placeholder="Enter Emergency First Hour Rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, e_rate_f: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_e_rate_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="e_rate">
                                <h6>Emergency Additional Hour Rate</h6>
                            </label>
                            <input type="numeric" className="form-control" name="e_rate_a" placeholder="Enter Emergency Additional Hour Rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, e_rate_a: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_e_rate_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="e_rate">
                                <h6>Weekend First Hour Rate</h6>
                            </label>
                            <input type="numeric" className="form-control" name="w_rate_f" placeholder="Enter Weekend First Hour Rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, w_rate_f: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_e_rate_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="e_rate">
                                <h6>Weekend Additional Hour Rate</h6>
                            </label>
                            <input type="numeric" className="form-control" name="w_rate_a" placeholder="Enter Weekend Additional Hour Rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, w_rate_a: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_e_rate_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="e_rate">
                                <h6>Sunday and Holiday First Hour Rate</h6>
                            </label>
                            <input type="numeric" className="form-control" name="sh_rate_f" placeholder="Enter Sunday And Holiday First Hour Rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, sh_rate_f: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_e_rate_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="e_rate">
                                <h6>Sunday and Holiday Additional Hour Rate</h6>
                            </label>
                            <input type="numeric" className="form-control" name="sh_rate_a" placeholder="Enter Sunday And Holiday Additional Hour Rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, sh_rate_a: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_e_rate_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="travel">
                                <h6>Travel</h6>
                            </label>
                            <input type="number" className="form-control" name="travel" placeholder="Enter Travel" style={{ height: '36px' }} onChange={(e) => setData({ ...data, travel: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_travel_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="billing_term">
                                <h6>Billing Term</h6>
                            </label>
                            <select name="billing_term" className="form-control" style={{ height: '36px' }} onChange={(e) => setData({ ...data, billing_term: e.target.value })}>
                                <option value>Select Billing Term</option>
                                <option value="NET30">NET30</option>
                                <option value="NET45">NET45</option>
                                <option value="Etc">Etc</option>
                            </select>
                            <span style={{ color: 'red', fontSize: 14 }} />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="type_phone">
                                <h6>Type Of Phone System</h6>
                            </label>
                            <input type="text" className="form-control" name="type_phone" placeholder="Type Of Phone System" style={{ height: '36px' }} onChange={(e) => setData({ ...data, type_phone: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_type_phone" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="type_wireless">
                                <h6>Type Of Wireless</h6>
                            </label>
                            <input type="text" className="form-control" name="type_wireless" placeholder="Type Of Wireless" style={{ height: '36px' }} onChange={(e) => setData({ ...data, type_wireless: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_type_wireless" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="type_cctv">
                                <h6>Type Of CCTV</h6>
                            </label>
                            <input type="text" className="form-control" name="type_cctv" placeholder="Type Of CCTV" style={{ height: '36px' }} onChange={(e) => setData({ ...data, type_cctv: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_type_cctv" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="type_pos">
                                <h6>Type Of POS</h6>
                            </label>
                            <input type="text" className="form-control" name="type_pos" placeholder="Type Of POS" style={{ height: '36px' }} onChange={(e) => setData({ ...data, type_pos: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_type_pos" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="team">
                                <h6>Team</h6>
                            </label>
                            <select name="team" className="form-control" style={{ height: '36px' }} onChange={(e) => setData({ ...data, team: e.target.value })}>
                                <option value>Select Team</option>
                                <option value="Blue Team">Blue Team</option>
                                <option value="Red Team">Red Team</option>
                                <option value="Etc">Etc</option>
                            </select>
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_team_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="sales_person">
                                <h6>Sales Person Assigned</h6>
                            </label>
                            <input type="text" className="form-control" name="sales_person" placeholder="Sales Person Assigned" style={{ height: '36px' }} onChange={(e) => setData({ ...data, sales_person: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_sales_person" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="project_manager">
                                <h6>Project Manager Assigned</h6>
                            </label>
                            <input type="text" className="form-control" name="project_manager" placeholder="Project Manager Assign" style={{ height: '36px' }} onChange={(e) => setData({ ...data, project_manager: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="project_manager" />
                        </div>
                        <div className="billing-address my-3" style={{ padding: '10px 20px', backgroundColor: 'black', color: 'white', borderRadius: '12px', fontSize: '18px', display: 'inline-block' }}>
                            Billing Address
                        </div>

                        <div className="form-group col-4">
                            <label htmlFor="address">
                                <h6>Address</h6>
                            </label>
                            <input type="text" className="form-control" name="address" placeholder="Enter Address" style={{ height: '36px' }} onChange={(e) => setData({ ...data, address: e.target.value })} />
                            {errors.address && <p className='text-danger'>{errors.address}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="address2">
                                <h6>Address 2</h6>
                            </label>
                            <input type="text" className="form-control" name="address2" placeholder="Enter Address 2" style={{ height: '36px' }} onChange={(e) => setData({ ...data, address2: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_address2_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="city">
                                <h6>City</h6>
                            </label>
                            <input type="text" className="form-control" name="city" placeholder="Enter City" style={{ height: '36px' }} onChange={(e) => setData({ ...data, city: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_city_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="state">
                                <h6>State</h6>
                            </label>
                            <input id="state" type="text" className="form-control" name="state" placeholder="Enter State" style={{ height: '36px' }} onChange={(e) => setData({ ...data, state: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_state_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="zip_code">
                                <h6>Zip Code</h6>
                            </label>
                            <input type="text" className="form-control" name="zip_code" placeholder="Enter Zipcode" style={{ height: '36px' }} onChange={(e) => setData({ ...data, zip_code: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_zip_code_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="country">
                                <h6>Country</h6>
                            </label>
                            <input type="text" className="form-control" name="country" placeholder="Enter Country" defaultValue="United States" style={{ height: '36px' }} onChange={(e) => setData({ ...data, country: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_country_error" />
                        </div>
                        <div className="billing-address my-3" style={{ padding: '10px 20px', backgroundColor: 'black', color: 'white', borderRadius: '12px', fontSize: '18px', display: 'inline-block' }}>
                            Head Quater Address
                        </div>

                        <div className="form-group col-4">
                            <label htmlFor="address">
                                <h6>Address</h6>
                            </label>
                            <input type="text" className="form-control" name="h_address" placeholder="Enter Address" style={{ height: '36px' }} onChange={(e) => setData({ ...data, h_address: e.target.value })} />
                            {errors.h_address && <p className='text-danger'>{errors.h_address}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="address2">
                                <h6>Address 2</h6>
                            </label>
                            <input type="text" className="form-control" name="h_address2" placeholder="Enter Address 2" style={{ height: '36px' }} onChange={(e) => setData({ ...data, h_address2: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_address2_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="city">
                                <h6>City</h6>
                            </label>
                            <input type="text" className="form-control" name="city" placeholder="Enter City" style={{ height: '36px' }} onChange={(e) => setData({ ...data, h_city: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_city_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="state">
                                <h6>State</h6>
                            </label>
                            <input id="state" type="text" className="form-control" name="h_state" placeholder="Enter State" style={{ height: '36px' }} onChange={(e) => setData({ ...data, h_state: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_state_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="zip_code">
                                <h6>Zip Code</h6>
                            </label>
                            <input type="text" className="form-control" name="h_zip_code" placeholder="Enter Zipcode" style={{ height: '36px' }} onChange={(e) => setData({ ...data, h_zip_code: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_zip_code_error" />
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="country">
                                <h6>Country</h6>
                            </label>
                            <input type="text" className="form-control" name="h_country" placeholder="Enter Country" defaultValue="United States" style={{ height: '36px' }} onChange={(e) => setData({ ...data, h_country: e.target.value })} />
                            <span style={{ color: 'red', fontSize: 14 }} id="cus_country_error" />
                        </div>
                    </div>

                </Modal.Body>
                <Modal.Footer>
                    <button onClick={() => setShowModal(false)} type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button onClick={(e) => submit(e)} type="button" className="btn btn-primary">Submit</button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default CreateCustomerModal