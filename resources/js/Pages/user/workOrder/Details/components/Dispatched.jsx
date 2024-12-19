import { useForm } from '@inertiajs/react';
import JoditEditor from 'jodit-react';
import React, { useRef, useState } from 'react'

const Dispatched = ({id, details, onSuccessMessage}) => {
    const editor = useRef(null);
    const [editable, setEditable] = useState(false);
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'instruction': details.instruction,
    });

    const config = {
        readonly: false,
        toolbarButtonSize: 'small',
        placeholder: 'Start typing here...',
    };
    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.updateDispatchedInstruction', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Dispatch Instructions is Updated Successfully');
                setEditable(false);
            }
        });
    };
    return (
        <form onSubmit={submit} className="card action-cards bg-white shadow-lg border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: '20px', fontWeight: 600 }}>Dispatch Instructions</h3>

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
                            value={details.instruction}
                            config={config}
                            onBlur={(newContent) => setData({ ...data, instruction: newContent })}
                        />
                    </div>
                }

                {!editable && (
                    <div className="mb-0 fw-bold"><div dangerouslySetInnerHTML={{ __html: details.instruction }} /></div>
                )}
            </div>
        </form>
    )
}

export default Dispatched