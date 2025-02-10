import { useForm } from '@inertiajs/react';
import JoditEditor from 'jodit-react';
import { DateTime } from 'luxon';
import React, { useRef, useState, useMemo } from 'react'

const WorkRequested = ({ id, details, onSuccessMessage, is_cancelled, is_billing }) => {
    const editor = useRef(null);
    const [editable, setEditable] = useState(false);
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'wo_requested': details.wo_requested,
        'requested_date': '',
        'request_type': '',
        'order_title': '',
        'source': '',
    });

    const config = useMemo(() => ({
        readonly: false,
        placeholder: 'Start typing...'
    }), []);

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.updateWorkRequested', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Work Requested is Updated Successfully');
                setEditable(false);
                setData(null)
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
                            disabled={is_cancelled || is_billing}
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
                    <>
                        <label htmlFor className='mt-3'>Work Order Title</label>
                        <input type="text" defaultValue={details.order_title} className='border-bottom w-100' onChange={(e) => setData({ ...data, order_title: e.target.value })} />

                        <label htmlFor className='mt-3'>Requesting Date</label>
                        <input type="date" defaultValue={details.requested_date} className='border-bottom w-100' onChange={(e) => setData({ ...data, requested_date: e.target.value })} />

                        <label htmlFor className='mt-3'>Requesting Method</label>
                        <select className='border-bottom w-100' onChange={(e) => setData({ ...data, request_type: e.target.value })}>
                            <option value="Email" selected={details.request_type == 'Email'}>Email</option>
                            <option value="Phone" selected={details.request_type == 'Phone'}>Phone</option>
                        </select>

                        <label htmlFor className='mt-3'>Source</label>
                        <input type="text" defaultValue={details.source} className='border-bottom w-100' onChange={(e) => setData({ ...data, source: e.target.value })} />
                    </>

                }
                {
                    editable &&
                    <div
                        id="scopeForm"
                        className='mt-3'
                    >
                        <JoditEditor
                            ref={editor}
                            value={details.wo_requested}
                            config={config}
                            onBlur={(newContent) => setData({ ...data, wo_requested: newContent })}
                        />
                    </div>
                }

                {!editable && (
                    <p className="mb-0">
                        <span className=''>Work Order Title: </span> {details?.order_title}
                    </p>
                )}

                {!editable && (
                    <p className="mb-0">
                        <span className=''>Requesting Date: </span> {details?.requested_date
                            ? DateTime.fromISO(details.requested_date).toFormat('MM-dd-yy')
                            : null}
                    </p>
                )}

                {!editable && (
                    <p className="mb-0">
                        <span className=''>Requesting Method: </span> {details?.request_type}
                    </p>
                )}

                {!editable && (
                    <p className="mb-4">
                        <span className=''>Source: </span> {details?.source}
                    </p>
                )}

                {!editable && (
                    <div className="mb-0"><div dangerouslySetInnerHTML={{ __html: details.wo_requested }} /></div>
                )}

            </div>
        </form>
    )
}

export default WorkRequested