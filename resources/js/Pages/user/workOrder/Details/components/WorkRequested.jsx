import { useForm } from '@inertiajs/react';
import JoditEditor from 'jodit-react';
import React, { useRef, useState } from 'react'

const WorkRequested = ({ id, details, onSuccessMessage, is_cancelled }) => {
    const editor = useRef(null);
    const [editable, setEditable] = useState(false);
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'wo_requested': details.wo_requested,
        'requested_date': details.requested_date,
        'request_type': details.request_type,
    });

    const config = {
        readonly: false,
        toolbarButtonSize: 'small',
        placeholder: 'Start typing here...',
    };
    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.updateScopeOfWork', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Scope of work is Updated Successfully');
                setEditable(false);
            }
        });
    };
    return (
        <form onSubmit={submit} className="card action-cards bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: '20px', fontWeight: 600 }}>Work Requested</h3>

                <div className="d-flex gap-2">
                    {!editable &&
                        <button
                            className="btn border-0"
                            onClick={() => setEditable(!editable)}
                            disabled={is_cancelled}
                        >
                            <i className="fa-solid fa-pen-to-square"></i>
                        </button>
                    }
                    {editable && (
                        <>
                            <button
                                type="submit"
                                onClick={submit}
                                className="btn btn-success fw-bold"
                            >
                                Save
                            </button>
                            <button
                                type="button"
                                className="btn btn-danger fw-bold"
                                onClick={() => setEditable(false)}
                            >
                                Cancel
                            </button>
                        </>
                    )}
                </div>
            </div>
            <div className="card-body bg-white">
                {
                    editable &&
                    <div
                        id="scopeForm"

                    >
                        <JoditEditor
                            ref={editor}
                            value={details.wo_requested}
                            config={config}
                            onBlur={(newContent) => setData({ ...data, wo_requested: newContent })}
                        />
                    </div>
                }

                {
                    editable &&
                    <input type="date" defaultValue={details.requested_date} onChange={(e) => setData({ ...data, requested_date: e.target.value })} />
                }

                {!editable && (
                    <div className="mb-0 fw-bold"><div dangerouslySetInnerHTML={{ __html: details.wo_requested }} /></div>
                )}

                {!editable && (
                    <p className="mb-0 fw-bold">
                        Requesting Date: {details?.requested_date}
                    </p>
                )}

                {!editable && (
                    <p className="mb-0 fw-bold">
                        Requesting Method: {details?.request_type}
                    </p>
                )}
            </div>
        </form>
    )
}

export default WorkRequested