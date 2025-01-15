import React, { useState } from 'react'

const Contact = ({ data, setData, contactRef }) => {
    const [addContact, setAddContact] = useState(false);
    const submit = (e) => {
        e.preventDefault();
        const newContact = {
            contact_title: data.contact_title || '',
            contact_name: data.contact_name || '',
            contact_phone: data.contact_phone || '',
        };

        setData({
            ...data,
            contacts: [...data.contacts, newContact],
            contact_title: '',
            contact_name: '',
            contact_phone: '',
        });

        setAddContact(false);
    };

    // Editing

    const [editingRow, setEditingRow] = useState(null);

    const handleEdit = (index) => {
        setEditingRow(index);

        const currentContact = data.contacts[index];
        setData({
            ...data,
            contact_title: currentContact.contact_title,
            contact_name: currentContact.contact_name,
            contact_phone: currentContact.contact_phone,
        });
    };

    const handleCancel = () => {
        setEditingRow(null);
        setData({
            ...data,
            contact_title: '',
            contact_name: '',
            contact_phone: '',
        });
    };

    const handleUpdate = (e, contactindex) => {
        e.preventDefault();

        const updatedContact = data.contacts.map((contact, index) =>
            index === contactindex
                ? {
                    ...contact,
                    contact_title: data.contact_title || contact.contact_title,
                    contact_name: data.contact_name || contact.contact_name,
                    contact_phone: data.contact_phone || contact
                        .contact_phone,
                }
                : contact
        );

        // Update the state
        setData({
            ...data,
            contacts: updatedContact,
            contact_title: '',
            contact_name: '',
            contact_phone: '',
        });

        // Exit edit mode
        setEditingRow(null);
    };

    const deleteContact = (e, index) => {
        e.preventDefault();

        // Remove the selected part by its index
        const updatedContact = data.contacts.filter((_, i) => i !== index);

        // Update the state
        setData({
            ...data,
            contacts: updatedContact,
        });

        // Exit edit mode if the deleted row is being edited
        if (editingRow === index) {
            setEditingRow(null);
        }
    };

    return (
        <div ref={contactRef} className="card bg-white mb-4 border">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Contacts</h3>
            </div>
            <div className="card-body bg-white">
                {
                    data?.contacts?.map((contact, index) => (
                        <form onSubmit={(e) => handleUpdate(e, index)} className="py-3 d-flex justify-content-between border-bottom">
                            <div>
                                {
                                    editingRow != index &&
                                    <>
                                        <h6 className="">{contact.contact_title}</h6>
                                        <p className="">{contact.contact_name}</p>
                                        <a className="" href={`callto:${contact.contact_phone}`}>{contact.contact_phone}</a>
                                    </>
                                }
                                {
                                    editingRow == index &&
                                    <div>
                                        <input type="text" name="title" defaultValue={contact.contact_title} onChange={(e) => setData({ ...data, contact_title: e.target.value })} className="mb-0 fw-bold border p-2 rounded mb-3 w-100" />
                                        <input type="text" name="name" defaultValue={contact.contact_name} onChange={(e) => setData({ ...data, contact_name: e.target.value })} className="mb-0 fw-bold border p-2 rounded mb-3 w-100" />
                                        <input type="text" name="phone" defaultValue={contact.contact_phone} onChange={(e) => setData({ ...data, contact_phone: e.target.value })} className="mb-0 fw-bold border p-2 rounded mb-3 w-100 text-primary" />
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
                                    <button onClick={handleCancel} className="btn btn-danger fw-bold" style={{ height: 'max-content' }}>
                                        Cancel
                                    </button>
                                }
                                <button onClick={(e) => deleteContact(e, index)} type="button" className="btn" style={{ height: 'max-content' }}>
                                    <i className="fa-solid fa-trash text-danger" aria-hidden="true" />
                                </button>


                            </div>
                        </form>
                    ))
                }
                {
                    addContact &&
                    <form onSubmit={submit} className="py-3 border-bottom">
                        <div>
                            <div>
                                <label htmlFor="title">Title</label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    placeholder="Enter Title"
                                    className="mb-2 border-bottom w-100"
                                    style={{ fontWeight: 600 }}
                                    onChange={(e) => setData({ ...data, contact_title: e.target.value })}
                                />

                                <label htmlFor="name">Name</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    placeholder="Enter Name"
                                    className="mb-2 border-bottom w-100"
                                    onChange={(e) => setData({ ...data, contact_name: e.target.value })}
                                />

                                <label htmlFor="phone">Number</label>
                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    placeholder="Enter Number"
                                    className="mb-2 border-bottom w-100 text-primary"
                                    onChange={(e) => setData({ ...data, contact_phone: e.target.value })}
                                />

                            </div>
                        </div>
                        <div className="d-flex action-group gap-2">
                            <button type='submit' className="btn btn-success fw-bold" style={{ height: 'max-content' }}>
                                Save
                            </button>
                            <button type='button' onClick={() => setAddContact(false)} className="btn btn-danger cnclContact fw-bold" style={{ height: 'max-content' }}>
                                Cancel
                            </button>
                        </div>
                    </form>
                }

                {
                    !addContact &&
                    <div className="mt-3">
                        <button onClick={() => setAddContact(true)} className="btn btn-outline-dark addContact" style={{ display: 'block' }}>Add Contact</button>
                    </div>
                }

            </div>
        </div>
    )
}

export default Contact