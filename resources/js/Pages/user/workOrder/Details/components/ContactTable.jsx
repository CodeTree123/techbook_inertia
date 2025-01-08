import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'

const ContactTable = ({ details, onSuccessMessage }) => {
    
    const [editableRow, setEditableRow] = useState(null);

    const { data, setData, post, delete:deleteItem, errors, processing, recentlySuccessful } = useForm({
        'title': '',
        'name': '',
        'phone': '',
    });

    const handleEdit = (index) => {
        setEditableRow(index);
        setData(null)
    };

    const handleCancel = () => {
        setEditableRow(null);
        setData(null)
    };

    const submitContact = (e, id) => {
        e.preventDefault();

        post(route('user.wo.updateContact', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Contact is Updated Successfully');
                setEditableRow(null);
                setData(null)
            }
        });
    };

    const deleteContact = (e, id) => {
        e.preventDefault();

        deleteItem(route('user.wo.deleteContact', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Contact is Deleted Successfully');
                setEditableRow(null);
                setData(null)
            }
        });
    };

    return (
        <>
            {
                details?.map((contact, index) => (
                    <form onSubmit={(e) => submitContact(e, contact.id)} className="py-3 d-flex justify-content-between border-bottom">
                        <div>
                            {
                                editableRow != index &&
                                <>
                                    <h6 className="">{contact.title}</h6>
                                    <p className="">{contact.name}</p>
                                    <a className="" href={`callto:${contact.phone}`}>{contact.phone}</a>
                                </>
                            }
                            {
                                editableRow == index &&
                                <div>
                                    <input type="text" name="title" defaultValue={contact.title} onChange={(e) => setData({ ...data, title: e.target.value })} className="mb-0 fw-bold p-0" />
                                    <input type="text" name="name" defaultValue={contact.name} onChange={(e) => setData({ ...data, name: e.target.value })} className="mb-0 p-0" />
                                    <input type="text" name="phone" defaultValue={contact.phone} onChange={(e) => setData({ ...data, phone: e.target.value })} className="mb-0 text-primary p-0" />
                                </div>
                            }

                        </div>
                        <div className="d-flex action-group gap-2">
                            {
                                editableRow != index &&
                                <button type='button' onClick={() => handleEdit(index)} className="btn edit-btn" style={{ height: 'max-content' }}>
                                    <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                                </button>
                            }
                            {
                                editableRow == index &&
                                <button type='submit' className="btn btn-success fw-bold" style={{ height: 'max-content' }}>
                                    Save
                                </button>
                            }
                            {
                                editableRow == index &&
                                <button onClick={handleCancel} className="btn btn-danger fw-bold" style={{ height: 'max-content' }}>
                                    Cancel
                                </button>
                            }
                            <button onClick={(e)=>deleteContact(e,contact.id)} type="button" className="btn" style={{ height: 'max-content' }}>
                                <i className="fa-solid fa-trash text-danger" aria-hidden="true" />
                            </button>


                        </div>
                    </form>
                ))
            }
        </>

    )
}

export default ContactTable