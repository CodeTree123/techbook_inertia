import JoditEditor from 'jodit-react'
import React, { useRef } from 'react'

const ScopeOfWork = ({ data, setData, scopeRef }) => {
    const editor = useRef(null);

    const config = {
        readonly: false,
        toolbarButtonSize: 'small',
        placeholder: 'Start typing here...',
    };
    return (
        <div ref={scopeRef} className="card action-cards bg-white border mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: '20px', fontWeight: 600 }}>Scope Of Work</h3>
            </div>
            <div className="card-body bg-white">
                <div
                    id="scopeForm"

                >
                    <JoditEditor
                        ref={editor}
                        value={data.scope_work}
                        config={config}
                        onBlur={(newContent) => setData({ ...data, scope_work: newContent })}
                    />
                </div>
            </div>
        </div>
    )
}

export default ScopeOfWork