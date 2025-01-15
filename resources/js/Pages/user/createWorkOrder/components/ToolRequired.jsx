import JoditEditor from 'jodit-react'
import React, { useRef, useMemo } from 'react'

const ToolRequired = ({ data, setData, toolRef }) => {
    const editor2 = useRef(null);

    const config = useMemo(() => ({
        readonly: false,
        placeholder: 'Start typing...'
    }), []);
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
                        ref={editor2}
                        value={data.r_tools}
                        config={config}
                        onChange={(newContent) => setData({ ...data, r_tools: newContent })}
                    />
                </div>
            </div>
        </div>
    )
}

export default ToolRequired