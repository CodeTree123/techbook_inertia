import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import AsyncSelect from 'react-select/async'

const Location = ({ id, details, onSuccessMessage, onErrorMessage, is_cancelled }) => {

    const loadSiteOptions = async (inputValue) => {
        try {
            const response = await fetch(`/api/customer-sites/${details.slug}?search=${inputValue}`);
            const json = await response.json();

            if (json.success && json.data) {
                return json.data.map(employee => ({
                    value: employee.id,
                    label: employee.site_id + ' ' + employee.location,
                }));
            }

            return []; // Return an empty array if no data is available
        } catch (error) {
            console.error('Error fetching employees:', error);
            return [];
        }
    };

    const [editable, setEditable] = useState(false)

    const handleEdit = (e) => {
        e.preventDefault();
        if (!details.slug) {
            onErrorMessage('Add Customer First')
        } else {
            setEditable(true);
        }

    }

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        'site_id': details?.site_id,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('user.wo.updateSiteInfo', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Site Informations Updated Successfully');
                setEditable(false);
            }
        });
    };


    let latitude = 34.9776679;
    let longitude = -120.4379281;
    const coordinates = details?.site?.co_ordinates
    if (coordinates) {
        const cleanedCoordinates = coordinates
            .replace(/POINT\(|\)/g, '')
            .split(' ');

        latitude = cleanedCoordinates[0] || latitude; // Fallback to default if undefined
        longitude = cleanedCoordinates[1] || longitude; // Fallback to default if undefined
    }

    const mapUrl = `https://www.google.com/maps/embed/v1/place?key=AIzaSyCZQq1GlPJb8PrwOkCiihS-tAq0qS-O1j8&q=${latitude},${longitude}`;

    return (
        <form onSubmit={(e) => submit(e)} className="card bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Location</h3>
                <div className="d-flex action-group gap-2">
                    {
                        !editable && details?.site &&
                        <button type='button' onClick={(e) => handleEdit(e)} className="btn border-0 edit-btn" disabled={is_cancelled}>
                            <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                        </button>
                    }
                    {
                        editable &&
                        <button type='submit' className="btn border-0 btn-success fw-bold" disabled={is_cancelled}>
                            Save
                        </button>
                    }
                    {
                        editable &&
                        <button type='button' onClick={() => setEditable(false)} className="btn border-0 btn-danger fw-bold" disabled={is_cancelled}>
                            Cancel
                        </button>
                    }

                </div>
            </div>
            <div className="card-body bg-white">
                <div className="">
                    <h6 htmlFor style={{ borderBottom: '0 !important' }}>Site location
                    </h6>
                    {
                        editable &&
                        <div className="mb-4">
                            <AsyncSelect
                                cacheOptions
                                loadOptions={loadSiteOptions}
                                defaultOptions
                                defaultValue={{ label: details?.site?.location, value: details?.site_id }}
                                placeholder="Search and select sites"
                                onChange={(selectedOption) => setData({ ...data, site_id: selectedOption?.value })}
                            />
                        </div>
                    }

                    {
                        !details?.site_id && !editable &&
                        <button className='btn btn-outline-dark' onClick={(e) => handleEdit(e)}>+ Add Site</button>
                    }

                </div>
                {
                    details?.site_id != null &&
                    <>
                        <p className="mb-0">{details?.site?.site_id}</p>
                        <p className="mb-0">{details?.site?.location + ','}</p>
                        <p className="mb-0">{details?.site && details?.site?.address_1 + ','} {details?.site && details?.site?.city + ','} {details?.site && details?.site?.state + ','} 
                        {details?.site?.zipcode}</p>
                        <iframe
                            src={mapUrl}
                            width="100%"
                            height="450"
                            style={{ border: 0 }}
                            allowFullScreen
                            loading="lazy"
                        ></iframe>
                    </>
                }

            </div>
        </form>

    )
}

export default Location