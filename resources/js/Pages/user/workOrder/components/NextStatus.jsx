import { useForm } from '@inertiajs/react';
import React from 'react'
import { Button } from 'react-bootstrap';
const NextStatus = ({id, onSuccessMessage, onErrorMessage, status, is_ftech, is_cancelled}) => {

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
    });

    const submit = (e) => {
        e.preventDefault();

        if(is_ftech == null && status == 2){
            onErrorMessage('Assign a technician first!');
        }else if(status == 12){
            window.location.href = `/customer/invoice/${id}`;
        }else{
            post(route('user.wo.nextStatus', id), {
                onSuccess: () => {
                    onSuccessMessage('Stage Updated Successfully');
                    setShowCancel(false);
                }
            });
        }
    };
    
    return (
        <>
            <form onSubmit={submit}>
                <Button variant='outline-primary' type="submit" disabled={status == 15 || is_cancelled}
                >
                    Next
                </Button>
            </form>

        </>
    )
}

export default NextStatus