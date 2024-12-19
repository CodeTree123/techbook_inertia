import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import AsyncSelect from 'react-select/async'

const Location = ({ id, details, onSuccessMessage }) => {

    const loadSiteOptions = async (inputValue) => {
        try {
            const response = await fetch(`/api/all-sites?search=${inputValue}`);
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
        setEditable(true);
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

    return (
        <form onSubmit={(e)=>submit(e)} className="card bg-white shadow-lg border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Location</h3>
                <div className="d-flex action-group gap-2">
                    {
                        !editable &&
                        <button type='button' onClick={(e) => handleEdit(e)} className="btn edit-btn">
                            <i className="fa-solid fa-pen-to-square" aria-hidden="true" />
                        </button>
                    }
                    {
                        editable &&
                        <button type='submit' className="btn btn-success fw-bold">
                            Save
                        </button>
                    }
                    {
                        editable &&
                        <button type='button' onClick={() => setEditable(false)} className="btn btn-danger fw-bold">
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
                                defaultValue={{ label: details.site.location, value: details.site_id }}
                                placeholder="Search and select sites"
                                onChange={(selectedOption) => setData({ ...data, site_id: selectedOption?.value })}
                            />
                        </div>
                    }

                </div>
                <p className="mb-0">{details?.site?.location} &amp;
                    {details?.site?.address_1},</p>
                <p className="mb-0">{details?.site?.city}, {details?.site?.state}, </p>
                <p className="mb-0">{details?.site?.zipcode}</p>
                {/* 
                                  <iframe
                                      src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCZQq1GlPJb8PrwOkCiihS-tAq0qS-O1j8&q=35.0399322,-85.3071289"
                                      width="100%"
                                      height="450"
                                      style="border:0;"
                                      allowfullscreen=""
                                      loading="lazy">
                                  </iframe> */}
            </div>
        </form>

    )
}

export default Location