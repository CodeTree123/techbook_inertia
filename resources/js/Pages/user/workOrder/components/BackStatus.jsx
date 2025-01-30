import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import { Button, Modal } from 'react-bootstrap';

const BackStatus = ({ id, onSuccessMessage, status, is_cancelled, is_billing }) => {
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'review_note': ''
    });

    const [showHold, setShowHold] = useState(false);

    const handleCloseHold = () => setShowHold(false);
    const handleShowHold = () => setShowHold(true);
    console.log(status);

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.backStatus', id), {
            onSuccess: () => {
                onSuccessMessage('Stage Updated Successfully');
            }
        });
    };

    const makeReview = (e) => {
        e.preventDefault();

        post(route('user.wo.makeReview', id), {
            onSuccess: () => {
                onSuccessMessage('Status Updated Successfully');
            }
        });
    };



    return (
        <div>
            {
                status == 10 ?
                    <Button variant='warning' type="submit" onClick={handleShowHold} disabled={is_cancelled || is_billing}>
                        Need Review
                    </Button> : <Button variant='outline-secondary' onClick={submit} type="submit" disabled={status == 1 || is_cancelled || is_billing}>
                        Revert
                    </Button>
            }
            <Modal show={showHold} onHide={handleCloseHold}>
                <Modal.Body>
                    <div className="position-relative">
                        <h5 className="modal-title" id="exampleModalLabel">Are you sure?</h5>

                    </div>
                    <p className='mb-0'>You want to review the work order!</p>
                </Modal.Body>
                <Modal.Footer>
                    <form className="w-100" onSubmit={makeReview}>

                        <div className="form-floating mb-3">
                            <textarea className="form-control" name="holding_note" placeholder="Leave a comment here" id="floatingTextarea2" style={{ height: 100 }} defaultValue={data.review_note} onChange={(e) => setData({...data,'review_note': e.target.value})} />
                            <label htmlFor="floatingTextarea2">Issue Note</label>
                            {errors.review_note && <p className='text-danger'>{errors.review_note}</p>}
                        </div>
                        <Button variant="secondary" className='me-2' onClick={handleCloseHold}>
                            Close
                        </Button>
                        <Button type="submit" variant="dark">
                            Submit
                        </Button>
                    </form>
                </Modal.Footer>
            </Modal>
        </div>
    )
}

export default BackStatus