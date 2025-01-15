import JoditEditor from 'jodit-react'
import React, { useRef, useMemo } from 'react'

const ScopeOfWork = ({ data, setData, scopeRef }) => {
    const editor3 = useRef(null);

    const config = useMemo(() => ({
        readonly: false,
        placeholder: 'Start typing...'
    }), []);
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
                        ref={editor3}
                        value={data.scope_work}
                        config={config}
                        onChange={(newContent) => setData({ ...data, scope_work: newContent })}
                    />
                </div>
            </div>
        </div>
    )
}

export default ScopeOfWork