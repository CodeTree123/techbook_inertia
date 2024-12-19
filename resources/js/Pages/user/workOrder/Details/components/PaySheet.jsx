import React, { useState } from 'react'

const PaySheet = ({ id, details, onSuccessMessage }) => {
    const rate = details?.technician?.rate?.['STD'] || 0;
    const totalHours = details?.check_in_out.reduce((sum, item) => {
        const hours = Number(item?.total_hours) || 0; // Default to 0 if total_hours is not a valid number
        return sum + hours;
      }, 0);
    const techPartsTotal = details?.tech_provided_parts?.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0) || 0;

    const totalCost = (rate * totalHours) + techPartsTotal;

    const [editable, setEditable] = useState(false);
    

    return (
        <div className="card bg-white shadow-lg border-0 mb-4 action-cards">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Pay Sheet</h3>
                <div className="d-flex action-group gap-2">
                    {
                        !editable &&
                        <button type='button' onClick={() => setEditable(true)} className="btn">
                            <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                        </button>
                    }
                    {
                        editable &&
                        <button type='submit' className="btn btn-success fw-bold">
                            Save
                        </button>
                    }
                    {
                        editable &&
                        <button onClick={() => setEditable(false)} className="btn btn-danger fw-bold">
                            Cancel
                        </button>
                    }

                </div>
            </div>
            <div className="card-body bg-white">
                <p className="mb-0">Payment Terms</p>
                <p className="mb-0 mx-5"><b>NET-{details?.technician?.terms ? details?.technician?.terms : ' '}-day terms</b>
                </p>
                <form>
                    <div className="d-flex mt-4 justify-content-between" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Labor</p>
                    </div>
                    <div className="d-flex mt-2 justify-content-between ps-5" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Rate</p>
                        <hr className="w-75" />
                        <p>${rate}
                        </p>
                    </div>
                    <div className="d-flex mt-2 justify-content-between ps-5" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Hour</p>
                        <hr className="w-50" />
                        <p id="totalHours">{totalHours} hours</p>
                    </div>
                    <hr />
                    <div className="d-flex mt-2 justify-content-between ps-5" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Total Labor Cost</p>
                        <hr className="w-50" />
                        <p>${totalHours * rate}
                        </p>
                    </div>
                    <div className="d-flex mt-2 justify-content-between ps-5" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Travel</p>
                        <hr className="w-50" />
                        {
                            !editable ?
                                <p className="">$0.00</p> :
                                <input name="travel" type="text" className="text-end" style={{ width: 100 }} defaultValue={0.00} />
                        }


                    </div>
                    <div className="d-flex mt-2 justify-content-between" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                        <p>Expenses</p>
                    </div>
                    {
                        details?.tech_provided_parts?.map((part) => (
                            <div className="d-flex mt-2 justify-content-between ps-5" style={{ fontSize: 20, fontWeight: '400 !important' }}>
                                <p>{part.part_name} x {part.quantity}</p>
                                <hr className="w-50" />
                                <p>${part.amount}</p>
                            </div>
                        ))
                    }
                </form>
                <button className="btn btn-outline-dark my-3">+ Add Items</button>
                <div className="d-flex mt-2 justify-content-between" style={{ fontSize: 20 }}>
                    <p className="fw-bold">Total Pay</p>
                    <p id="totalPay" className="fw-bold">
                        ${totalCost}
                    </p>
                </div>
            </div>
        </div>

    )
}

export default PaySheet