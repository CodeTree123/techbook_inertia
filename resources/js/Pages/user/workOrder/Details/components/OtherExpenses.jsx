import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'

const OtherExpenses = ({ id, parts, onSuccessMessage }) => {

    const [editable, setEditable] = useState(null);

    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({
        description: '',
        price: '',
        quantity: ''
    });

    const handleEdit = (expenseId) => {
        setEditable(expenseId);
        setData(null)
    }

    const updateExpense = (e, expenseId) => {
        e.preventDefault();
        post(route('user.wo.updateExpenses', expenseId), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Expense Updated Successfully');
                setEditable(null)
            }
        });
    }

    const deleteExpense = (e, expenseId) => {
        e.preventDefault();
        deleteItem(route('user.wo.deleteExpense', expenseId), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Expense Deleted Successfully');
                setEditable(null)
            }
        });
    }

    return (
        <>
            {
                parts?.map((part) => (
                    <div className="d-flex mt-2 justify-content-between ps-5 position-relative" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        {
                            editable != part.id ?
                                <>
                                    <p>{part.description} (${part.price}) x {part.quantity}</p>
                                    <hr className="w-auto" />
                                    <p>${part.amount}</p>
                                </> :
                                <div className='row mb-3'>
                                    <div className='col-4'>
                                        <input type="text" className='border-bottom w-100' placeholder='Item Description' defaultValue={part.description} onChange={(e) => setData({ ...data, description: e.target.value })} />
                                    </div>
                                    <div className='col-4'>
                                        <input type="text" className='border-bottom w-100' placeholder='Item Price' defaultValue={part.price} onChange={(e) => setData({ ...data, price: e.target.value })} />
                                    </div>
                                    <div className='col-4'>
                                        <input type="text" className='border-bottom w-100' placeholder='Item Quantity' defaultValue={part.quantity} onChange={(e) => setData({ ...data, quantity: e.target.value })} />
                                    </div>
                                    <div className='col-12 d-flex mt-2 gap-2 justify-content-end'>
                                        <button onClick={(e) => updateExpense(e, part.id)} className='btn-success btn'>
                                            Save
                                        </button>
                                        <button onClick={() => setEditable(null)} className='btn-danger btn'>
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                        }

                        {
                            editable != part.id &&
                            <div className='position-absolute rounded-5 bg-primary text-white justify-content-center align-items-center shadow editablePoint' style={{ width: '20px', height: '20px', cursor: 'pointer', right: '10px', top: '-10px' }} onClick={() => handleEdit(part.id)}>
                                <i class="fa-solid fa-pencil" style={{ fontSize: '10px' }}></i>
                            </div>
                        }
                        {
                            editable != part.id &&
                            <div className='position-absolute rounded-5 bg-danger text-white justify-content-center align-items-center shadow editablePoint' style={{ width: '20px', height: '20px', cursor: 'pointer', right: '-15px', top: '-10px' }} onClick={(e) => deleteExpense(e, part.id)}>
                                <i class="fa-solid fa-trash" style={{ fontSize: '10px' }}></i>
                            </div>
                        }
                    </div>
                ))
            }
        </>
    )
}

export default OtherExpenses