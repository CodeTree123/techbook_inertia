import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import OtherExpenses from './OtherExpenses';

const PaySheet = ({ id, details, onSuccessMessage }) => {
    
    const rate = details?.technician?.rate?.['STD'] || 0;
    const totalHours = details?.check_in_out.reduce((sum, item) => {
        const hours = Number(item?.total_hours) || 0; // Default to 0 if total_hours is not a valid number
        return sum + hours;
    }, 0);
    const techPartsTotal = details?.tech_provided_parts?.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0) || 0;

    const otherExpensesTotal = details?.other_expenses?.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0) || 0;

    const totalCost = (rate * totalHours) + techPartsTotal + parseFloat(details.travel_cost ?? 0) + otherExpensesTotal;

    const [editable, setEditable] = useState(false);

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        travel_cost: details.travel_cost || 0.00,
        description: '',
        price: '',
        quantity: 1,
    });

    const updatePaysheet = (e) => {
        e.preventDefault();
        post(route('user.wo.updateTravel', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Pay Sheet Updated');
                setEditable(false);
            }
        });
    }

    // Expense

    const [addExpense, setAddExpense] = useState(false)

    const updateExpense = (e) => {
        e.preventDefault();
        post(route('user.wo.addExpenses', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Other Expense Added');
                setAddExpense(false);
            }
        });
    }

    return (
        <div className="card bg-white shadow border-0 mb-4 action-cards">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Pay Sheet</h3>
            </div>
            <div className="card-body bg-white editableDiv">
                <p className="mb-0">Payment Terms</p>
                <p className="mb-0 mx-5"><b>NET-{details?.technician?.terms ? details?.technician?.terms : ' '}-day terms</b>
                </p>
                <form>
                    <div className="d-flex mt-4 justify-content-between" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Labor</p>
                    </div>
                    <div className="d-flex mt-2 justify-content-between ps-5" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Rate</p>
                        <hr className="w-75" />
                        <p>${rate}
                        </p>
                    </div>
                    <div className="d-flex mt-2 justify-content-between ps-5" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Hour</p>
                        <hr className="w-50" />
                        <p id="totalHours">{totalHours} hours</p>
                    </div>
                    <hr />
                    <div className="d-flex mt-2 justify-content-between ps-5" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Total Labor Cost</p>
                        <hr className="w-50" />
                        <p>${totalHours * rate}
                        </p>
                    </div>
                    <div className="d-flex mt-2 justify-content-between ps-5 position-relative " style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Travel</p>
                        <hr className="w-50" />
                        {
                            !editable ?
                                <p className="">${details.travel_cost || 0.00}</p> :
                                <input name="travel" type="text" className="text-end" style={{ width: 100 }} defaultValue={data.travel_cost} onChange={(e) => setData({ travel_cost: e.target.value })}  onBlur={(e) => updatePaysheet(e)} autoFocus/>
                        }
                        {
                            !editable &&
                            <div className='position-absolute rounded-5 bg-primary text-white justify-content-center align-items-center shadow editablePoint' style={{ width: '20px', height: '20px', cursor: 'pointer', right: '-15px', top: '-10px' }} onClick={() => setEditable(true)}>
                                <i class="fa-solid fa-pencil" style={{ fontSize: '10px' }}></i>
                            </div>
                        }

                    </div>
                    <div className="d-flex mt-2 justify-content-between" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Expenses</p>
                    </div>
                    {
                        details?.tech_provided_parts?.map((part) => (
                            <div className="d-flex mt-2 justify-content-between ps-5" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                                <p>{part.part_name} x {part.quantity}</p>
                                <hr className="w-50" />
                                <p>${part.amount}</p>
                            </div>
                        ))
                    }
                    <div className="d-flex mt-2 justify-content-between" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Others</p>
                    </div>
                    <OtherExpenses id={id} parts={details?.other_expenses} onSuccessMessage={onSuccessMessage}/>
                    
                </form>
                {
                    addExpense &&
                    <div className='row'>
                        <div className='col-4'>
                            <input type="text" className='border-bottom w-100' placeholder='Item Description' onChange={(e) => setData({ ...data, description: e.target.value })} />
                            {errors.description && <p className='text-danger'>{errors.description}</p>}
                        </div>
                        <div className='col-4'>
                            <input type="text" className='border-bottom w-100' placeholder='Item Price' onChange={(e) => setData({ ...data, price: e.target.value })} />
                            {errors.price && <p className='text-danger'>{errors.price}</p>}
                        </div>
                        <div className='col-4'>
                            <input type="text" className='border-bottom w-100' placeholder='Item Quantity' onChange={(e) => setData({ ...data, quantity: e.target.value })} />
                            {errors.quantity && <p className='text-danger'>{errors.quantity}</p>}
                        </div>
                        <div className='col-12 d-flex mt-2 gap-2 justify-content-end'>
                            <button onClick={(e) => updateExpense(e)} className='btn-success btn'>
                                Save
                            </button>
                            <button onClick={() => setAddExpense(false)} className='btn-danger btn'>
                                Cancel
                            </button>
                        </div>
                    </div>
                }
                {
                    !addExpense &&
                    <button className="btn btn-outline-dark my-3" onClick={() => setAddExpense(true)}>+ Add Items</button>
                }

                <div className="d-flex mt-2 justify-content-between" style={{ fontSize: 20 }}>
                    <p className="fw-bold">Total Pay</p>
                    <p id="totalPay" className="fw-bold">
                        ${totalCost.toFixed(2)}
                    </p>
                </div>
            </div>
        </div>

    )
}

export default PaySheet