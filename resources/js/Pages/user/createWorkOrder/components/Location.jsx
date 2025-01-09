import React, { useState } from 'react'
import AsyncSelect from 'react-select/async';

const Location = ({ data, setData, errors, locationRef }) => {

    const loadSiteOptions = async (inputValue, cus_id) => {
    
        try {
            const response = await fetch(`/api/customer-sites/${cus_id}?search=${inputValue}`);
            const json = await response.json();
    
            if (json.success && json.data) {
                return json.data.map(site => ({
                    value: site.id,
                    label: `${site.site_id} ${site.location}`,
                }));
            }
    
            return [];
        } catch (error) {
            console.error("Error fetching site:", error);
            return [];
        }
    };
    
    const [siteData, setSiteData] = useState(null);

    const handleSelect = async (selectedOption) => {
        setData({ ...data, site_id: selectedOption })
        
        try {
            const response = await fetch(`/api/single-site/${selectedOption}`);
            const json = await response.json();
            
            if (json.success && json.data) {
                setSiteData(json.data)
            }

            return [];
        } catch (error) {
            console.error('Error fetching employees:', error);
            return [];
        }
    }
    
    let latitude = 34.9776679;
    let longitude = -120.4379281;
    const coordinates = siteData?.co_ordinates
    if (coordinates) {
        const cleanedCoordinates = coordinates
            .replace(/POINT\(|\)/g, '')
            .split(' ');

        latitude = cleanedCoordinates[0] || latitude;
        longitude = cleanedCoordinates[1] || longitude;
    }

    const mapUrl = `https://www.google.com/maps/embed/v1/place?key=AIzaSyCZQq1GlPJb8PrwOkCiihS-tAq0qS-O1j8&q=${latitude},${longitude}`;
    

    return (
        <div ref={locationRef} className="card bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Location</h3>
            </div>
            <div className="card-body bg-white">
                <div className="">
                    <h6 htmlFor style={{ borderBottom: '0 !important' }}>Site location
                    </h6>
                    <div className="mb-4">
                        <AsyncSelect
                            cacheOptions
                            loadOptions={(inputValue) => loadSiteOptions(inputValue, data?.cus_id)} // Pass inputValue and cus_id properly
                            defaultOptions
                            defaultValue={{ label: siteData?.location, value: data?.site_id }}
                            placeholder="Search and select sites"
                            onChange={(selectedOption) => handleSelect(selectedOption?.value)}
                        />
                        {errors.site_id && <p className='text-danger mb-0'>{errors.site_id}</p>}
                    </div>

                </div>
                {
                    siteData != null &&
                    <>
                        <p className="mb-3">{siteData && siteData?.location+'; '}
                            {siteData && siteData?.address_1 + ', '}{siteData && siteData?.city + ', '} {siteData && siteData?.state + ', '} {siteData?.zipcode}</p>
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
        </div>
    )
}

export default Location