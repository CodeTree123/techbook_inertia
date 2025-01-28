import React, { useEffect, useState } from 'react'
import MainLayout from '../layout/MainLayout'
import { Head, useForm } from '@inertiajs/react';

const EditProfile = ({ user }) => {

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        firstname: user.firstname,
        lastname: user.lastname,
        address: user?.address?.address,
        state: user?.address?.state,
        zip: user?.address?.zip,
        country: user?.address?.country,
        city: user?.address?.city,
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

        post(route('user.profile.update'), {
            preserveScroll: true,
            onSuccess: () => {
                handleSuccessMessage('Profile Updated Successfully');
            },
            onError: (e) => {
                handleErrorMessage(e)
            }
        });
    };

    return (
        <MainLayout>
            <Head>
                <title>Edit Profile | Techbook</title>
            </Head>
            <div className='container-fluid total-bg d-flex align-items-center justify-content-center'>
                <div className="card action-cards bg-white shadow border-0 mb-4 w-100" style={{ maxWidth: '980px' }}>
                    <div className="card-header bg-white d-flex justify-content-between align-items-center" style={{ borderTop: '10px solid rgb(175, 225, 175)' }}>
                        <h3 style={{ fontSize: 20, fontWeight: 600 }}>Edit Profile</h3>
                    </div>

                    <div className="card-body">
                        <div className='row'>
                            <div className='col-md-6 mb-3'>
                                <label htmlFor="" className='mb-1'>First Name</label>
                                <input type="text" placeholder='First Name' className='w-100 border px-3 py-2' defaultValue={user.firstname} onChange={(e) => setData({ ...data, firstname: e.target.value })} />
                            </div>
                            <div className='col-md-6 mb-3'>
                                <label htmlFor="" className='mb-1'>Last Name</label>
                                <input type="text" placeholder='Last Name' className='w-100 border px-3 py-2' defaultValue={user.lastname} onChange={(e) => setData({ ...data, lastname: e.target.value })} />
                            </div>
                            <div className='col-md-6 mb-3'>
                                <label htmlFor="" className='mb-1'>Email</label>
                                <input type="text" placeholder='Email' className='w-100 border px-3 py-2' value={user.email} disabled />
                            </div>
                            <div className='col-md-6 mb-3'>
                                <label htmlFor="" className='mb-1'>Phone</label>
                                <input type="text" placeholder='Phone' className='w-100 border px-3 py-2' value={user.mobile} disabled />
                            </div>
                            <div className='col-md-6 mb-3'>
                                <label htmlFor="" className='mb-1'>Address</label>
                                <input type="text" placeholder='Address' className='w-100 border px-3 py-2' defaultValue={user?.address?.address} onChange={(e) => setData({ ...data, address: e.target.value })} />
                            </div>
                            <div className='col-md-6 mb-3'>
                                <label htmlFor="" className='mb-1'>State</label>
                                <input type="text" placeholder='State' className='w-100 border px-3 py-2' defaultValue={user?.address?.state} onChange={(e) => setData({ ...data, state: e.target.value })} />
                            </div>

                            <div className='col-md-4 mb-3'>
                                <label htmlFor="" className='mb-1'>Zip Code</label>
                                <input type="text" placeholder='Zip Code' className='w-100 border px-3 py-2' defaultValue={user?.address?.zip} onChange={(e) => setData({ ...data, zip: e.target.value })} />
                            </div>
                            <div className='col-md-4 mb-3'>
                                <label htmlFor="" className='mb-1'>City</label>
                                <input type="text" placeholder='City' className='w-100 border px-3 py-2' defaultValue={user?.address?.city} onChange={(e) => setData({ ...data, city: e.target.value })} />
                            </div>
                            <div className='col-md-4 mb-3'>
                                <label htmlFor="" className='mb-1'>Country</label>
                                <input type="text" placeholder='Country' className='w-100 border px-3 py-2' defaultValue={user?.address?.country} onChange={(e) => setData({ ...data, country: e.target.value })} />
                            </div>

                            <div className='col-12'>
                                <button className='btn w-100 fw-semibold' style={{ backgroundColor: 'rgb(175, 225, 175)' }} onClick={(e)=>submit(e)}>Submit</button>
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

export default EditProfile