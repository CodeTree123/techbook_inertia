import React, { useState } from 'react'

const PaySheet = ({ data, setData, payRef }) => {
    const [addExpense, setAddExpense] = useState(false)

    const handleSubmit = (e) => {
        e.preventDefault();

        // Create a new part object
        const newPart = {
            other_description: data.other_description || '',
            other_price: data.other_price || '',
            other_quantity: data.other_quantity || '',
        };

        // Update the techProvidedParts array
        setData({
            ...data,
            otherExpenses: [...data?.otherExpenses, newPart],
            other_description: '',
            other_price: '',
            other_quantity: '',
        });

        // Optionally close the form
        setAddExpense(false);
    };

    const [editable, setEditable] = useState(null);

    const handleEdit = (index) => {
        setEditable(index);

        const currentPart = data.otherExpenses[index];
        setData({
            ...data,
            other_description: currentPart.other_description,
            other_price: currentPart.other_price,
            other_quantity: currentPart.other_quantity,
        });
    }

    const handleCancel = () => {
        setEditable(null);
        setData({
            ...data,
            other_description: '',
            other_price: '',
            other_quantity: '',
        });
    };

    const handleUpdate = (e, partindex) => {
        e.preventDefault();
    
        const updatedParts = data.otherExpenses.map((part, index) =>
            index === partindex
                ? {
                      ...part,
                      other_description: data.other_description || part.other_description,
                      other_price: data.other_price || part.other_price,
                      other_quantity: data.other_quantity || part.other_quantity,
                  }
                : part
        );
    
        // Update the state
        setData({
            ...data,
            otherExpenses: updatedParts,
            other_description: '',
            other_price: '',
            other_quantity: '',
        });
    
        // Exit edit mode
        setEditable(null);
    };

    const deletePart = (e, index) => {
        e.preventDefault();
    
        // Remove the selected part by its index
        const updatedParts = data.otherExpenses.filter((_, i) => i !== index);
    
        // Update the state
        setData({
            ...data,
            otherExpenses: updatedParts,
        });
    
        // Exit edit mode if the deleted row is being edited
        if (editable === index) {
            setEditable(null);
        }
    };

    return (
        <div ref={payRef} className="card bg-white border mb-4 action-cards">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Pay Sheet</h3>
            </div>
            <div className="card-body bg-white editableDiv">
                <form>
                    <div className="d-flex mt-2 justify-content-between position-relative " style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p className='me-3' style={{ width: 'max-content' }}>Travel</p>
                        <hr className="w-100" />

                        <input name="travel" type="text" className="text-end ms-3 border" style={{ width: 100 }} defaultValue={data.travel_cost} onChange={(e) => setData({ ...data, travel_cost: e.target.value })} autoFocus />

                    </div>
                </form>
                {
                    data?.techProvidedParts?.map((part) => (
                        <div className="d-flex mt-2 justify-content-between position-relative " style={{ fontSize: 20, fontWeight: '400 !important' }}>
                            <p className='me-3' style={{ width: 'max-content', whiteSpace: 'nowrap' }}>{part.part_name} (${part.price} x {part.quantity})</p>
                            <hr className="w-100" />

                            <p className='ms-3' style={{ width: 'max-content' }}>${part.price * part.quantity}</p>

                        </div>
                    ))
                }
                {
                    data?.otherExpenses?.map((part, index) => (
                        <div className="d-flex mt-2 justify-content-between position-relative " style={{ fontSize: 20, fontWeight: '400 !important' }}>
                            {
                                editable != index &&
                                <>
                                    <p className='me-3' style={{ width: 'max-content', whiteSpace: 'nowrap' }}>{part.other_description} (${part.other_price} x {part.other_quantity})</p>
                                    <hr className="w-100" />

                                    <p className='ms-3' style={{ width: 'max-content' }}>${part.other_price * part.other_quantity}</p>
                                </>
                            }

                            {
                                editable == index &&

                                <div className='row w-100'>
                                    <div className='col-4'>
                                        <input type="text" className='border-bottom w-100' placeholder='Item Description' defaultValue={part.other_description} onChange={(e) => setData({ ...data, other_description: e.target.value })} />
                                    </div>
                                    <div className='col-4'>
                                        <input type="text" className='border-bottom w-100' placeholder='Item Price' defaultValue={part.other_price} onChange={(e) => setData({ ...data, other_price: e.target.value })} />
                                    </div>
                                    <div className='col-4'>
                                        <input type="text" className='border-bottom w-100' placeholder='Item Quantity' defaultValue={part.other_quantity} onChange={(e) => setData({ ...data, other_quantity: e.target.value })} />
                                    </div>
                                    <div className='col-12 d-flex mt-2 gap-2 justify-content-end'>
                                        <button onClick={(e) => handleUpdate(e, index)} className='btn-success btn'>
                                            Save
                                        </button>
                                        <button onClick={() => handleCancel()} className='btn-danger btn'>
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            }

                            {
                                editable != index &&
                                <button className='position-absolute border-0 rounded-5 bg-primary text-white justify-content-center align-items-center shadow editablePoint' style={{ width: '20px', height: '20px', cursor: 'pointer', right: '10px', top: '-10px' }} onClick={() => handleEdit(index)}>
                                    <i class="fa-solid fa-pencil" style={{ fontSize: '10px' }}></i>
                                </button>
                            }
                            {
                                editable != index &&
                                <button className='position-absolute border-0 rounded-5 bg-danger text-white justify-content-center align-items-center shadow editablePoint' style={{ width: '20px', height: '20px', cursor: 'pointer', right: '-15px', top: '-10px' }} onClick={(e) => deletePart(e, index)}>
                                    <i class="fa-solid fa-trash" style={{ fontSize: '10px' }}></i>
                                </button>
                            }
                        </div>
                    ))
                }
                {
                    addExpense &&
                    <div className='row'>
                        <div className='col-4'>
                            <input type="text" className='border-bottom w-100' placeholder='Item Description' onChange={(e) => setData({ ...data, other_description: e.target.value })} />
                        </div>
                        <div className='col-4'>
                            <input type="text" className='border-bottom w-100' placeholder='Item Price' onChange={(e) => setData({ ...data, other_price: e.target.value })} />
                        </div>
                        <div className='col-4'>
                            <input type="text" className='border-bottom w-100' placeholder='Item Quantity' onChange={(e) => setData({ ...data, other_quantity: e.target.value })} />
                        </div>
                        <div className='col-12 d-flex mt-2 gap-2 justify-content-end'>
                            <button onClick={(e) => handleSubmit(e)} className='btn-success btn'>
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
                        ${(data.travel_cost ? parseFloat(data.travel_cost) : 0) + (data?.techProvidedParts?.reduce((sum, item) => sum + (parseFloat(item.price * item.quantity) || 0), 0) || 0) + (data?.otherExpenses?.reduce((sum, item) => sum + (parseFloat(item.other_price * item.other_quantity) || 0), 0) || 0)}
                    </p>
                </div>
            </div>
        </div>
    )
}

export default PaySheet