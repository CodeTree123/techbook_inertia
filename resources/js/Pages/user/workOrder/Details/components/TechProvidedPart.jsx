import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import TechProvidedPartsTable from './TechProvidedPartsTable';

const TechProvidedPart = ({ id, details, onSuccessMessage, onErrorMessage, is_cancelled, is_billing }) => {
    const [newItem, setNewItem] = useState(false);

    const handleNewItem = (e) => {
        e.preventDefault();
        setNewItem(true);
    };

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'part_name': '',
        'parts_number': '',
        'quantity': '',
        'price': '',
    });

    const submit = (e) => {
        e.preventDefault();
        if(details.ftech_id){
            post(route('user.wo.storeTechPart', id), {
                preserveScroll: true,
                onSuccess: () => {
                    onSuccessMessage('Parts Information Added');
                    setNewItem(false);
                },
            });
        }else{
            onErrorMessage('Assign a tech first')
        }
        
    };


    return (
        <div className="card bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Technician Provided Parts </h3>
            </div>
            <div className="card-body bg-white">
                <div className="row border-top border-bottom">
                    <div className="col-5 border-end">
                        <h6 style={{fontSize: '12px'}}>Parts Description</h6>
                    </div>
                    <div className="col-2 border-end">
                        <h6 style={{fontSize: '12px'}}>Parts Number</h6>
                    </div>
                    <div className="col-1 border-end">
                        <h6 style={{fontSize: '12px'}}>Price</h6>
                    </div>
                    <div className="col-1 border-end">
                        <h6 style={{fontSize: '12px'}}>Quantity</h6>
                    </div>
                    <div className="col-1 border-end">
                        <h6 style={{fontSize: '12px'}}>Amount</h6>
                    </div>
                    <div className="col-2" style={{fontSize: '12px'}}>
                        Actions
                    </div>
                </div>
                <TechProvidedPartsTable details={details} onSuccessMessage={onSuccessMessage} is_cancelled={is_cancelled} is_billing={is_billing}/>
                {
                    newItem &&
                    <form onSubmit={submit} className="row border-bottom">
                        <div className="col-5 border-end">
                            <input className="mb-0 fw-bold p-0 w-100 border-bottom-0" name="part_name" placeholder="Parts Name" type="text" style={{fontSize: '12px'}} onChange={(e) => setData({ ...data, part_name: e.target.value })} />
                        </div>
                        <div className="col-2 border-end">
                            <input className="mb-0 fw-bold p-0 w-100 border-bottom-0" name="parts_number" type="text" style={{fontSize: '12px'}} placeholder="Parts Number" onChange={(e) => setData({ ...data, parts_number: e.target.value })} />
                        </div>
                        <div className="col-1 border-end">
                            <input className="mb-0 fw-bold p-0 w-100 border-bottom-0" placeholder="Price" name="price" style={{fontSize: '12px'}} type="text" id="priceInput" onChange={(e) => setData({ ...data, price: e.target.value })} />
                        </div>
                        <div className="col-1 border-end">
                            <input className="mb-0 fw-bold p-0 w-100 border-bottom-0" name="quantity" placeholder="Quantity" style={{fontSize: '12px'}} type="text" id="quantityInput" onChange={(e) => setData({ ...data, quantity: e.target.value })} />
                        </div>
                        <div className="col-1 border-end" id="totalDisplay" style={{fontSize: '12px'}}>
                            ${(data.price * data.quantity).toFixed(2)}
                        </div>
                        <div className="col-2 d-flex align-items-center action-group">
                            <button style={{ height: 'max-content' }} type="submit" className="btn fw-bold">
                                <i className="fa-regular fa-floppy-disk text-success" aria-hidden="true" />
                            </button>
                            <button type="button" className="btn fw-bold" onClick={(e) => setNewItem(false)} style={{ height: 'max-content' }}>
                                <i className="fa-solid fa-ban text-danger" aria-hidden="true" />
                            </button>
                        </div>
                    </form>
                }
                {
                    !newItem &&
                    <div className="mt-3">
                        <button className="btn btn-outline-dark" onClick={handleNewItem} disabled={is_cancelled || is_billing}>+ Add Item</button>
                    </div>
                }

            </div>
        </div>

    )
}

export default TechProvidedPart