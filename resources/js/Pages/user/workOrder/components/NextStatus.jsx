import { useForm } from '@inertiajs/react';
import React from 'react'
import { Button } from 'react-bootstrap';

const NextStatus = ({id, onSuccessMessage}) => {

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.nextStatus', id), {
            onSuccess: () => {
                onSuccessMessage('Stage Updated Successfully');
                setShowCancel(false);
            }
        });
    };
    
    return (
        <>
            <form onSubmit={submit}>
                <Button variant='outline-primary' type="submit">
                    Next
                </Button>
            </form>

        </>
    )
}

export default NextStatus