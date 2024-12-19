import React from 'react'

const PartByTC = () => {
    return (
        <div className="card action-cards bg-white shadow-lg border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Parts Provided By Tech Yeah/Client
                </h3>
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
            <div className="card-body d-flex bg-white">
                <div className="w-100 py-5 border-end">
                    <p>Tech Yeah</p>
                    <button type="button" className="btn btn-outline-dark">+ Add Item</button>
                </div>
                <div className="w-100 p-5">
                    <p>Client</p>
                    <button type="button" className="btn btn-outline-dark">+ Add Item</button>
                </div>
            </div>
        </div>

    )
}

export default PartByTC