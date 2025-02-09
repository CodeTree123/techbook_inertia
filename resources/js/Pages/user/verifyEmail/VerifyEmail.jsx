import React, { useEffect, useRef, useState } from 'react'
import MainLayout from '../layout/MainLayout'
import { Head, Link, useForm } from '@inertiajs/react'

const VerifyEmail = ({ email }) => {

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        email: email,
        code: ''
    });

    const [otp, setOtp] = useState(new Array(6).fill(""));
    const inputRefs = useRef([]);

    const handleChange = (index, e) => {
        const value = e.target.value;

        // Allow only numbers
        if (!/^\d$/.test(value)) return;

        const newOtp = [...otp];
        newOtp[index] = value;
        setOtp(newOtp);

        // Move to next input automatically
        if (index < 5 && value) {
            inputRefs.current[index + 1].removeAttribute("disabled");
            inputRefs.current[index + 1].focus();
        }

        // If last input is filled, submit OTP
        if (index === 5 && value) {
            const otpCode = newOtp.join("");
            setData("code", otpCode); // Update the form data
        }
    };

    const handleKeyDown = (index, e) => {
        if (e.key === "Backspace") {
            const newOtp = [...otp];
            newOtp[index] = "";
            setOtp(newOtp);

            // Move to the previous input if backspace is pressed
            if (index > 0) {
                inputRefs.current[index].setAttribute("disabled", true);
                inputRefs.current[index - 1].focus();
            }
        }
    };

    // Use useEffect to trigger the post request after `data.code` is updated
    useEffect(() => {
        if (data.code.length === 6) {
            post(route('user.password.verify.code'), {
                ...data
            });
        }
    }, [data.code]); 

    const maskEmail = (email) => {
        const [name, domain] = email.split("@");
        if (!name || !domain) return email; // Fallback if email is invalid

        return `${name[0]}***@${domain}`;
    };

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

    return (
        <MainLayout>
            <Head>
                <title>Verify Email | Techbook</title>
            </Head>
            <div className='container-fluid total-bg d-flex align-items-center justify-content-center'>
                <div className="card action-cards bg-white shadow border-0 mb-4 w-100" style={{ maxWidth: '576px' }}>
                    <div className="card-header bg-white d-flex justify-content-between align-items-center" style={{ borderTop: '10px solid rgb(175, 225, 175)' }}>
                        <h3 style={{ fontSize: 20, fontWeight: 600 }}>Verify Email</h3>
                    </div>

                    <div className="card-body">
                        <p>A 6 digit verification code sent to your email address : <b>{maskEmail(email)}</b></p>

                        <label htmlFor="" className='mb-1 mt-4'>Verification Code</label>
                        <div className="d-flex justify-content-start gap-2 mb-4">
                            {otp.map((digit, index) => (
                                <input
                                    key={index}
                                    type="text"
                                    value={digit}
                                    maxLength="1"
                                    ref={(el) => (inputRefs.current[index] = el)}
                                    onChange={(e) => handleChange(index, e)}
                                    onKeyDown={(e) => handleKeyDown(index, e)}
                                    className="form-control text-center fw-bold border-primary"
                                    style={{ width: "50px", height: "50px", fontSize: "1.5rem" }}
                                    disabled={index !== 0} // Only enable first input initially
                                />
                            ))}
                        </div>
                        <p>Please check including your Junk/Spam Folder. if not found, you can <Link href='/user/password/reset' className='fw-bold'>Try to send again</Link></p>
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

export default VerifyEmail