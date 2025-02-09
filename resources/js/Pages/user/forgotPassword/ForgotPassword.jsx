import { Head, useForm, usePage } from '@inertiajs/react'
import React, { useEffect, useState } from 'react'
import MainLayout from '../layout/MainLayout'

const ForgotPassword = () => {

    const { flash } = usePage().props;

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        value: '',
    });

    const [successMessage, setSuccessMessage] = useState('');
    const [errorMessage, setErrorMessage] = useState('');

    const handleSuccessMessage = (data) => {
        setSuccessMessage(data);
    };

    const handleErrorMessage = (data) => {
        setErrorMessage(data);
    };

    useEffect(() => {
        if (errorMessage) {
            const timer = setTimeout(() => {
                setErrorMessage('');
            }, 1500);
            return () => clearTimeout(timer);
        }
    }, [errorMessage]);

    useEffect(() => {
        if (successMessage) {
            const timer = setTimeout(() => {
                setSuccessMessage('');
            }, 1500);
            return () => clearTimeout(timer);
        }
    }, [successMessage]);

    const submit = (e) => {
        e.preventDefault();

        post(route('user.password.email'), {
            preserveScroll: true,
            onSuccess: () => {
                // handleSuccessMessage('Password Changed Successfully');
            }
        });
    };

    return (
        <MainLayout>
            <Head>
                <title>Forgot Password | Techbook</title>
            </Head>
            <div className='container-fluid total-bg d-flex align-items-center justify-content-center'>
                <div className="card action-cards bg-white shadow border-0 mb-4 w-100" style={{ maxWidth: '576px' }}>
                    <div className="card-header bg-white d-flex justify-content-between align-items-center" style={{ borderTop: '10px solid rgb(175, 225, 175)' }}>
                        <h3 style={{ fontSize: 20, fontWeight: 600 }}>Account Recovery</h3>
                    </div>

                    <div className="card-body">
                        <p>To recover your account please provide your email or username to find your account.</p>
                        <div className='row'>
                            <div className='col-md-12 mb-3'>
                                <label htmlFor="" className='mb-1'>Email Or Username</label>
                                <input type="text" placeholder='Enter Your Email Or Username' className='w-100 border px-3 py-2' onChange={(e) => setData({ ...data, value: e.target.value })} />
                                {errors.value && <p className='text-danger'>{errors.value}</p>}
                                {flash?.verifyError && <p className='text-danger'>{flash?.verifyError}</p>}
                            </div>

                            <div className='col-12'>
                                <button className='btn w-100 fw-semibold' style={{ backgroundColor: 'rgb(175, 225, 175)' }} onClick={(e) => submit(e)}>Submit</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {successMessage && (
                <div className="alert alert-success alert-dismissible fade show position-fixed" style={{ bottom: '50px', right: '50px', height: 'max-content' }} role="alert">
                    <span>{successMessage}</span>
                    <button type="button" className="btn-close" onClick={() => setSuccessMessage(null)} />
                </div >
            )}
            {errorMessage && (
                <div className="alert alert-danger alert-dismissible fade show position-fixed" style={{ bottom: '50px', right: '50px', height: 'max-content' }} role="alert">
                    <span>{errorMessage}</span>
                    <button type="button" className="btn-close" onClick={() => setErrorMessage(null)} />
                </div >
            )
            }
        </MainLayout>
    )
}

export default ForgotPassword