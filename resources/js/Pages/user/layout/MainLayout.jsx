import React, { useEffect, useState } from 'react'
import Header from '../components/Header'
import Footer from '../components/Footer'
import { Head } from '@inertiajs/react';

const MainLayout = ({ children }) => {
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
    <>
      <Head>
        <meta name="csrf-token" content={window.csrfToken} />
      </Head>
      <Header onSuccessMessage={handleSuccessMessage} onErrorMessage={handleErrorMessage} />
      {children}
      <Footer />
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
    </>
  )
}

export default MainLayout