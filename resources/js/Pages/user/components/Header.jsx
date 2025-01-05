import { Link, usePage } from '@inertiajs/react';
import React from 'react'
import '../../../../css/header.css'
import CreateSiteModal from './Site/CreateSiteModal';
import SearchSiteModal from './Site/SearchSiteModal';
import CreateCustomerModal from './Customer/CreateCustomerModal';
import SearchCustomerModal from './Customer/SearchCustomerModal';
import CreateTechModal from './FieldTech/CreateTechModal';
import SearchTechnicianModal from './FieldTech/SearchTechnicianModal';
import ZipSearchModal from './FieldTech/ZipSearchModal';
import ImportSite from './Site/ImportSite';
import DistanceSearchModal from './FieldTech/DistanceSearchModal';
import { Dropdown } from 'react-bootstrap';

const Header = ({ onSuccessMessage, onErrorMessage }) => {
    const { user } = usePage().props;
    return (
        <header className="fixed-top">
            <div className="p-1 d-flex justify-content-end" style={{ background: "#AFE1AF", position: "relative" }}>
                <Dropdown>
                    <Dropdown.Toggle variant="outline-light" id="dropdown-basic" className='shadow-circle text-dark'>
                        <i className="fas fa-user-circle text-success" style={{ marginRight: "10px" }}></i>
                        {/** Authenticated User Fullname */}
                        {user ? user.firstname + ' ' + user.lastname : "User"}
                    </Dropdown.Toggle>

                    <Dropdown.Menu>
                        <Dropdown.Item href="/user/profile-setting" className='mb-3'>Profile Setting</Dropdown.Item>
                        <Dropdown.Item href="/user/change-password" className='mb-3'>Change Password</Dropdown.Item>
                        <hr />
                        <Dropdown.Item href="/user/logout" className='text-danger mt-3' onClick={(e) => {
                            e.preventDefault();
                            document.getElementById('logout-form').submit();
                        }}>Logout</Dropdown.Item>
                    </Dropdown.Menu>
                </Dropdown>
                <form id="logout-form" action="/user/logout" method="get" className="d-none">
                    {/* CSRF Token */}
                    <input type="hidden" name="_token" value={window.CSRFToken} />
                </form>
            </div>

            <div className="header-main shadow bg-white">
                <div className="d-flex align-items-center">
                    <div className="logo">
                        <Link href="/user/work/order/view/pdf/user/inertia/dashboard">
                            <img src="/landingPage/images/tb_logo.jpeg" alt="Logo" />
                        </Link>
                    </div>
                    <div className="border-none-vertical" style={{ marginLeft: "50px", borderLeft: "4px solid #F2F2F2" }}></div>

                    <nav className="navbar-container">
                        <div id="cssmenu">
                            <ul>
                                {user ? (
                                    <>
                                        <li>
                                            <a href="#">Work Order</a>
                                            <ul>
                                                <li>
                                                    <a href="#">New</a>
                                                    <ul>
                                                        <li>
                                                            <Link href={`/user/work/order/new/${1}`} data-action="service">Service</Link>
                                                        </li>
                                                        <li><Link href={`/user/work/order/new/${2}`} data-action="project">Project</Link></li>
                                                        <li><Link href={`/user/work/order/new/${3}`} data-action="install">Install</Link></li>
                                                    </ul>
                                                </li>
                                                <li><Link href="/user/work/order/view/pdf/user/inertia/dashboard">Search</Link></li>
                                                <li><Link href="/user/dashboard">Home</Link></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">Site</a>
                                            <ul>
                                                <CreateSiteModal onSuccessMessage={onSuccessMessage} />
                                                <SearchSiteModal onSuccessMessage={onSuccessMessage} />
                                                <ImportSite onSuccessMessage={onSuccessMessage} />
                                            </ul>
                                        </li>
                                        <li><a href="#">Field Techs</a>
                                            <ul>
                                                <CreateTechModal onSuccessMessage={onSuccessMessage} />
                                                <SearchTechnicianModal onSuccessMessage={onSuccessMessage} />
                                                <ZipSearchModal onSuccessMessage={onSuccessMessage} />
                                                <DistanceSearchModal onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage} />
                                            </ul>
                                        </li>
                                        <li><a href="#">Create Sub Ticket</a>
                                            <ul>
                                                <li><a href="#">Create</a></li>
                                                <li><a href="#">Sub Ticket</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Estimator</a>
                                            <ul>
                                                <li><a href="#">New</a></li>
                                                <li><a href="#">Search</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Parts</a>
                                            <ul>
                                                <li><a href="#">New Parts</a></li>
                                                <li><a href="#">Search Part</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Customer</a>
                                            <ul>
                                                <CreateCustomerModal onSuccessMessage={onSuccessMessage} />
                                                <SearchCustomerModal onSuccessMessage={onSuccessMessage} />
                                            </ul>
                                        </li>
                                        <li><a href="#">Quotes</a>
                                            <ul>
                                                <li><a href="#">New Quotes</a></li>
                                                <li><a href="#">Search Quotes</a></li>
                                            </ul>
                                        </li>
                                    </>
                                ) : (
                                    <>
                                        <li><Link to="/user/login">Login</Link></li>
                                        <li><Link to="/user/register">Register</Link></li>
                                    </>
                                )}
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

            <div className="tab-content" id="myTabContent">
                <div className="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>
                <div className="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                <div className="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
            </div>
        </header>
    )
}

export default Header