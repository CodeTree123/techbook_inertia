import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import AsyncSelect from 'react-select/async'

const Overview = ({ data, setData, errors, overviewRef }) => {

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
            return [];
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

            return [];
        } catch (error) {
            console.error('Error fetching employees:', error);
            return [];
        }
    };

    const handlePriorityChange = (e) => {
        setData({ ...data, priority: Number(e.target.value) });
    };

    return (
        <div ref={overviewRef} className="card action-cards bg-white border mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Overview</h3>
            </div>
            <div className="card-body bg-white">
                <div>
                    <h6 style={{ fontWeight: 600 }}>
                        Customer Name :
                    </h6>
                    <AsyncSelect
                        cacheOptions
                        loadOptions={loadOptions}
                        defaultOptions
                        placeholder="Search and select customer"
                        onChange={(selectedOption) => setData({ ...data, cus_id: selectedOption?.value })}
                        className='mb-3'
                    />
                    {errors.cus_id && <p className='text-danger mb-0' style={{ marginTop: '-16px' }}>{errors.cus_id}</p>}

                    <h6 style={{ fontWeight: 600 }}>
                        Priority :
                    </h6>
                    <select className="mb-0 w-100 p-0 mb-3 border p-2 rounded" name="priority" onChange={handlePriorityChange}>
                        <option value={1} selected={data.priority == 1}>P1
                        </option>
                        <option value={2} selected={data.priority == 2}>P2
                        </option>
                        <option value={3} selected={data.priority == 3}>P3
                        </option>
                        <option value={4} selected={data.priority == 4}>P4
                        </option>
                        <option value={5} selected={data.priority == 5}>P5
                        </option>
                    </select>
                    {errors.priority && <p className='text-danger mb-0' style={{ marginTop: '-16px' }}>{errors.priority}</p>}

                    <h6 style={{ fontWeight: 600 }}>
                        Requested By :
                    </h6>
                    <input className="mb-0 border p-2 rounded mb-3 w-100" name="requested_by" type="text" defaultValue={data.requested_by} onChange={(e) => setData({ ...data, requested_by: e.target.value })} />
                    {errors.requested_by && <p className='text-danger mb-0' style={{ marginTop: '-16px' }}>{errors.requested_by}</p>}

                    <h6 style={{ fontWeight: 600 }}>
                        Team :
                    </h6>
                    <input className="mb-0 border p-2 rounded mb-3 w-100" type="text" defaultValue={''} name="team" />

                    <h6 style={{ fontWeight: 600 }}>
                        WO Manager :
                    </h6>
                    <AsyncSelect
                        cacheOptions
                        loadOptions={loadEmployeeOptions}
                        defaultOptions
                        placeholder="Search and select employees"
                        className='mb-3'
                        onChange={(selectedOption) => setData({ ...data, wo_manager: selectedOption?.value })}
                    />
                    {errors.wo_manager && <p className='text-danger mb-0'>{errors.wo_manager}</p>}

                    <h6 style={{ fontWeight: 600 }}>
                        Purchase Order :
                    </h6>
                    <input className="mb-0 border p-2 rounded mb-3 w-100" type="text" defaultValue={data.purchase_order} onChange={(e) => setData({ ...data, purchase_order: e.target.value })} name="purchase_order" />

                    <h6 style={{ fontWeight: 600 }}>
                        Problem Code :
                    </h6>
                    <input className="mb-0 border p-2 rounded mb-3 w-100" type="text" defaultValue={data.problem_code} onChange={(e) => setData({ ...data, problem_code: e.target.value })} name="problem_code" />

                    <h6 style={{ fontWeight: 600 }}>
                        Source :
                    </h6>
                    <input className="mb-0 border p-2 rounded mb-3 w-100" name="source" type="text" defaultValue={data.source} onChange={(e) => setData({ ...data, source: e.target.value })} />
                </div>
            </div>
        </div>
    )
}

export default Overview