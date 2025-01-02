import React from 'react'

const ProfitSheet = () => {
    return (
        <div className="card action-cards bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Profit Sheet</h3>
                <div className="d-flex action-group gap-2">
                    <button className="btn edit-btn" style={{ display: 'block' }}>
                        <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                    </button>
                    <button className="btn save-btn fw-bold" style={{ display: 'none' }}>
                        Save
                    </button>
                    <button className="btn btn-danger cancel-btn fw-bold" style={{ display: 'none' }}>
                        Cancel
                    </button>
                </div>
            </div>
            <div className="card-body bg-white">
            </div>
        </div>

    )
}

export default ProfitSheet