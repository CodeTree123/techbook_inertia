import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import AsyncSelect from 'react-select/async';

const Overview = ({ id, details, onSuccessMessage, is_cancelled, is_billing }) => {
    const [editable, setEditable] = useState(false);

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

    const loadEmployeeOptions = async (inputValue) => {
        try {
            const response = await fetch(`/api/all-employees?search=${inputValue}`);
            const json = await response.json();

            if (json.success && json.data) {
                return json.data.map(employee => ({
                    value: employee.id,
                    label: employee.name,
                }));
            }

            return []; // Return an empty array if no data is available
        } catch (error) {
            console.error('Error fetching employees:', error);
            return [];
        }
    };

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'cus_id': '',
        'p_o': '',
        'priority': '',
        'requested_by': '',
        'wo_manager': '',
        'source': '',
    });

    const handlePriorityChange = (e) => {
        setData({ ...data, priority: Number(e.target.value) });
    };

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.updateOverview', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Overview Details Updated Successfully');
                setEditable(false);
            }
        });
    };


    return (
        <div className="card action-cards bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Overview</h3>
                <div className="d-flex action-group gap-2">

                    {
                        !editable ?
                            <button type="button" className="btn edit-btn border-0" onClick={() => setEditable(!editable)} disabled={is_cancelled || is_billing}>
                                <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                            </button> :
                            <>
                                <button onClick={submit} type='submit' className="btn btn-success fw-bold">
                                    Save
                                </button>
                                <button type='button' className="btn btn-danger fw-bold" onClick={() => setEditable(!editable)}>
                                    Cancel
                                </button>
                            </>
                    }

                </div>
            </div>
            <div className="card-body bg-white">
                <div>
                    <table>
                        <tbody><tr>
                            <td className='fw-bold'>Customer Name : </td>
                            <td>
                                {
                                    !editable ?
                                        <p className="mb-0">
                                            {details?.company_name}
                                        </p>
                                        :
                                        <AsyncSelect
                                            cacheOptions
                                            loadOptions={loadOptions}
                                            defaultOptions
                                            placeholder="Search and select customer"
                                            onChange={(selectedOption) => setData({ ...data, cus_id: selectedOption?.value })}
                                        />
                                }
                            </td>
                        </tr>
                            <tr>
                                <td className='fw-bold'>Purchase order : </td>
                                <td>
                                    {
                                        !editable ?
                                            <p className="mb-0">{details.p_o}</p> :
                                            <input className="mb-0 p-0" name="p_o" type="text" defaultValue={details.p_o} onChange={(e) => setData({ ...data, p_o: e.target.value })} />
                                    }
                                </td>
                            </tr>
                            <tr>
                                <td className='fw-bold'>Priority : </td>
                                <td>
                                    {
                                        !editable ?
                                            <p className="mb-0">{
                                                details.priority == 1 ? 'P1' :
                                                    details.priority == 2 ? 'P2' :
                                                        details.priority == 3 ? 'P3' :
                                                            details.priority == 4 ? 'P4' :
                                                                details.priority == 5 ? 'P5' : ''
                                            }</p> :
                                            <select className="mb-0 w-100 p-0" name="priority" onChange={handlePriorityChange}>
                                                <option value={1} selected={details.priority == 1}>P1
                                                </option>
                                                <option value={2} selected={details.priority == 2}>P2
                                                </option>
                                                <option value={3} selected={details.priority == 3}>P3
                                                </option>
                                                <option value={4} selected={details.priority == 4}>P4
                                                </option>
                                                <option value={5} selected={details.priority == 5}>P5
                                                </option>
                                            </select>
                                    }
                                </td>
                            </tr>
                            <tr>
                                <td className='fw-bold'>Requested By : </td>
                                <td>
                                    {
                                        !editable ?
                                            <p className="mb-0">{details.requested_by}</p> :
                                            <input className="mb-0 p-0" name="requested_by" type="text" defaultValue={details.requested_by} onChange={(e) => setData({ ...data, requested_by: e.target.value })} />
                                    }


                                </td>
                            </tr>
                            <tr>
                                <td className='fw-bold'>Team : </td>
                                <td>
                                    {
                                        !editable ?
                                            <p className="mb-0"></p> :
                                            <input className="mb-0 p-0" type="text" defaultValue={''} name="team" />
                                    }

                                </td>
                            </tr>
                            <tr>
                                <td className='fw-bold'>WO Manager : </td>
                                <td>
                                    {
                                        !editable ? <p className="mb-0">{details?.employee_name}</p> :
                                            <AsyncSelect
                                                cacheOptions
                                                loadOptions={loadEmployeeOptions}
                                                defaultOptions
                                                placeholder="Search and select employees"
                                                onChange={(selectedOption) => setData({ ...data, wo_manager: selectedOption?.value })}
                                            />
                                    }

                                </td>
                            </tr>
                            <tr>
                                <td className='fw-bold'>Source : </td>
                                <td>
                                    {
                                        !editable ? <p className="mb-0">{details?.source}</p>
                                            :
                                            <input type="text" defaultValue={details.source} className='border-bottom w-100' onChange={(e) => setData({ ...data, source: e.target.value })} />
                                    }
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    )
}

export default Overview