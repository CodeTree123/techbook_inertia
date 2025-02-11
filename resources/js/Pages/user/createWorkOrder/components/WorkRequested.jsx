import JoditEditor from 'jodit-react'
import React, { useMemo, useRef } from 'react'

const WorkRequested = ({ data, setData, errors, woReqRef }) => {
    const editor = useRef(null);

    const config = useMemo(() => ({
        readonly: false,
        placeholder: 'Start typing...'
    }), []);

    return (
        <div ref={woReqRef} className="card action-cards bg-white border mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: '20px', fontWeight: 600 }}>Work Requested</h3>
            </div>
            <div className="card-body bg-white">

                <h6 style={{ fontWeight: 600 }}>
                    Work Order Title :
                </h6>
                <input className="mb-0 border p-2 rounded mb-3 w-100" name="order_title" type="text" defaultValue={data.order_title} onChange={(e) => setData({ ...data, order_title: e.target.value })} />
                
                <div
                    id="wo_requested"
                    className='mb-3'
                >
                    <JoditEditor
                        ref={editor}
                        value={data.wo_requested}
                        config={config}
                        tabIndex={1}
                        onChange={() => {
                            const updatedContent = editor.current?.value;
                            setData({ ...data, wo_requested: updatedContent });
                        }}
                    />
                    {errors.wo_requested && <p className='text-danger'>{errors.wo_requested}</p>}
                </div>

                <h6 style={{ fontWeight: 600 }}>
                    Requested Date :
                </h6>
                <input className="mb-0 border p-2 rounded mb-3 w-100" name="requested_date" type="date" defaultValue={data.requested_date} onChange={(e) => setData({ ...data, requested_date: e.target.value })} />

                <h6 style={{ fontWeight: 600 }}>
                    Requesting Method :
                </h6>
                <select className="mb-0 w-100 p-0 mb-3 border p-2 rounded" name="request_type" onChange={(e) => setData({ ...data, request_type: e.target.value })}>
                    <option value='email' selected={data.request_type == 'email'}>Email
                    </option>
                    <option value='phone' selected={data.request_type == 'phone'}>Phone
                    </option>
                </select>

                
            </div>
        </div>
    )
}

export default WorkRequested