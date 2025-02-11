import { useForm } from '@inertiajs/react';
import Shade from "@/Pages/user/components/Shade";
import React, { useEffect, useState } from 'react'
import { Modal } from 'react-bootstrap';

const CreateTechModal = ({ onSuccessMessage }) => {

    const [skills, setSkills] = useState(null);

    useEffect(() => {
        const fetchSkills = async () => {
            try {
                const response = await fetch('/api/all-skills');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                setSkills(data.data); // Assuming `data.data` contains the skills list
            } catch (error) {
                console.log(error);

            }
        };

        fetchSkills();
    }, []);

    const [showTask, setShowTask] = useState(false);

    const handleCloseHold = () => setShowTask(false);
    const handleShowHold = () => {
        setShowTask(true)
        setData(null)
    };

    const { data, setData, post, errors, reset } = useForm({
        company_name: "",
        address: "",
        city: "",
        state: "",
        zip_code: "",
        email: "",
        rate: "",
        radius: "",
        travel_fee: "",
        terms: "",
        phone: "",
        primary_contact_email: "",
        primary_contact: "",
        country: "",
        title: "",
        cell_phone: "",
        status: "",
        coi_expire_date: "",
        msa_expire_date: "",
        nda: '',
        std_rate: "",
        em_rate: "",
        ot_rate: "",
        sh_rate: "",
        c_wo_ct: "",
        preference: "",
        source: "",
        notes: "",
        skill_id: [],
        skill_name: ''
    });

    const [selectedValues, setSelectedValues] = useState([]);

    const handleCheckboxChange = (event) => {
        const value = event.target.value;
        const isChecked = event.target.checked;

        // Update selected values directly in the state
        setSelectedValues((prevSelectedValues) => {
            const updatedValues = isChecked
                ? [...prevSelectedValues, value]
                : prevSelectedValues.filter((item) => item !== value);

            // Set the form data (this will ensure the selected values are correctly passed to the backend)
            setData({ ...data, skill_id: updatedValues });

            return updatedValues; // Return the updated state
        });
    };

    const submit = (e) => {
        e.preventDefault();

        post(route('user.ftech.new'), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('New Technician Added');
                setShowTask(false)
            },
            onError: (errors) => {
                console.log("Error triggered", errors);  // Log any errors
            }
        });
    };


    const [addSkill, setAddSkill] = useState(false);
    const addSkillForm = (e) => {
        e.preventDefault();
        const fetchSkills = async () => {
            try {
                const response = await fetch('/api/all-skills');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                setSkills(data.data); // Assuming `data.data` contains the skills list
            } catch (error) {
                console.log(error);

            }
        };
        post(route('user.skillsets.new'), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('New Skill Added');
                setAddSkill(false);
                fetchSkills();
            }
        });
    };
    
    return (
        <>
            <li><a href="#" onClick={handleShowHold}>New</a></li>
            <Modal show={showTask} onHide={handleCloseHold} size="xl">
                <Modal.Header>
                    <h5 className="modal-title" id="exampleModalLabel">Register New Technician</h5>
                    <button onClick={() => setShowTask(false)} type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                </Modal.Header>
                <Modal.Body>
                    <div className="row">
                        <div className="form-group col-4">
                            <label>Company Name</label>
                            <input type="text" className="form-control" placeholder="Enter company name" name="company_name" style={{ height: '36px' }} onChange={(e) => setData({ ...data, company_name: e.target.value })} />
                            {errors.company_name && <p className='text-danger'>{errors.company_name}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>C Work Order Count</label>
                            <input type="number" className="form-control" placeholder="Enter value" name="c_wo_ct" style={{ height: '36px' }} onChange={(e) => setData({ ...data, c_wo_ct: e.target.value })} />
                            {errors.c_wo_ct && <p className='text-danger'>{errors.c_wo_ct}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label htmlFor="status">Select Status</label>
                            <select name="status" style={{ height: '36px' }} className="form-control" onChange={(e) => setData({ ...data, status: e.target.value })}>
                                <option value>Select Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Pending">Pending</option>
                            </select>
                            {errors.status && <p className='text-danger'>{errors.status}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Terms</label>
                            <select name="terms" style={{ height: '36px' }} className="form-control" onChange={(e) => setData({ ...data, terms: e.target.value })}>
                                <option value>Select Terms</option>
                                <option value={30}>30</option>
                                <option value={45}>45</option>
                                <option value={60}>60</option>
                                <option value={90}>90</option>
                            </select>
                            {errors.terms && <p className='text-danger'>{errors.terms}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Preferred?</label>
                            <select name="preference" style={{ height: '36px' }} className="form-control" onChange={(e) => setData({ ...data, preference: e.target.value })}>
                                <option value="Yes">Yes</option>
                                <option selected value="No">No</option>
                            </select>
                            {errors.preference && <p className='text-danger'>{errors.preference}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Source</label>
                            <input type="text" style={{ height: '36px' }} className="form-control" placeholder="Enter Sourcee" name="source" onChange={(e) => setData({ ...data, source: e.target.value })} />
                            {errors.source && <p className='text-danger'>{errors.source}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Notes</label>
                            <textarea className="form-control" style={{ height: '36px' }} name="notes" placeholder="Enter notes here" defaultValue={""} onChange={(e) => setData({ ...data, notes: e.target.value })} />
                            {errors.notes && <p className='text-danger'>{errors.notes}</p>}
                        </div>

                        <Shade title="Address" />

                        <div className="form-group col-4">
                            <label>Address</label>
                            <input type="text" className="form-control" placeholder="Enter address" name="address" style={{ height: '36px' }} onChange={(e) => setData({ ...data, address: e.target.value })} />
                            {errors.address && <p className='text-danger'>{errors.address}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>City</label>
                            <input type="text" className="form-control" placeholder="Enter city" name="city" style={{ height: '36px' }} onChange={(e) => setData({ ...data, city: e.target.value })} />
                            {errors.city && <p className='text-danger'>{errors.city}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>State</label>
                            <input type="text" className="form-control" placeholder="Enter state" name="state" style={{ height: '36px' }} onChange={(e) => setData({ ...data, state: e.target.value })} />
                            {errors.state && <p className='text-danger'>{errors.state}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Zipcode</label>
                            <input type="text" className="form-control" placeholder="Enter zipcode" name="zip_code" style={{ height: '36px' }} onChange={(e) => setData({ ...data, zip_code: e.target.value })} />
                            {errors.zip_code && <p className='text-danger'>{errors.zip_code}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Country</label>
                            <input type="text" className="form-control" placeholder="Enter country" name="country" defaultValue="United States" style={{ height: '36px' }} onChange={(e) => setData({ ...data, country: e.target.value })} />
                            {errors.country && <p className='text-danger'>{errors.country}</p>}
                        </div>

                        <Shade title="Contacts" />

                        <div className="form-group col-4">
                            <label>Email</label>
                            <input type="text" className="form-control" placeholder="Enter email" name="email" style={{ height: '36px' }} onChange={(e) => setData({ ...data, email: e.target.value })} />
                            {errors.email && <p className='text-danger'>{errors.email}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Phone</label>
                            <input type="text" className="form-control" placeholder="Enter phone" name="phone" style={{ height: '36px' }} onChange={(e) => setData({ ...data, phone: e.target.value })} />
                            {errors.phone && <p className='text-danger'>{errors.phone}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Primary Contact Name</label>
                            <input type="text" className="form-control" placeholder="Enter primary contact name" name="primary_contact" style={{ height: '36px' }} onChange={(e) => setData({ ...data, primary_contact: e.target.value })} />
                            {errors.primary_contact && <p className='text-danger'>{errors.primary_contact}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Primary Contact's Email</label>
                            <input type="text" className="form-control" placeholder="Enter primary contacts email" name="primary_contact_email" style={{ height: '36px' }} onChange={(e) => setData({ ...data, primary_contact_email: e.target.value })} />
                            {errors.primary_contact_email && <p className='text-danger'>{errors.primary_contact_email}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Title</label>
                            <input type="text" className="form-control" placeholder="Enter title" name="title" style={{ height: '36px' }} onChange={(e) => setData({ ...data, title: e.target.value })} />
                            {errors.title && <p className='text-danger'>{errors.title}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Cell Phone</label>
                            <input type="text" className="form-control" placeholder="Enter cell phone" name="cell_phone" style={{ height: '36px' }} onChange={(e) => setData({ ...data, cell_phone: e.target.value })} />
                            {errors.cell_phone && <p className='text-danger'>{errors.cell_phone}</p>}
                        </div>

                        <Shade title="Rates" />

                        <div className="form-group col-4">
                            <label>Standard Rate</label>
                            <input type="numeric" className="form-control" placeholder="Enter rate" name="std_rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, std_rate: e.target.value })} />
                            {errors.std_rate && <p className='text-danger'>{errors.std_rate}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>EM Rate</label>
                            <input type="numeric" className="form-control" placeholder="Enter rate" name="em_rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, em_rate: e.target.value })} />
                            {errors.em_rate && <p className='text-danger'>{errors.em_rate}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>OT Rate</label>
                            <input type="numeric" className="form-control" placeholder="Enter rate" name="ot_rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, ot_rate: e.target.value })} />
                            {errors.ot_rate && <p className='text-danger'>{errors.ot_rate}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>SH Rate</label>
                            <input type="numeric" className="form-control" placeholder="Enter rate" name="sh_rate" style={{ height: '36px' }} onChange={(e) => setData({ ...data, sh_rate: e.target.value })} />
                            {errors.sh_rate && <p className='text-danger'>{errors.sh_rate}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Radius</label>
                            <input type="numeric" className="form-control" placeholder="Enter radius" name="radius" style={{ height: '36px' }} onChange={(e) => setData({ ...data, radius: e.target.value })} />
                            {errors.radius && <p className='text-danger'>{errors.radius}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>Travel Fee</label>
                            <input type="numeric" className="form-control" placeholder="Enter travel fee" name="travel_fee" style={{ height: '36px' }} onChange={(e) => setData({ ...data, travel_fee: e.target.value })} />
                            {errors.travel_fee && <p className='text-danger'>{errors.travel_fee}</p>}
                        </div>

                        <Shade title="Attachments" />

                        <div className="form-group col-4">
                            <label>COI Expiration Date</label>
                            <input type="date" style={{ height: '36px' }} className="form-control" id="tech_modal_coi_expire_date" placeholder="COI expiration date" autoComplete="off" name="coi_expire_date" onChange={(e) => setData({ ...data, coi_expire_date: e.target.value })} />
                            {errors.coi_expire_date && <p className='text-danger'>{errors.coi_expire_date}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>COI Attachment</label>
                            <input type="file" style={{ height: '36px' }} className="form-control" placeholder="COI attachment" name="coi_file" onChange={(e) => setData({ ...data, coi_file: e.target.files[0] })} />
                            {errors.coi_file && <p className='text-danger'>{errors.coi_file}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>MSA Expiration Date</label>
                            <input type="date" style={{ height: '36px' }} className="form-control" id="tech_modal_msa_expire_date" placeholder="MSA expiration date" autoComplete="off" name="msa_expire_date" onChange={(e) => setData({ ...data, msa_expire_date: e.target.value })} />
                            {errors.msa_expire_date && <p className='text-danger'>{errors.msa_expire_date}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>MSA Attachment</label>
                            <input type="file" style={{ height: '36px' }} className="form-control" placeholder="MSA attachment" name="msa_file" onChange={(e) => setData({ ...data, msa_file: e.target.files[0] })} />
                            {errors.msa_file && <p className='text-danger'>{errors.msa_file}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>NDA</label>
                            <select name="nda" style={{ height: '36px' }} className="form-control" onChange={(e) => setData({ ...data, nda: e.target.value })}>
                                <option value>Select NDA</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            {errors.nda && <p className='text-danger'>{errors.nda}</p>}
                        </div>
                        <div className="form-group col-4">
                            <label>NDA Attachment</label>
                            <input type="file" style={{ height: '36px' }} className="form-control" placeholder="NDA attachment" name="nda_file" onChange={(e) => setData({ ...data, nda_file: e.target.files[0] })} />
                            {errors.nda_file && <p className='text-danger'>{errors.nda_file}</p>}
                        </div>
                        
                        <Shade title="Skill sets" />

                        <div style={{ marginTop: 20 }}>
                            <label>Skill Sets</label>
                        </div>
                        <div className="row mt-3">
                            {skills?.map((skill) => (
                                <div className='col-3' key={skill.id}>
                                    <div className="form-check">
                                        <input
                                            className="form-check-input"
                                            type="checkbox"
                                            value={skill.id} // Use value to bind the skill ID
                                            id={`flexCheckDefault${skill.id}`}
                                            onChange={handleCheckboxChange}
                                        />
                                        <label className="form-check-label" htmlFor={`flexCheckDefault${skill.id}`}>
                                            {skill.skill_name}
                                        </label>
                                    </div>
                                </div>
                            ))}
                        </div>
                        <div className='mt-2'>
                            {
                                addSkill &&
                                <>
                                <div className="input-group mb-3" style={{ width: '300px' }}>
                                    <input type="text" className="form-control" placeholder="Add Skill" aria-label="Recipient's username" aria-describedby="basic-addon2" autoFocus onChange={(e) => setData({ ...data, skill_name: e.target.value })} />
                                    <div className="input-group-append">
                                        <button onClick={(e) => addSkillForm(e)} className="btn btn-primary" type="button">Add</button>
                                    </div>
                                </div>
                                {errors.skill_name && <p className='text-danger'>{errors.skill_name}</p>}
                                </>
                            }


                            <button className='btn btn-primary d-inline' onClick={() => setAddSkill(!addSkill)}>Add Skill</button>

                        </div>

                        <span style={{ color: 'red', fontSize: 14 }} id="skill_id_error" />
                    </div>

                </Modal.Body>
                <Modal.Footer>
                    <button onClick={() => setShowTask(false)} type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button onClick={(e) => submit(e)} type="button" className="btn btn-primary">Submit</button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default CreateTechModal