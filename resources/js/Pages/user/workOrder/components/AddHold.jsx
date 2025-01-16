import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Modal, Button } from 'react-bootstrap';
import {route} from 'ziggy-js';


const AddHold = ({ id, stage, onSuccessMessage, is_cancelled  }) => {

    const [showHold, setShowHold] = useState(false);

    const handleCloseHold = () => setShowHold(false);
    const handleShowHold = () => setShowHold(true);

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        is_hold: id,
        holding_note: '',
    });

    const submit = (e) => {
        e.preventDefault();
        
        post(route('user.wo.hold',id), {
            onSuccess: () => {
                onSuccessMessage('Stage Updated Successfully');
                setShowHold(false);
            }
        });
    };

    return (
        <>
            <Button variant="outline-dark" style={{height: 'max-content'}} onClick={handleShowHold} disabled={is_cancelled}>
                {stage ? 'Remove Hold' : "Add Hold"}
            </Button>

            <Modal show={showHold} onHide={handleCloseHold}>
                <Modal.Body>
                    <div className="position-relative">
                        <h5 className="modal-title" id="exampleModalLabel">Are you sure?</h5>

                    </div>
                    <p className='mb-0'>You want to hold the work order!</p>
                </Modal.Body>
                <Modal.Footer>
                    <form className="w-100" onSubmit={submit}>
                        
                        <div className="form-floating mb-3">
                            <textarea className="form-control" name="holding_note" placeholder="Leave a comment here" id="floatingTextarea2" style={{ height: 100 }} defaultValue={data.holding_note} onChange={(e) => setData('holding_note', e.target.value)}/>
                            <label htmlFor="floatingTextarea2">Holding Note</label>
                        </div>
                        <Button variant="secondary" className='me-2' onClick={handleCloseHold}>
                            Close
                        </Button>
                        <Button type="submit" variant="dark">
                        {stage ? 'Remove Hold' : "Add Hold"}
                        </Button>
                    </form>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default AddHold