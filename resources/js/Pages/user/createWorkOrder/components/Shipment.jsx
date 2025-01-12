import React, { useState } from 'react'

const Shipment = ({ data, setData, shipmentRef }) => {
    const [addShipment, setAddShipment] = useState(false);

    const handleSubmit = (e) => {
        e.preventDefault();

        const newShipment = {
            associate: data.associate || 'fedex',
            tracking_number: data.tracking_number || '',
            shipment_from: data.shipment_from || '',
            shipment_to: data.shipment_to || '',
            created_at: data.created_at || '',
        };

        setData({
            ...data,
            shipments: [...data.shipments, newShipment],
            associate: '',
            tracking_number: '',
            shipment_from: '',
            shipment_to: '',
            created_at: '',
        });

        setAddShipment(false);
    };

    // Editing

    const [editingRow, setEditingRow] = useState(null);

    const handleEdit = (index) => {
        setEditingRow(index);

        const currentShipment = data.shipments[index];
        setData({
            ...data,
            associate: currentShipment.associate,
            tracking_number: currentShipment.tracking_number,
            shipment_from: currentShipment.shipment_from,
            shipment_to: currentShipment.shipment_to,
            created_at: currentShipment.created_at,
        });
    };

    const handleCancel = () => {
        setEditingRow(null);
        setData({
            ...data,
            associate: '',
            tracking_number: '',
            shipment_from: '',
            shipment_to: '',
            created_at: '',
        });
    };

    const formatDate = (dateString) => {
        if (!dateString) return ''; // If no date, return an empty string
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
        const day = String(date.getDate()).padStart(2, '0'); // Pad single digits
        return `${year}-${month}-${day}`;
    };

    const handleUpdate = (e, shipmentindex) => {
        e.preventDefault();
        
        const updatedShipment = data.shipments.map((ship, index) =>
            index === shipmentindex
                ? {
                      ...ship,
                      associate: data.associate || ship.associate,
                      tracking_number: data.tracking_number || ship.tracking_number,
                      shipment_from: data.shipment_from || ship.shipment_from,
                      shipment_to: data.shipment_to || ship.shipment_to,
                      created_at: data.created_at || ship.created_at,
                  }
                : ship
        );
    
        // Update the state
        setData({
            ...data,
            shipments: updatedShipment,
            associate: '',
            tracking_number: '',
            shipment_from: '',
            shipment_to: '',
            created_at: '',
        });
    
        // Exit edit mode
        setEditingRow(null);
    };

    const deleteShipment = (e, index) => {
        e.preventDefault();
    
        // Remove the selected part by its index
        const updatedShipment = data.shipments.filter((_, i) => i !== index);
    
        // Update the state
        setData({
            ...data,
            shipments: updatedShipment,
        });
    
        // Exit edit mode if the deleted row is being edited
        if (editingRow === index) {
            setEditingRow(null);
        }
    };

    return (
        <div ref={shipmentRef} className="card bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Shipments</h3>
            </div>
            <div className="card-body bg-white">
                {
                    data?.shipments?.map((shipment, index) => (
                        <form onSubmit={(e) => handleUpdate(e, index)} className="p-3 mb-3 action-cards d-flex justify-content-between" style={{ backgroundColor: '#E3F2FD' }}>
                            <div>
                                {
                                    editingRow != index &&
                                    <>
                                        <h5 className="nrml-txt">
                                            {
                                                shipment.associate == 'fedex' ?
                                                    <a target="_blank" href={`https://www.fedex.com/fedextrack/?trknbr=${shipment.tracking_number}`}>{shipment.tracking_number}</a> :
                                                    <a target="_blank" href={`https://www.ups.com/track?loc=en_US&tracknum=${shipment.tracking_number}`}>{shipment.tracking_number}</a>
                                            }

                                        </h5>
                                        <i className="nrml-txt" style={{ fontSize: 12 }}>
                                            from:  <span>{shipment.shipment_from}</span> --- <span><i className="fa-solid fa-truck-fast" aria-hidden="true" /></span>--- to:
                                            <span>{shipment.shipment_to}</span>
                                        </i>
                                        <br />
                                        <i className="nrml-txt" style={{ fontSize: 12 }}>by <span className="text-uppercase">{shipment.associate} </span></i>
                                        <br />
                                        <i style={{ fontSize: 12 }}>
                                            Issue Date:
                                            ({new Date(shipment.created_at).toLocaleDateString('en-US', {
                                                month: '2-digit',
                                                day: '2-digit',
                                                year: 'numeric'
                                            })})
                                        </i>
                                    </>
                                }

                                {
                                    editingRow == index &&
                                    <div>
                                        <select name="associate" className="mt-3 fw-bold p-0 w-100" onChange={(e) => setData({ ...data, associate: e.target.value })}>
                                            <option value="fedex" selected={shipment.associate == 'fedex'}>Fedex
                                            </option>
                                            <option value="ups" selected={shipment.associate == 'ups'}>UPS
                                            </option>
                                        </select>
                                        <input type="text" name="tracking_number" defaultValue={shipment.tracking_number} onChange={(e) => setData({ ...data, tracking_number: e.target.value })} placeholder="Enter Tracking Number" className="mt-3 border-bottom w-100 " />

                                        <input type="text" name="from" defaultValue={shipment.shipment_from} placeholder="Enter Shippment From" className="mt-3 border-bottom w-100 " onChange={(e) => setData({ ...data, shipment_from: e.target.value })} />

                                        <input type="text" name="to" defaultValue={shipment.shipment_to} placeholder="Enter Shippment To" className="mt-3 border-bottom w-100 " onChange={(e) => setData({ ...data, shipment_to: e.target.value })} />
                                        <input
                                            type="date"
                                            name="date"
                                            onChange={(e) => setData({ ...data, created_at: e.target.value })}
                                            placeholder="Enter Shipment Date"
                                            className="mt-3 border-bottom w-100"
                                        />

                                    </div>
                                }

                            </div>
                            <div className="d-flex action-group gap-2">
                                {
                                    editingRow != index &&
                                    <button type='button' onClick={() => handleEdit(index)} className="btn edit-btn" style={{ height: 'max-content' }}>
                                        <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                                    </button>
                                }

                                {
                                    editingRow == index &&
                                    <button type='submit' className="btn btn-success fw-bold" style={{ height: 'max-content' }}>
                                        Save
                                    </button>
                                }
                                {
                                    editingRow == index &&
                                    <button onClick={handleCancel} type='button' className="btn btn-danger fw-bold" style={{ height: 'max-content' }}>
                                        Cancel
                                    </button>
                                }
                                {
                                    editingRow != index &&
                                    <a onClick={(e) => deleteShipment(e, index)} className="btn" style={{ height: 'max-content' }}>
                                        <i className="fa-solid fa-trash text-danger" aria-hidden="true" />
                                    </a>
                                }

                            </div>
                        </form>
                    ))
                }
                {
                    addShipment &&
                    <form onSubmit={handleSubmit} className="py-3 border-bottom">
                        <div>
                            <div>
                                <label htmlFor className="mt-2">Select Method</label>
                                <select name="associate" onChange={(e) => setData({ ...data, associate: e.target.value })} className="mb-0 nrml-inp fw-bold p-0 w-100" style={{ display: 'block' }}>
                                    <option value="fedex" selected>Fedex</option>
                                    <option value="ups">UPS</option>
                                </select>
                                <label htmlFor className="mt-2">Tracking Number</label>
                                <input type="text" name="tracking_number" onChange={(e) => setData({ ...data, tracking_number: e.target.value })} placeholder="Enter Tracking Number" className="mb-2 border-bottom w-100" />

                                <label htmlFor className="mt-2">From</label>
                                <input type="text" name="from" onChange={(e) => setData({ ...data, shipment_from: e.target.value })} placeholder="Enter Shipment From" className="mb-2 border-bottom w-100" />
                                <label htmlFor className="mt-2">To</label>
                                <input type="text" name="to" onChange={(e) => setData({ ...data, shipment_to: e.target.value })} placeholder="Enter Shipment To" className="mb-2 border-bottom w-100" />
                                <label htmlFor className="mt-2">Issue Date</label>
                                <input type="date" name="date" onChange={(e) => setData({ ...data, created_at: e.target.value })} placeholder="Shipment Date" className="mb-2 border-bottom w-100" />
                            </div>
                        </div>
                        <div className="d-flex action-group gap-2">
                            <button type='submit' className="btn btn-success fw-bold" style={{ height: 'max-content' }}>
                                Save
                            </button>
                            <button onClick={() => setAddShipment(false)} className="btn btn-danger cnclShipment fw-bold" style={{ height: 'max-content' }}>
                                Cancel
                            </button>
                        </div>
                    </form>
                }
                {
                    !addShipment &&
                    <div className="w-100 py-3">
                        <button type="button" onClick={() => setAddShipment(true)} className="btn btn-outline-dark addShipment">+ Add
                            Shipment</button>
                    </div>
                }

            </div>
        </div>
    )
}

export default Shipment