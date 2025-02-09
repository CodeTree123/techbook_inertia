import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'

const CloseoutNote = ({ note, is_cancelled, is_billing, onSuccessMessage }) => {

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        note: ''
    });

    const [editable, setEditable] = useState(null);

    const handleEdit = (id) => {
        setEditable(id);
    }

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.updateCloseout', note.id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Close Out Note Updated');
                setEditable(null);
            },
        });
    };

    return (
        <div className='px-4 py-3 mt-3 d-flex justify-content-between' style={{ backgroundColor: 'rgb(227, 242, 253)' }}>
            <div className='w-50'>
                <h6>Closeout Note:</h6>
                {
                    editable != note.id ?
                        <div>{note.note}</div> :
                        <textarea className='p-2 w-100' onChange={(e)=>setData({...data, note: e.target.value})}>{note.note}</textarea>
                }
            </div>
            <div className="d-flex action-group gap-2">
                {
                    editable != note.id &&
                    <button onClick={() => handleEdit(note.id)} className="btn edit-btn border-0" style={{ height: 'max-content' }} disabled={is_cancelled || is_billing}>
                        <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                    </button>
                }

                {
                    editable == note.id &&
                    <button onClick={(e) => submit(e)} className="btn btn-success fw-bold" style={{ height: 'max-content' }}>
                        Save
                    </button>
                }
                {
                    editable == note.id &&
                    <button onClick={() => setEditable(null)} className="btn btn-danger fw-bold" style={{ height: 'max-content' }}>
                        Cancel
                    </button>
                }

            </div>
        </div>
    )
}

export default CloseoutNote