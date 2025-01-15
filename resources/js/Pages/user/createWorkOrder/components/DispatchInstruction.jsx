import JoditEditor from 'jodit-react';
import React, { useRef, useMemo } from 'react'

const DispatchInstruction = ({data, setData, instructionRef}) => {
    const editor5 = useRef(null);

    const config = useMemo(() => ({
        readonly: false,
        placeholder: 'Start typing...'
    }), []);

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
                        ref={editor5}
                        value={data.instruction}
                        config={config}
                        onChange={() => {
                            const updatedContent = editor5.current?.value;
                            setData({ ...data, instruction: updatedContent });
                        }}
                    />
                </div>
            </div>
        </div>
    )
}

export default DispatchInstruction