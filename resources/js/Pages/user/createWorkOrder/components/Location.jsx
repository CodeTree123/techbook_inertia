import React, { useEffect, useState } from 'react'
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
    const [mapUrl, setMapUrl] = useState('');

    const defaultLatitude = 34.9776679;
    const defaultLongitude = -120.4379281;

    const handleSelect = async (selectedOption) => {
        setData({ ...data, site_id: selectedOption });

        try {
            const response = await fetch(`/api/single-site/${selectedOption}`);
            const json = await response.json();

            if (json.success && json.data) {
                setSiteData(json.data);
            } else {
                setSiteData(null); // Reset siteData if no data
            }
        } catch (error) {
            console.error('Error fetching employees:', error);
            setSiteData(null); // Reset siteData on error
        }
    };

    useEffect(() => {
        let latitude = defaultLatitude;
        let longitude = defaultLongitude;

        if (siteData?.co_ordinates) {
            const coordinates = siteData.co_ordinates
                .replace(/POINT\(|\)/g, '')
                .split(' ');

            latitude = coordinates[0] || defaultLatitude;
            longitude = coordinates[1] || defaultLongitude;
        }

        const newMapUrl = `https://www.google.com/maps/embed/v1/place?key=AIzaSyCZQq1GlPJb8PrwOkCiihS-tAq0qS-O1j8&q=${latitude},${longitude}`;
        setMapUrl(newMapUrl);
    }, [siteData]);

    return (
        <div ref={locationRef} className="card bg-white border mb-4">
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
                        {/* <iframe
                            src={mapUrl}
                            width="100%"
                            height="450"
                            style={{ border: 0 }}
                            allowFullScreen
                            loading="lazy"
                        ></iframe> */}
                    </>
                }

            </div>
        </div>
    )
}

export default Location