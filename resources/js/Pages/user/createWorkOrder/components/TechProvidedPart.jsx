import React, { useState } from 'react'

const TechProvidedPart = ({ data, setData, techPartRef }) => {
    const [newItem, setNewItem] = useState(false);
    const handleNewItem = (e) => {
        e.preventDefault();
        setNewItem(true);
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        // Create a new part object
        const newPart = {
            part_name: data.part_name || '',
            parts_number: data.parts_number || '',
            quantity: data.quantity || '',
            price: data.price || '',
        };

        // Update the techProvidedParts array
        setData({
            ...data,
            techProvidedParts: [...data.techProvidedParts, newPart],
            part_name: '',
            parts_number: '',
            quantity: '',
            price: '',
        });

        // Optionally close the form
        setNewItem(false);
    };

    // Editing
    const [editingRow, setEditingRow] = useState(null);

    const handleEdit = (index) => {
        setEditingRow(index);
    
        // Pre-fill the editing fields with current values
        const currentPart = data.techProvidedParts[index];
        setData({
            ...data,
            part_name: currentPart.part_name,
            parts_number: currentPart.parts_number,
            price: currentPart.price,
            quantity: currentPart.quantity,
        });
    };
    

    const handleCancel = () => {
        setEditingRow(null);
        setData({
            ...data,
            part_name: '',
            parts_number: '',
            price: '',
            quantity: '',
        });
    };
    

    const handleUpdate = (e, partindex) => {
        e.preventDefault();
    
        const updatedParts = data.techProvidedParts.map((part, index) =>
            index === partindex
                ? {
                      ...part,
                      part_name: data.part_name || part.part_name,
                      parts_number: data.parts_number || part.parts_number,
                      price: data.price || part.price,
                      quantity: data.quantity || part.quantity,
                  }
                : part
        );
    
        // Update the state
        setData({
            ...data,
            techProvidedParts: updatedParts,
            part_name: '',
            parts_number: '',
            price: '',
            quantity: '',
        });
    
        // Exit edit mode
        setEditingRow(null);
    };

    const deletePart = (e, index) => {
        e.preventDefault();
    
        // Remove the selected part by its index
        const updatedParts = data.techProvidedParts.filter((_, i) => i !== index);
    
        // Update the state
        setData({
            ...data,
            techProvidedParts: updatedParts,
        });
    
        // Exit edit mode if the deleted row is being edited
        if (editingRow === index) {
            setEditingRow(null);
        }
    };
    


    return (
        <div ref={techPartRef} className="card bg-white border mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Technician Provided Parts </h3>
            </div>
            <div className="card-body bg-white">
                <div className="row border-top border-bottom">
                    <div className="col-5 border-end">
                        <h6 style={{ fontSize: '12px' }}>Parts Description</h6>
                    </div>
                    <div className="col-2 border-end">
                        <h6 style={{ fontSize: '12px' }}>Parts Number</h6>
                    </div>
                    <div className="col-1 border-end">
                        <h6 style={{ fontSize: '12px' }}>Price</h6>
                    </div>
                    <div className="col-1 border-end">
                        <h6 style={{ fontSize: '12px' }}>Quantity</h6>
                    </div>
                    <div className="col-1 border-end">
                        <h6 style={{ fontSize: '12px' }}>Amount</h6>
                    </div>
                    <div className="col-2" style={{ fontSize: '12px' }}>
                        Actions
                    </div>
                </div>
                {
                    data?.techProvidedParts?.map((part, index) => (
                        <form onSubmit={(e) => handleUpdate(e, index)} className="row border-bottom">
                            <div className="col-5 border-end">
                                {
                                    editingRow != index ?
                                        <p className="mb-0 fw-bold" style={{ display: 'block', fontSize: '12px' }}>{part.part_name} </p> :
                                        <input className="mb-0 fw-bold p-0 w-100" style={{ fontSize: '12px' }} onChange={(e) => setData({ ...data, part_name: e.target.value })} name="part_name" type="text" defaultValue={part.part_name} />
                                }


                            </div>
                            <div className="col-2 border-end">
                                {
                                    editingRow != index ?
                                        <p className="mb-0 fw-bold" style={{ display: 'block', fontSize: '12px' }}>{part.parts_number}</p> :
                                        <input className="mb-0 fw-bold p-0 w-100" style={{ fontSize: '12px' }} name="parts_number" onChange={(e) => setData({ ...data, parts_number: e.target.value })} type="text" defaultValue={part.parts_number} />}
                            </div>
                            <div className="col-1 border-end">
                                {
                                    editingRow != index ?
                                        <p className="mb-0 fw-bold" style={{ display: 'block', fontSize: '12px' }}>${part.price}</p> :
                                        <input className="mb-0 fw-bold p-0 w-100" style={{ fontSize: '12px' }} onChange={(e) => setData({ ...data, price: e.target.value })} name="price" type="text" defaultValue={part.price} />}
                            </div>
                            <div className="col-1 border-end">
                                {
                                    editingRow != index ?
                                        <p className="mb-0 fw-bold" style={{ display: 'block', fontSize: '12px' }}>{part.quantity}</p> :
                                        <input className="mb-0 fw-bold p-0 w-100" style={{ fontSize: '12px' }} onChange={(e) => setData({ ...data, quantity: e.target.value })} name="quantity" type="text" defaultValue={part.quantity} />}
                            </div>
                            <div className="col-1 border-end">
                                <p className="mb-0 fw-bold" style={{ fontSize: '12px' }}>${part.quantity * part.price}</p>
                            </div>
                            <div className="col-2 d-flex action-group">
                                {
                                    editingRow != index &&
                                    <button type="button" className="btn" onClick={() => handleEdit(index)} style={{ display: 'block' }}>
                                        <i className="fa-solid fa-pen-to-square text-primary" aria-hidden="true" />
                                    </button>
                                }
                                {
                                    editingRow == index &&
                                    <button type='submit' className="btn fw-bold">
                                        <i className="fa-regular fa-floppy-disk text-success" aria-hidden="true" />
                                    </button>
                                }
                                {
                                    editingRow == index &&
                                    <button type="button" onClick={handleCancel} className="btn fw-bold">
                                        <i className="fa-solid fa-ban text-danger" aria-hidden="true" />
                                    </button>
                                }
                                {
                                    editingRow != index &&
                                    <button onClick={(e) => deletePart(e, index)} type="button" className="btn" style={{ height: 'max-content' }}>
                                        <i className="fa-solid fa-trash text-danger" aria-hidden="true" />
                                    </button>
                                }
                            </div>
                        </form>
                    ))
                }
                {
                    newItem &&
                    <form onSubmit={handleSubmit} className="row border-bottom">
                        <div className="col-5 border-end">
                            <input
                                className="mb-0 fw-bold p-0 w-100 border-bottom-0"
                                name="part_name"
                                placeholder="Parts Name"
                                type="text"
                                style={{ fontSize: '12px' }}
                                value={data.part_name || ''}
                                onChange={(e) => setData({ ...data, part_name: e.target.value })}
                            />
                        </div>
                        <div className="col-2 border-end">
                            <input
                                className="mb-0 fw-bold p-0 w-100 border-bottom-0"
                                name="parts_number"
                                placeholder="Parts Number"
                                type="text"
                                style={{ fontSize: '12px' }}
                                value={data.parts_number || ''}
                                onChange={(e) => setData({ ...data, parts_number: e.target.value })}
                            />
                        </div>
                        <div className="col-1 border-end">
                            <input
                                className="mb-0 fw-bold p-0 w-100 border-bottom-0"
                                name="price"
                                placeholder="Price"
                                type="text"
                                style={{ fontSize: '12px' }}
                                value={data.price || ''}
                                onChange={(e) => setData({ ...data, price: e.target.value })}
                            />
                        </div>
                        <div className="col-1 border-end">
                            <input
                                className="mb-0 fw-bold p-0 w-100 border-bottom-0"
                                name="quantity"
                                placeholder="Quantity"
                                type="text"
                                style={{ fontSize: '12px' }}
                                value={data.quantity || ''}
                                onChange={(e) => setData({ ...data, quantity: e.target.value })}
                            />
                        </div>
                        <div className="col-1 border-end" id="totalDisplay" style={{ fontSize: '12px' }}>
                            ${(data.price * data.quantity).toFixed(2) || '0.00'}
                        </div>
                        <div className="col-2 d-flex align-items-center action-group">
                            <button
                                style={{ height: 'max-content' }}
                                type="submit"
                                className="btn fw-bold"
                            >
                                <i className="fa-regular fa-floppy-disk text-success" aria-hidden="true" />
                            </button>
                            <button
                                type="button"
                                className="btn fw-bold"
                                onClick={() => setNewItem(false)}
                                style={{ height: 'max-content' }}
                            >
                                <i className="fa-solid fa-ban text-danger" aria-hidden="true" />
                            </button>
                        </div>
                    </form>
                }
                {
                    !newItem &&
                    <div className="mt-3">
                        <a className="btn btn-outline-dark" onClick={handleNewItem}>+ Add Item</a>
                    </div>
                }


            </div>
        </div>
    )
}

export default TechProvidedPart