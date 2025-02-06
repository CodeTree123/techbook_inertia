import { Head, Link, usePage } from '@inertiajs/react'
import React from 'react'
import Footer from '../components/Footer'
import Slider from './partials/Slider';

const Home = () => {
  const { user, general } = usePage().props;

  return (
    <>
      <Head>
        <title>{general.site_name}</title>
      </Head>
      <header className='py-3' style={{ background: '#AFE1AF' }}>
        <div className='container d-flex flex-wrap justify-content-md-between justify-content-center align-items-center'>
          <h1 className='fs-4 fw-bold navbar-brand poppins'>
            <Link href="/" style={{ color: '#4b208c' }}>{general.site_name}</Link>
          </h1>

          {
            user ?
              <Link href='/user/work/order/view/pdf/user/inertia/dashboard' className='btn text-white rounded-5 px-4 py-1 poppins text-uppercase' style={{ background: '#4b208c' }}>Access Dashboard</Link> :
              <Link href='/user/login' className='btn text-white rounded-5 px-4 py-1 poppins text-uppercase' style={{ background: '#4b208c' }}>Login</Link>
          }

        </div>
      </header>
      <div className='py-3' style={{ background: '#AFE1AF' }}>
        <div className='container d-flex align-items-center'>
          <div className='row w-100' style={{ minHeight: 'calc(100vh - 100px)' }}>
            <div className='col-md-6 d-flex flex-column justify-content-center align-items-md-start align-items-center'>
              <h2 className='fw-bold navbar-brand poppins text-uppercase mb-4 text-md-start text-center' style={{ fontSize: '50px' }}>tech <br />service <br /> provider</h2>
              {
                user ?
                  <Link href='/user/work/order/view/pdf/user/inertia/dashboard' className='btn text-white rounded-5 px-4 py-1 poppins text-uppercase' style={{ background: '#4b208c', width: 'max-content' }}>Access Dashboard</Link> :
                  <Link href='/user/login' className='btn text-white rounded-5 px-4 py-1 poppins text-uppercase' style={{ background: '#4b208c', width: 'max-content' }}>Login</Link>
              }
            </div>

            <div className='col-md-6 d-flex justify-content-md-end justify-content-center align-items-center'>
              <Slider />
            </div>
          </div>
        </div>
      </div>

      <div className='py-5 border'>
        <div className='container'>
          <div className='row'>
            <div className='col-md-4 text-md-start text-center mb-md-0 mb-5'>
              <h4 className='fw-bold navbar-brand poppins text-uppercase' style={{ color: '#4b208c' }}>
                <i class="fa-solid fa-location-dot me-1"></i>Address</h4>
              <p>
                1905 Marketview Dr.
                Suite 226
                <br />
                Yorkville, IL 60560
              </p>
            </div>

            <div className='col-md-4 text-md-start text-center mb-md-0 mb-5'>
              <h4 className='fw-bold navbar-brand poppins text-uppercase' style={{ color: '#4b208c' }}>
                <i class="fa-regular fa-address-book me-1"></i>Contacts</h4>
              <ul>
                <li><a href="mailto:info@techyeahinc.com" style={{ color: '#4b208c' }}><i class="fa-regular fa-envelope me-1"></i>info@techyeahinc.com</a></li>
                <li><a href="callto:630.474.5234" style={{ color: '#4b208c' }}><i class="fa-solid fa-phone me-1"></i>630.474.5234</a></li>
              </ul>
            </div>

            <div className='col-md-4 text-md-start text-center mb-md-0 mb-5'>
              <h4 className='fw-bold navbar-brand poppins text-uppercase' style={{ color: '#4b208c' }}>
                <i class="fa-solid fa-location-dot me-1"></i>Social Connection</h4>
              <div className='flex'>
                <a href="" className='fs-2 me-4 text-dark'><i class="fa-brands fa-facebook"></i></a>
                <a href="" className='fs-2 me-4 text-dark'><i class="fa-brands fa-linkedin"></i></a>
                <a href="" className='fs-2 me-4 text-dark'><i class="fa-brands fa-instagram"></i></a>
                <a href="" className='fs-2 me-4 text-dark'><i class="fa-brands fa-x-twitter"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </>
  )
}

export default Home