import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Modal, Button } from 'react-bootstrap';
import {route} from 'ziggy-js';

const MakeCancel = ({id, is_cancelled, onSuccessMessage}) => {
    const [showCancel, setShowCancel] = useState(false);

    const handleCloseCancel = () => setShowCancel(false);
    const handleShowCancel = () => setShowCancel(true);

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        cancelling_note: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.cancel', id), {
            onSuccess: () => {
                onSuccessMessage('Stage Updated Successfully');
                setShowCancel(false);
            }
        });
    };

    return (
        <>
            <Button variant="danger" style={{height: 'max-content'}} onClick={handleShowCancel} disabled={is_cancelled == 7}>
                {is_cancelled != 7 ? 'Cancel' : 'Cancelled'}
            </Button>

            <Modal show={showCancel} onHide={handleCloseCancel}>
                <Modal.Body>
                    <div className="position-relative">
                        <h5 className="modal-title" id="exampleModalLabel">Are you sure?</h5>

                    </div>
                    <p className='mb-0'>You want to Cancel the work order!</p>
                </Modal.Body>
                <Modal.Footer>
                    <form className="w-100" onSubmit={submit}>

                        <div className="form-floating mb-3">
                            <textarea className="form-control" name="cancelling_note" placeCanceler="Leave a comment here" id="floatingTextarea2" style={{ height: 100 }} defaultValue={data.cancelling_note} onChange={(e) => setData('cancelling_note', e.target.value)} />
                            <label htmlFor="floatingTextarea2">Canceling Note</label>
                        </div>
                        <Button variant="secondary" className='me-2' onClick={handleCloseCancel}>
                            Close
                        </Button>
                        <Button type="submit" variant="danger">
                            Cancel
                        </Button>
                    </form>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default MakeCancel