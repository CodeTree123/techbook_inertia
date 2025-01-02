import { useForm } from '@inertiajs/react';
import JoditEditor from 'jodit-react';
import React, { useRef, useState } from 'react'

const ToolRequired = ({ id, details, onSuccessMessage }) => {
    const editor = useRef(null);
    const [editable, setEditable] = useState(false);
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'r_tools': details.r_tools,
    });

    const config = {
        readonly: false,
        toolbarButtonSize: 'small',
        placeholder: 'Start typing here...',
    };
    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.updateTools', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Required Tools is Updated Successfully');
                setEditable(false);
            }
        });
    };
    return (
        <form onSubmit={submit} className="card action-cards bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: '20px', fontWeight: 600 }}>Tool Required</h3>

                <div className="d-flex gap-2">
                    {!editable &&
                        <a
                            className="btn"
                            onClick={() => setEditable(!editable)}
                        >
                            <i className="fa-solid fa-pen-to-square"></i>
                        </a>
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
                            value={details.r_tools}
                            config={config}
                            onBlur={(newContent) => setData({ ...data, r_tools: newContent })}
                        />
                    </div>
                }

                {!editable && (
                    <div className="mb-0 fw-bold"><div dangerouslySetInnerHTML={{ __html: details.r_tools }} /></div>
                )}
            </div>
        </form>
    )
}

export default ToolRequired