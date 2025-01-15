import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import ContactTable from './ContactTable';

const Contact = ({id, details, onSuccessMessage, is_cancelled}) => {
    const [addContact, setAddContact] = useState(false);
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'title': '',
        'name': '',
        'phone': '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.createContact', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Contact is Created Successfully');
                setAddContact(false);
            }
        });
    };

    return (
        <div className="card bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Contacts</h3>
            </div>
            <div className="card-body bg-white">
                <ContactTable details={details} onSuccessMessage={onSuccessMessage} is_cancelled={is_cancelled}/>
                {
                    addContact &&
                    <form onSubmit={(e)=>submit(e)} className="py-3 border-bottom">
                        <div>
                            <div>
                                <label htmlFor>Title</label>
                                <input type="text" name="title" placeholder="Enter Title" className="mb-2 border-bottom w-100" style={{ fontWeight: 600 }} onChange={(e)=>setData({...data, title: e.target.value})} />
                                <label htmlFor>Name</label>
                                <input type="text" name="name" placeholder="Enter Name" className="mb-2 border-bottom w-100" onChange={(e)=>setData({...data, name: e.target.value})} />
                                <label htmlFor>Number</label>
                                <input type="text" name="phone" placeholder="Enter Number" className="mb-2 border-bottom w-100 text-primary" onChange={(e)=>setData({...data, phone: e.target.value})} />
                            </div>
                        </div>
                        <div className="d-flex action-group gap-2">
                            <button type='submit' className="btn btn-success fw-bold" style={{ height: 'max-content' }}>
                                Save
                            </button>
                            <button type='button' onClick={()=>setAddContact(false)} className="btn btn-danger cnclContact fw-bold" style={{ height: 'max-content' }}>
                                Cancel
                            </button>
                        </div>
                    </form>
                }

                {
                    !addContact &&
                    <div className="mt-3">
                        <button onClick={() => setAddContact(true)} className="btn btn-outline-dark addContact" style={{ display: 'block' }} disabled={is_cancelled}>Add Contact</button>
                    </div>
                }

            </div>
        </div>

    )
}

export default Contact