import JoditEditor from 'jodit-react'
import React, { useRef } from 'react'

const ToolRequired = ({ data, setData, toolRef }) => {
    const editor = useRef(null);
    
        const config = {
            readonly: false,
            toolbarButtonSize: 'small',
            placeholder: 'Start typing here...',
        };
    return (
        <div ref={toolRef} className="card action-cards bg-white border mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: '20px', fontWeight: 600 }}>Tool Required</h3>
            </div>
            <div className="card-body bg-white">
                <div
                    id="scopeForm"

                >
                    <JoditEditor
                        ref={editor}
                        value={data.r_tools}
                        config={config}
                        onBlur={(newContent) => setData({ ...data, r_tools: newContent })}
                    />
                </div>
            </div>
        </div>
    )
}

export default ToolRequired