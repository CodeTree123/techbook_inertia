import React from 'react'
import MainLayout from '../layout/MainLayout'
import { Head, Link, useForm } from '@inertiajs/react'

const Login = () => {

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        username: '',
        password: '',
        remember: false
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.login'), {
            preserveScroll: true,
            onSuccess: () => {
                handleSuccessMessage('Password Changed Successfully');
            }
        });
    };

    return (
        <MainLayout>
            <Head>
                <title>Login | Techbook</title>
            </Head>

            <div className='container-fluid total-bg d-flex align-items-center justify-content-center'>
                <div className="card action-cards bg-white shadow border mb-4 w-100" style={{ maxWidth: '576px' }}>
                    <div className="card-header bg-white d-flex justify-content-between align-items-center" style={{ borderTop: '10px solid rgb(175, 225, 175)' }}>
                        <h3 style={{ fontSize: 20, fontWeight: 600 }}>Login</h3>
                    </div>

                    <div className="card-body">
                        <div className='row'>
                            <div className='col-md-12 mb-3'>
                                <label htmlFor="" className='mb-1'>Username</label>
                                <input type="text" placeholder='Username' className='w-100 border px-3 py-2' defaultValue={data.username} onChange={(e) => setData({ ...data, username: e.target.value })} />
                                {errors.username && <p className='text-danger'>{errors.username}</p>}
                            </div>

                            <div className='col-md-12 mb-3'>
                                <label htmlFor="" className='mb-1'>Password</label>
                                <input type="password" placeholder='Password' className='w-100 border px-3 py-2' onChange={(e) => setData({ ...data, password: e.target.value })} />
                                {errors.password && <p className='text-danger'>{errors.password}</p>}
                            </div>

                            <div className="col-md-12 form-check mx-3 py-2">
                                <input type="checkbox" className="form-check-input" id="exampleCheck1" checked={data.remember} onChange={(e) => setData({ ...data, remember: !data.remember })}/>
                                <label className="form-check-label" htmlFor="exampleCheck1">Remember me</label>
                            </div>


                            <div className='col-12'>
                                <button className='btn w-100 fw-semibold' style={{ backgroundColor: 'rgb(175, 225, 175)' }} onClick={(e) => submit(e)}>Login</button>
                            </div>
                            <hr className='my-3'/>
                            <Link href="/user/password/reset" className='mb-3 text-center fw-bold' style={{color: 'rgb(9, 89, 9)'}}>Forgot Password</Link>
                        </div>
                    </div>
                </div>
            </div>
        </MainLayout>
    )
}

export default Login