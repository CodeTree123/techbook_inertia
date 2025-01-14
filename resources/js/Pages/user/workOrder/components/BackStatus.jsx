import { useForm } from '@inertiajs/react';
import React from 'react'
import { Button } from 'react-bootstrap';

const BackStatus = ({id, onSuccessMessage, status, is_cancelled}) => {
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.backStatus', id), {
            onSuccess: () => {
                onSuccessMessage('Stage Updated Successfully');
                setShowCancel(false);
            }
        });
    };
    return (
        <form onSubmit={submit}>
            <Button variant='outline-secondary' type="submit" disabled={status == 1 || is_cancelled}>
                Revert
            </Button>
        </form>
    )
}

export default BackStatus