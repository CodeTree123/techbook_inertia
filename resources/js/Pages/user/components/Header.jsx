import { Link, usePage } from '@inertiajs/react';
import React from 'react'
import '../../../../css/header.css'
import CreateSiteModal from './Site/CreateSiteModal';
import SearchSiteModal from './Site/SearchSiteModal';

const Header = ({onSuccessMessage, onErrorMessage}) => {
    const { user } = usePage().props;
    return (
        <header className="fixed-top">
            <div className="p-1 d-flex justify-content-end" style={{ background: "#AFE1AF", position: "relative" }}>
                <div className="dropdown">
                    <button
                        className="btn btn-outline-light shadow-circle text-dark"
                        style={{ marginLeft: "10px" }}
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i className="fas fa-user-circle text-success" style={{ marginRight: "10px" }}></i>
                        {/** Authenticated User Fullname */}
                        {user ? user.firstname + ' ' + user.lastname : "User"}
                    </button>
                    <ul className="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><Link className="dropdown-item" to="/user/profile/setting">Profile Setting</Link></li>
                        <li><Link className="dropdown-item mt-3" to="/user/twofactor">2FA Security</Link></li>
                        <li><Link className="dropdown-item mt-3" to="/user/change/password">Change Password</Link></li>
                        <hr />
                        <li>
                            <a
                                className="dropdown-item mt-3 text-danger"
                                href="/user/logout"
                                onClick={(e) => {
                                    e.preventDefault();
                                    document.getElementById('logout-form').submit();
                                }}
                            >
                                Logout
                            </a>
                            <form id="logout-form" action="/user/logout" method="get" className="d-none">
                                {/* CSRF Token */}
                                <input type="hidden" name="_token" value={window.CSRFToken} />
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <div className="header-main shadow bg-white">
                <div className="d-flex align-items-center">
                    <div className="logo">
                        <Link href="/user/dashboard">
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
                                                        <li><a href="#" data-action="service">Service</a></li>
                                                        <li><a href="#" data-action="project">Project</a></li>
                                                        <li><a href="#" data-action="install">Install</a></li>
                                                    </ul>
                                                </li>
                                                <li><Link href="/user/work/order/view/pdf/user/inertia/dashboard">Search</Link></li>
                                                <li><Link href="/user/dashboard">Home</Link></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">Site</a>
                                            <ul>
                                                <CreateSiteModal onSuccessMessage={onSuccessMessage}/>
                                                <SearchSiteModal onSuccessMessage={onSuccessMessage}/>
                                                <li><a href="#">Import</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Field Techs</a>
                                            <ul>
                                                <li><a href="#">New</a></li>
                                                <li><a href="#">Search</a></li>
                                                <li><a href="#">Zip Code</a></li>
                                                <li><a href="#">Distance Search</a></li>
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
                                                <li><a href="#">New Customer</a></li>
                                                <li><a href="#">Search Customer</a></li>
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