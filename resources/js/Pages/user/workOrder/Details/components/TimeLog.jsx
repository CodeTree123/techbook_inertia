import { useForm } from '@inertiajs/react';
import { DateTime } from 'luxon';
import React, { useState } from 'react'

const TimeLog = ({ id, details, onSuccessMessage }) => {

    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({
        'check_in': '',
        'check_out': '',
        'date': '',
    });

    const deleteLog = (e, logId) => {
        e.preventDefault();

        deleteItem(route('user.wo.deleteLog', logId), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Checkin/out time deleted successfully');
            }
        });
    };

    const [editingRow, setEditingRow] = useState(null);

    const handleEdit = (index) => {
        setEditingRow(index);
        setData(null)
    }

    const updateLog = (e, logId) => {
        e.preventDefault();

        post(route('user.wo.logCheckinout', logId), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Checkin/out time updated');
                setEditingRow(null);
                setData(null)
            },
            onError: (error) => {
                console.error('Error updating part:', error);
            }
        });
    };

    const formatDateToISO = (date) => {
        const [month, day, year] = date.split('/');
        const fullYear = `20${year}`; // Ensure the year is in full format (e.g., 2024 instead of 24)
        return `${fullYear}-${month}-${day}`;
      };

    return (
        <div className="card bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: "20px", fontWeight: 600 }}>Time log</h3>
            </div>
            <div className="card-body bg-white">
                {details.check_in_out.map((check, index) => {
                    const checkIn = check.check_in; // Example: "03:11:07"
                    const checkOut = check.check_out; // Example: "05:15:09" or null

                    const calculateTimeDifference = (checkIn, checkOut) => {
                        const checkInTime = new Date(`1970-01-01T${checkIn}Z`);
                        const checkOutTime = new Date(`1970-01-01T${checkOut}Z`);
                        const totalMinutes = Math.floor((checkOutTime - checkInTime) / (1000 * 60)); // Convert to minutes

                        const hours = Math.floor(totalMinutes / 60); // Calculate hours
                        const minutes = totalMinutes % 60; // Calculate remaining minutes

                        // Format the output
                        return `${hours > 0 ? hours + (hours === 1 ? " hour " : " hours ") : ""}${minutes > 0 ? minutes + (minutes === 1 ? " minute" : " minutes") : "0 minutes"
                            }`.trim();
                    };

                    const timeDifference = calculateTimeDifference(checkIn, checkOut);

                    const calculateTimeDifferenceNow = (checkIn, timezoneKey) => {
                        const timezoneMap = {
                            'PT': 'America/Los_Angeles',
                            'MT': 'America/Denver',
                            'CT': 'America/Chicago',
                            'ET': 'America/New_York',
                            'AKT': 'America/Anchorage',
                            'HST': 'Pacific/Honolulu',
                        };

                        const timeZoneName = timezoneMap[timezoneKey] || 'UTC';

                        // Get the current date using JavaScript's Date object
                        const currentDate = new Date().toISOString().slice(0, 10); // Get YYYY-MM-DD format

                        const now = DateTime.now().setZone(timeZoneName);
                        const checkInDateTime = DateTime.fromISO(`${currentDate}T${checkIn}`, { zone: timeZoneName });

                        const diff = now.diff(checkInDateTime, ['hours', 'minutes']);

                        return `${diff.hours} hours ${diff.minutes.toFixed(0)} minutes`;
                    };

                    // Example usage
                    const timezoneKey = details?.site?.time_zone; // This is dynamically passed
                    const timeDifferenceNow = calculateTimeDifferenceNow(checkIn, timezoneKey);



                    return (
                        <form
                            key={check.id}
                            onSubmit={(e) => updateLog(e, check.id)}
                            className="py-3 mb-3 mx-1 row flex-row card action-cards"
                            style={{ backgroundColor: "#E3F2FD" }}
                        >
                            <div className="col-12 pb-3 mb-3 border-bottom d-flex justify-content-between align-items-center">
                                {
                                    check.check_out ?
                                        <span>Total Hours: {timeDifference} ≈ {check.total_hours + ' hours'}</span> :
                                        <span>Total Hours: {timeDifferenceNow}</span>
                                }

                                <div className="d-flex action-group gap-2">
                                    {
                                        editingRow != index &&
                                        <button onClick={() => handleEdit(index)} type="button" className="btn edit-btn">
                                            <i className="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </button>
                                    }
                                    {
                                        editingRow == index &&
                                        <button type="submit" className="btn btn-success fw-bold" style={{ height: "max-content" }}>
                                            Save
                                        </button>
                                    }
                                    {
                                        editingRow == index &&
                                        <button type="button" onClick={() => setEditingRow(null)} className="btn btn-danger fw-bold" style={{ height: "max-content" }}>
                                            Cancel
                                        </button>
                                    }
                                    {
                                        editingRow != index &&
                                        <button
                                            type="button"
                                            onClick={(e) => deleteLog(e, check.id)}
                                            className="btn"
                                            style={{ height: "max-content" }}
                                        >
                                            <i className="fa-solid fa-trash text-danger" aria-hidden="true"></i>
                                        </button>
                                    }

                                </div>
                            </div>
                            <div className="col-6 border-end">
                                <p>
                                    Checked in at{" "}
                                    <span className="nrml-txt">
                                        {(() => {
                                            const checkInTime = check.check_in;
                                            const today = new Date().toISOString().split('T')[0];
                                            const checkInDateTime = `${today}T${checkInTime}`;
                                            const formattedTime = new Date(checkInDateTime).toLocaleTimeString();
                                            return formattedTime;
                                        })()}
                                    </span>

                                </p>
                                {check.checkin_note && (
                                    <p style={{ fontWeight: 300, fontSize: "14px" }}>{check.checkin_note}</p>
                                )}
                                {
                                    editingRow == index &&
                                    <input type="time" defaultValue={check.check_in} className="mb-2" name="check_in" onChange={(e) => setData({ ...data, check_in: e.target.value })} />
                                }

                                <p>
                                    Date <span className="nrml-txt">{new Date(check.date).toLocaleDateString()}</span>
                                </p>
                                {
                                    editingRow == index &&
                                    <input
                                        type="date"
                                        defaultValue={formatDateToISO(check.date)}
                                        className="mb-2"
                                        name="date"
                                        onChange={(e) => setData({ ...data, date: e.target.value })}
                                    />
                                }
                            </div>
                            <div className="col-6 ps-3">
                                <p>
                                    {check.check_out ? (
                                        <>
                                            Checked out at{" "}
                                            <span className="nrml-txt">{(() => {
                                                const checkInTime = check.check_out;
                                                const today = new Date().toISOString().split('T')[0];
                                                const checkInDateTime = `${today}T${checkInTime}`;
                                                const formattedTime = new Date(checkInDateTime).toLocaleTimeString();
                                                return formattedTime;
                                            })()}</span>
                                        </>
                                    ) : (
                                        "Not checked out yet"
                                    )}
                                </p>
                                {check.checkout_note && (
                                    <p style={{ fontWeight: 300, fontSize: "14px" }}>{check.checkout_note}</p>
                                )}
                                {
                                    editingRow == index &&
                                    <input type="time" defaultValue={check.check_out || ""} className="" name="check_out" onChange={(e) => setData({ ...data, check_out: e.target.value })} />
                                }
                            </div>
                            <div className="col-12 border-top pt-2 d-flex gap-2">
                                by{" "}
                                {
                                    check?.tech_id != null ?
                                        <div className='d-flex align-items-center gap-2'>
                                            {check.engineer?.avatar ? (
                                                <img
                                                    src={`${window.location.protocol}//${window.location.host}/${check.engineer.avatar}`}
                                                    style={{
                                                        width: "30px",
                                                        height: "30px",
                                                        borderRadius: "50%",
                                                        objectFit: "cover",
                                                    }}
                                                    alt=""
                                                />
                                            ) : (
                                                <div
                                                    className="bg-primary d-flex justify-content-center align-items-center text-white"
                                                    style={{
                                                        width: "30px",
                                                        height: "30px",
                                                        borderRadius: "50%",
                                                    }}
                                                >
                                                    {check.engineer?.name.charAt(0)}
                                                </div>
                                            )}
                                            {check.engineer?.name}
                                        </div> :
                                        <div className='d-flex align-items-center gap-2'>
                                            {details.ftech_id && (
                                                <div
                                                    className="bg-primary d-flex justify-content-center align-items-center text-white"
                                                    style={{
                                                        width: "30px",
                                                        height: "30px",
                                                        borderRadius: "50%",
                                                    }}
                                                >
                                                    {details?.technician?.company_name.charAt(0)}
                                                </div>
                                            )}
                                            {details?.technician?.company_name}
                                        </div>
                                }

                            </div>
                        </form>
                    );
                })}
            </div>
        </div>
    )
}

export default TimeLog