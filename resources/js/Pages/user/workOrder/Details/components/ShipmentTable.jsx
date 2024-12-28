import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'

const ShipmentTable = ({ details, onSuccessMessage }) => {
    const [editingRow, setEditingRow] = useState(null);

    const handleEdit = (index) => {
        setEditingRow(index);
        setData(null)
    };

    const handleCancel = () => {
        setEditingRow(null);
        setData(null)
    };

    const formatDate = (dateString) => {
        if (!dateString) return ''; // If no date, return an empty string
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
        const day = String(date.getDate()).padStart(2, '0'); // Pad single digits
        return `${year}-${month}-${day}`;
    };


    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({
        'associate': '',
        'tracking_number': '',
        'shipment_from': '',
        'shipment_to': '',
        'created_at': '',
    });

    const submit = (e, id) => {
        e.preventDefault();

        post(route('user.wo.updateShipment', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Shipment Updated Successfully');
                setEditingRow(null);
                setData(null)
            }
        });
    };

    const deleteShipment = (e, id) => {
        e.preventDefault();

        deleteItem(route('user.wo.deleteShipment', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Shipment Deleted Successfully');
                setEditingRow(null);
                setData(null)
            }
        });
    };

    return (
        <div>
            {
                details?.shipments?.map((shipment, index) => (
                    <form onSubmit={(e) => submit(e, shipment.id)} className="p-3 mb-3 action-cards d-flex justify-content-between" style={{ backgroundColor: '#E3F2FD' }}>
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
                                        value={formatDate(shipment.created_at)}
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
                                <a onClick={(e) => deleteShipment(e, shipment.id)} className="btn" style={{ height: 'max-content' }}>
                                    <i className="fa-solid fa-trash text-danger" aria-hidden="true" />
                                </a>
                            }

                        </div>
                    </form>
                ))
            }
        </div>
    )
}

export default ShipmentTable