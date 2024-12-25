import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import ShipmentTable from './ShipmentTable';

const Shipment = ({ id, details, onSuccessMessage }) => {
    const [addShipment, setAddShipment] = useState(false);
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'associate': 'fedex',
        'tracking_number': '',
        'shipment_from': '',
        'shipment_to': '',
        'created_at': '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.createShipment', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Shipment Added Successfully');
                setAddShipment(false);
            }
        });
    };
    return (
        <div className="card bg-white shadow-lg border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Shipments</h3>
            </div>
            <div className="card-body bg-white">
                <ShipmentTable details={details} onSuccessMessage={onSuccessMessage}/>

                {
                    addShipment &&
                    <form onSubmit={submit} className="py-3 border-bottom">
                        <div>
                            <div>
                                <label htmlFor className="mt-2">Select Method</label>
                                <select name="associate" onChange={(e) => setData({ ...data, associate: e.target.value })} className="mb-0 nrml-inp fw-bold p-0 w-100" style={{ display: 'block' }}>
                                    <option value="fedex" selected>Fedex</option>
                                    <option value="ups">UPS</option>
                                </select>
                                <label htmlFor className="mt-2">Tracking Number</label>
                                <input type="text" name="tracking_number" onChange={(e)=>setData({ ...data, tracking_number: e.target.value})} placeholder="Enter Tracking Number" className="mb-2 border-bottom w-100" />
                                
                                <label htmlFor className="mt-2">From</label>
                                <input type="text" name="from" onChange={(e)=>setData({ ...data, shipment_from: e.target.value})} placeholder="Enter Shipment From" className="mb-2 border-bottom w-100" />
                                <label htmlFor className="mt-2">To</label>
                                <input type="text" name="to" onChange={(e)=>setData({ ...data, shipment_to: e.target.value})} placeholder="Enter Shipment To" className="mb-2 border-bottom w-100" />
                                <label htmlFor className="mt-2">Issue Date</label>
                                <input type="date" name="date" onChange={(e)=>setData({ ...data, created_at: e.target.value})} placeholder="Shipment Date" className="mb-2 border-bottom w-100" />
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