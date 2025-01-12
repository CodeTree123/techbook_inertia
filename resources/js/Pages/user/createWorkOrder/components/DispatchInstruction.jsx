import JoditEditor from 'jodit-react';
import React, { useRef } from 'react'

const DispatchInstruction = ({data, setData, instructionRef}) => {
    const editor = useRef(null);

    const config = {
        readonly: false,
        toolbarButtonSize: 'small',
        placeholder: 'Start typing here...',
    };
    return (
        <div ref={instructionRef} className="card action-cards bg-white border mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: '20px', fontWeight: 600 }}>Dispatch Instructions</h3>
            </div>
            <div className="card-body bg-white">
                <div
                    id="dispatchForm"

                >
                    <JoditEditor
                        ref={editor}
                        value={data.instruction}
                        config={config}
                        onBlur={(newContent) => setData({ ...data, instruction: newContent })}
                    />
                </div>
            </div>
        </div>
    )
}

export default DispatchInstruction