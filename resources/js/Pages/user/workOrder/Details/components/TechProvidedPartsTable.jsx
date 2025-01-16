import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'

const TechProvidedPartsTable = ({ details, onSuccessMessage, is_cancelled }) => {
    const [editingRow, setEditingRow] = useState(null);

    const handleEdit = (index) => {
        setEditingRow(index);
    };

    const handleCancel = () => {
        setEditingRow(null);
    };

    const { data, setData, post, delete:deleteItem, errors, processing, recentlySuccessful } = useForm({
        'part_name': '',
        'parts_number': '',
        'quantity': '',
        'price': '',
    });

    const submit = (e, id) => {
        e.preventDefault();

        post(route('user.wo.updateTechPart', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Parts Updated Successfully');
                setEditingRow(null);
                setData(null)
            },
            onError: (error) => {
                console.error('Error updating part:', error);
            }
        });
    };

    const deletePart = (e, id) => {
        e.preventDefault();

        deleteItem(route('user.wo.deleteTechPart', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Parts Deleted Successfully');
            },
            onError: (error) => {
                console.error('Error updating part:', error);
            }
        });
    };

    return (
        <>
            {
                details?.tech_provided_parts?.map((part, index) => (
                    <form onSubmit={(e) => submit(e, part.id)} className="row border-bottom">
                        <div className="col-5 border-end">
                            {
                                editingRow != index ?
                                    <p className="mb-0 fw-bold" style={{ display: 'block', fontSize: '12px' }}>{part.part_name} </p> :
                                    <input className="mb-0 fw-bold p-0 w-100" style={{ fontSize: '12px' }} onChange={(e) => setData({ part_name: e.target.value })} name="part_name" type="text" defaultValue={part.part_name} />
                            }


                        </div>
                        <div className="col-2 border-end">
                            {
                                editingRow != index ?
                                    <p className="mb-0 fw-bold" style={{ display: 'block', fontSize: '12px' }}>{part.parts_number}</p> :
                                    <input className="mb-0 fw-bold p-0 w-100" style={{ fontSize: '12px' }} name="parts_number" onChange={(e) => setData({ parts_number: e.target.value })} type="text" defaultValue={part.parts_number} />}
                        </div>
                        <div className="col-1 border-end">
                            {
                                editingRow != index ?
                                    <p className="mb-0 fw-bold" style={{ display: 'block', fontSize: '12px' }}>${part.price}</p> :
                                    <input className="mb-0 fw-bold p-0 w-100" style={{ fontSize: '12px' }} onChange={(e) => setData({ price: e.target.value })} name="price" type="text" defaultValue={part.price} />}
                        </div>
                        <div className="col-1 border-end">
                            {
                                editingRow != index ?
                                    <p className="mb-0 fw-bold" style={{ display: 'block', fontSize: '12px' }}>{part.quantity}</p> :
                                    <input className="mb-0 fw-bold p-0 w-100" style={{ fontSize: '12px' }} onChange={(e) => setData({ quantity: e.target.value })} name="quantity" type="text" defaultValue={part.quantity} />}
                        </div>
                        <div className="col-1 border-end">
                            <p className="mb-0 fw-bold" style={{ fontSize: '12px' }}>${part.quantity * part.price}</p>
                        </div>
                        <div className="col-2 d-flex action-group">
                            {
                                editingRow != index &&
                                <button type="button" className="btn border-0" onClick={() => handleEdit(index)} style={{ display: 'block' }} disabled={is_cancelled}>
                                    <i className="fa-solid fa-pen-to-square text-primary" aria-hidden="true" />
                                </button>
                            }
                            {
                                editingRow == index &&
                                <button type='submit' className="btn fw-bold border-0" disabled={is_cancelled}>
                                    <i className="fa-regular fa-floppy-disk text-success" aria-hidden="true" />
                                </button>
                            }
                            {
                                editingRow == index &&
                                <button type="button" onClick={handleCancel} className="btn fw-bold border-0" disabled={is_cancelled}>
                                    <i className="fa-solid fa-ban text-danger" aria-hidden="true" />
                                </button>
                            }
                            {
                                editingRow != index &&
                                <button onClick={(e)=>deletePart(e, part.id)} type="button" className="btn border-0" style={{ height: 'max-content' }} disabled={is_cancelled}>
                                    <i className="fa-solid fa-trash text-danger" aria-hidden="true" />
                                </button>
                            }
                        </div>
                    </form>
                ))
            }
        </>
    )
}

export default TechProvidedPartsTable