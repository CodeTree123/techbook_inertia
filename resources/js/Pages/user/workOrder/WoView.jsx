import React, { useEffect, useRef, useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import "../../../../css/wo.css"
import AddHold from './components/AddHold';
import MakeCancel from './components/MakeCancel';
import NextStatus from './components/NextStatus';
import BackStatus from './components/BackStatus';
import WorkOrderTab from './components/WorkOrderTab';
import Reschedule from './components/Reschedule';
import MainLayout from '../layout/MainLayout';
import { DateTime } from 'luxon';
export default function WoView({ wo }) {
  console.log(wo);

  const [latestAtRiskScheduleId, setLatestAtRiskScheduleId] = useState(null);

  const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
    atRisk: false
  });


  const hasRun = useRef(false);

  useEffect(() => {
    if (!hasRun.current && wo.stage == 3) {
      post(route('user.wo.goAtRisk', wo.id), {
        preserveScroll: true,
        onSuccess: () => {
          hasRun.current = true;
        }
      });
    }
  })

  useEffect(() => {
    const timezoneMap = {
      'PT': 'America/Los_Angeles',
      'MT': 'America/Denver',
      'CT': 'America/Chicago',
      'ET': 'America/New_York',
      'AKT': 'America/Anchorage',
      'HST': 'Pacific/Honolulu',
    };

    const selectedTimezone = timezoneMap[wo?.site?.time_zone]; // Get the selected timezone

    const now = DateTime.now().setZone(selectedTimezone);

    if (wo.schedule_type === 'single') {
      const schedule = wo?.schedules?.[0] || null;
      if (schedule) {
        setLatestAtRiskScheduleId(schedule.id); // Store the latest schedule ID

        // Convert the schedule date and time to Luxon DateTime in the selected time zone
        const scheduleDateTime = `${schedule.on_site_by}T${schedule.scheduled_time}`; // Format the date and time string
        const scheduleLuxonDateTime = DateTime.fromISO(scheduleDateTime, { zone: 'utc' }) // Parse as UTC
          .setZone(selectedTimezone, { keepLocalTime: true });

        // Compare the two times in the selected time zone
        if (Array.isArray(wo?.check_in_out) && wo?.check_in_out.length > 0) {
          setData({ atRisk: false });
        } else if (scheduleLuxonDateTime <= now) {
          setData({ atRisk: true });
        } else {
          setData({ atRisk: false });
        }
      }

    } else {
      let latestRiskId = null; // Track the ID of the latest at-risk schedule
      let lastCheckInTime = null; // Track the most recent valid check-in time

      const isValidSchedule = wo?.schedules?.every((schedule, index, schedules) => {
        const scheduleDateTime = `${schedule.on_site_by}T${schedule.scheduled_time}`;
        const scheduleLuxonDateTime = DateTime.fromISO(scheduleDateTime, { zone: 'utc' })
          .setZone(selectedTimezone, { keepLocalTime: true });

        // Check if the schedule is in the future
        if (scheduleLuxonDateTime > now) {
          return true; // Future schedules are always valid
        }

        // For the first schedule or if there's no previous schedule, we don't check the previous schedule time
        const previousScheduleTime = index > 0 ? schedules[index - 1].scheduled_time : null;

        if (previousScheduleTime) {
          const previousScheduleDateTime = `${schedule.on_site_by}T${previousScheduleTime}`;
          const previousScheduleLuxonDateTime = DateTime.fromISO(previousScheduleDateTime, { zone: 'utc' })
            .setZone(selectedTimezone, { keepLocalTime: true });

          // Check if the check-in falls between the previous schedule's time and the current one
          const hasValidCheckIn = wo?.check_in_out?.some((checkInOut) => {
            const rawDate = checkInOut.date; // "12/23/24"
            const rawTime = checkInOut.check_in; // "06:12:18"

            // Parse date string into Luxon format (MM/DD/YY to YYYY-MM-DD)
            const parsedDate = DateTime.fromFormat(rawDate, 'MM/dd/yy');
            if (!parsedDate.isValid) {
              console.error('Invalid Date:', parsedDate.invalidExplanation);
              return false;
            }

            // Combine parsed date and raw time into ISO format
            const isoString = `${parsedDate.toISODate()}T${rawTime}`;
            console.log('ISO String:', isoString);

            // Parse combined string with Luxon
            const checkInLuxonDateTime = DateTime.fromISO(isoString, { zone: 'utc' });
            if (!checkInLuxonDateTime.isValid) {
              console.error('Invalid Check-In DateTime:', checkInLuxonDateTime.invalidExplanation);
              return false;
            }

            // Convert to selected timezone
            const adjustedCheckInTime = checkInLuxonDateTime.setZone(selectedTimezone, { keepLocalTime: true });
            console.log('Adjusted Check-In Time:', adjustedCheckInTime.toISO());

            // Ensure the check-in falls within the time window
            return adjustedCheckInTime > previousScheduleLuxonDateTime && adjustedCheckInTime <= scheduleLuxonDateTime;
          });

          if (!hasValidCheckIn) {
            latestRiskId = schedule.id; // Track the latest at-risk schedule
          }

          return hasValidCheckIn; // A schedule is valid only if there's a valid check-in
        }

        return true; // If no previous schedule, continue without validation for the first schedule
      });

      // Update the state based on the validation results

      setData({ atRisk: !isValidSchedule })
      setLatestAtRiskScheduleId(latestRiskId);

    }

  }, [wo, setData, setLatestAtRiskScheduleId]);

  const getStage = () => {
    if (wo?.stage === 7) {
      return <span className="fw-bold">Cancelled</span>;
    }

    if (wo?.is_hold === 0) {
      switch (wo?.stage) {
        case 1:
          return <span className="fw-bold">New</span>;
        case 2:
          return <span className="fw-bold">Need Dispatch</span>;
        case 3:
          return <span className="fw-bold">Dispatched</span>;
        case 4:
          return <span className="fw-bold">Closed</span>;
        case 5:
          return <span className="fw-bold">Billing</span>;
        default:
          return null;
      }
    }

    return <span className="fw-bold">On Hold</span>;
  };

  const currentDateTime = new Date();

  let scheduledTime;

  const upcomingSchedule = wo?.schedules.find(schedule => {
    const scheduledDate = new Date(schedule.on_site_by);
    scheduledTime = new Date(
      scheduledDate.getFullYear(),
      scheduledDate.getMonth(),
      scheduledDate.getDate(),
      schedule.scheduled_time.split(':')[0],
      schedule.scheduled_time.split(':')[1]
    );

    return scheduledTime > currentDateTime;
  });

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
      <MainLayout>
        <Head title={wo?.order_id + ' | Techbook'} />
        <div className="container-fluid total-bg">
          <div className="row">

            <div className="col-4 border-end border-bottom px-3 py-2" >
              <h2 classname="fw-bold" style={{ fontSize: 24 }}>#{wo.order_id}
              </h2>
              <div className="d-flex align-items-center gap-2">
                {getStage()}
                {
                  wo.status === 1 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Pending
                    </span>
                  )
                }

                {
                  wo.status === 2 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Contacted
                    </span>
                  )
                }

                {
                  wo.status === 3 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Confirmed
                    </span>
                  )
                }

                {
                  wo.status === 4 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      At Risk
                    </span>
                  )
                }

                {
                  wo.status === 5 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Delayed
                    </span>
                  )
                }

                {
                  wo.status === 6 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      On Hold
                    </span>
                  )
                }

                {
                  wo.status === 7 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      En Route
                    </span>
                  )
                }

                {
                  wo.status === 8 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Checked-In
                    </span>
                  )
                }

                {
                  wo.status === 9 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Checked-Out
                    </span>
                  )
                }

                {
                  wo.status === 10 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Needs Approval
                    </span>
                  )
                }

                {
                  wo.status === 11 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Issue
                    </span>
                  )
                }

                {
                  wo.status === 12 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Approved
                    </span>
                  )
                }

                {
                  wo.status === 13 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Invoiced
                    </span>
                  )
                }

                {
                  wo.status === 14 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Past Due
                    </span>
                  )
                }

                {
                  wo.status === 15 && (
                    <span
                      className="badge"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '0.5rem',
                        height: 'max-content',
                        color: '#148E6F',
                        backgroundColor: '#1FE1AF75',
                      }}
                    >
                      <div
                        style={{
                          width: '10px',
                          height: '10px',
                          borderRadius: '50%',
                          backgroundColor: '#148E6F',
                        }}
                      ></div>
                      Paid
                    </span>
                  )
                }

              </div>
              <p style={{ color: '#808080' }} className="mb-0 text-start">Purchase Order : #{wo.p_o}</p>
            </div>

            <div className="col-4 border-end border-bottom px-3 py-2">
              <h2 className="fw-bold text-center" style={{ fontSize: 24 }}>{wo?.customer?.company_name} ({wo?.customer?.customer_id})</h2>
              <p style={{ color: '#808080' }} className="mb-0">Purchase Order : #{wo.p_o}</p>
              <p style={{ color: '#808080' }} className="mb-0">Problem Code : #595</p>
              <p style={{ color: '#808080' }} className="mb-0">Resolution Code : #59552</p>
            </div>
              <div className="d-flex justify-content-center align-items-center gap-2">
                <i className="fa-solid fa-location-dot" style={{ fontSize: 16, color: '#00BABA' }} aria-hidden="true" />
                <h2 className="mb-0 fw-light" style={{ fontSize: 16 }}> Location : {wo?.site ? wo?.site?.location : <span className='fw-light'>No Location Added Yet</span>}</h2>
              </div>
              <p style={{ color: '#808080' }} className='text-center'>Site: {wo?.site && wo?.site?.address_1 + ';'} {wo?.site && wo?.site?.city + ','}
                {wo?.site?.state} {wo?.site?.zipcode}</p>
            </div>

            {
              wo.schedule_type == 'single' ?

                <div className="col-4 border-end border-bottom px-3 py-2">
                  <p className="fw-bold mb-0 text-end" style={{ fontSize: '24px' }}>Schedule</p>
                  {wo.schedules[0] ? (
                    <p className='text-end'>{
                      DateTime.fromISO(wo.schedules[0].on_site_by).toFormat('MM-dd-yy')
                    } at {DateTime.fromISO(wo.schedules[0].scheduled_time).toFormat('hh:mm a')}</p>
                  ) : (
                    <p className='text-end'>No upcoming schedules.</p>
                  )}
                </div> :

                wo.check_in_out?.[0]?.check_in ? (
                  <div className="col-4 border-end border-bottom px-3 py-2">
                    <p className="fw-bold mb-0 text-end" style={{ fontSize: '24px' }}>Time Logged</p>
                    <p style={{ color: '#808080' }} className='text-end'>
                      {wo?.check_in_out.reduce((sum, item) => {
                        const hours = Number(item?.total_hours) || 0; // Default to 0 if total_hours is not a valid number
                        return sum + hours;
                      }, 0)} Hours
                    </p>
                  </div>
                ) : (
                  <div className="col-4 border-end border-bottom px-3 py-2">
                    <p className="fw-bold mb-0 text-end" style={{ fontSize: '16px' }}>Scheduled Time</p>
                    {upcomingSchedule ? (
                      <p className='text-end'>{
                        DateTime.fromISO(upcomingSchedule.on_site_by).toFormat('MM-dd-yy')
                      } at {scheduledTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })}</p>
                    ) : (
                      <p className='text-end'>No upcoming schedules.</p>
                    )}
                  </div>
                )
            }

            <div className="col-4 border-end border-bottom px-3 py-2">
              <div className="d-flex justify-content-start align-items-center gap-2">
                <i className="fa-solid fa-circle-user" style={{ fontSize: 25 }} aria-hidden="true" />
                <h2 className="fw-bold" style={{ fontSize: 24 }}>Field Tech</h2>
              </div>
              <p style={{ color: '#808080' }}>{wo?.technician && wo?.technician?.company_name + ';'} <br />
                {wo?.technician && ' ID :' + wo?.technician?.technician_id}</p>
              {
                wo?.technician?.phone && (
                  <a href={`callto:${wo?.technician?.phone}`}>
                    <i className="fa-solid fa-phone" style={{ fontSize: '14px' }}></i> {wo?.technician?.phone}
                  </a>
                )
              }
              <br />
              {
                wo?.technician?.email && (
                  <a href={`mailto:${wo?.technician?.email}`}>
                    <i className="fa-regular fa-envelope"></i> {wo?.technician?.email}
                  </a>
                )
              }


            </div>

            <div className="col-4 border-end border-bottom px-3 py-2">
              <h2 className="text-center" style={{ fontSize: 12, fontWeight: 600 }}>WO Manager</h2>
              <p className="text-center">{wo?.employee?.name}</p>
              <p style={{ fontSize: 12, fontWeight: 600 }} className="text-center mb-0">Requested By</p>
              <p className="text-center pb-3">{wo.requested_by}</p>
              {
                wo?.order_type === 1 && (
                  <p className="text-divider">
                    <span style={{ fontWeight: '600', backgroundColor: 'rgba(248, 249, 250, 1)' }}>Service</span>
                  </p>
                )
              }
              {
                wo?.order_type === 2 && (
                  <p className="text-divider">
                    <span style={{ fontWeight: '600', backgroundColor: 'rgba(248, 249, 250, 1)' }}>Project</span>
                  </p>
                )
              }
              {
                wo?.order_type === 3 && (
                  <p className="text-divider">
                    <span style={{ fontWeight: '600', backgroundColor: 'rgba(248, 249, 250, 1)' }}>Install</span>
                  </p>
                )
              }
            </div>

            <div className="col-4 border-end border-bottom px-3 py-2">
              <div className='d-flex flex-column align-items-end'>
                <div className='w-100 pb-4'>
                  <h4 style={{ fontSize: '24px' }} className='fw-bold text-end'>Problem Code</h4>
                  <p style={{ color: '#808080' }} className="mb-0 text-end"> #595</p>
                </div>
                <hr className='w-25 my-0'/>
                <div className='w-100 pt-4'>
                  <h4 style={{ fontSize: '24px' }} className='fw-bold text-end'>Resolution Code</h4>
                  <p style={{ color: '#808080' }} className="mb-0 text-end"> #59552</p>
                </div>
              </div>
              <p style={{ color: '#808080' }}>Site: {wo?.site && wo?.site?.address_1+';'} {wo?.site && wo?.site?.city+','}
                {wo?.site?.state} {wo?.site?.zipcode}</p>
            </div>

            {
              wo.schedule_type == 'single' ?

                <div className="col-3 border-end border-bottom px-3 py-2">
                  <p className="fw-bold mb-0" style={{ fontSize: '16px' }}>Schedule</p>
                  {wo.schedules[0] ? (
                    <p>{
                      DateTime.fromISO(wo.schedules[0].on_site_by).toFormat('MM-dd-yy')
                    } at {DateTime.fromISO(wo.schedules[0].scheduled_time).toFormat('hh:mm a')}</p>
                  ) : (
                    <p>No upcoming schedules.</p>
                  )}
                </div> :

                wo.check_in_out?.[0]?.check_in ? (
                  <div className="col-3 border-end border-bottom px-3 py-2">
                    <p className="fw-bold mb-0" style={{ fontSize: '16px' }}>Time Logged</p>
                    <p style={{ color: '#808080' }}>
                      {wo?.check_in_out.reduce((sum, item) => {
                        const hours = Number(item?.total_hours) || 0; // Default to 0 if total_hours is not a valid number
                        return sum + hours;
                      }, 0)} Hours
                    </p>
                  </div>
                ) : (
                  <div className="col-3 border-end border-bottom px-3 py-2">
                    <p className="fw-bold mb-0" style={{ fontSize: '16px' }}>Scheduled Time</p>
                    {upcomingSchedule ? (
                      <p>{
                        DateTime.fromISO(upcomingSchedule.on_site_by).toFormat('MM-dd-yy')
                      } at {scheduledTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })}</p>
                    ) : (
                      <p>No upcoming schedules.</p>
                    )}
                  </div>
                )

            }



            <div className="col-3 border-end border-bottom px-3 py-2">
              <p className="fw-bold mb-0 text-center" style={{ fontSize: 16 }}>Support Ticket</p>
            </div>

            <div className="col-2 d-flex gap-1 px-3 py-4">
              <AddHold id={wo?.id} onSuccessMessage={handleSuccessMessage} stage={wo.is_hold} is_cancelled={wo.stage == 7} />
              <MakeCancel id={wo?.id} onSuccessMessage={handleSuccessMessage} is_cancelled={wo.stage == 7} />
            </div>

            <div className="col-8 px-3 py-4">
              {wo.stage !== 7 ? (
                <div className="btn-group w-100" role="group" aria-label="Basic example">
                  <button
                    type="button"
                    className={`btn stage ${wo.is_hold === 1 ? 'bg-secondary' : (wo.stage === 1 || wo.stage === 2 || wo.stage === 3 || wo.stage === 4 || wo.stage === 5) ? 'stage-primary' : ''} w-100`}
                  >
                    NEW
                  </button>
                  <button
                    type="button"
                    className={`btn stage ${wo.is_hold === 1 ? 'bg-secondary' : (wo.stage === 2 || wo.stage === 3 || wo.stage === 4 || wo.stage === 5) ? 'stage-primary' : ''} w-100`}
                  >
                    Needs Dispatch
                  </button>
                  <button
                    type="button"
                    className={`btn stage ${wo.is_hold === 1 ? 'bg-secondary' : (wo.stage === 3 || wo.stage === 4 || wo.stage === 5) ? 'stage-primary' : ''} w-100`}
                  >
                    Dispatched
                  </button>
                  <button
                    type="button"
                    className={`btn stage ${wo.is_hold === 1 ? 'bg-secondary' : (wo.stage === 4 || wo.stage === 5) ? 'stage-primary' : ''} w-100`}
                  >
                    Closed
                  </button>
                  <button
                    type="button"
                    className={`btn stage ${wo.is_hold === 1 ? 'bg-secondary' : (wo.stage === 5) ? 'stage-primary' : ''} w-100`}
                  >
                    Billings
                  </button>
                </div>
              ) : (
                <div className="alert alert-danger mb-0" style={{ paddingTop: '0.375rem', paddingBottom: '0.375rem' }} role="alert">
                  <i>Note: {wo.cancelling_note}</i>
                </div>
              )}

              {wo.holding_note && wo.is_hold === 1 && (
                <div className="alert alert-dark mb-0 mt-2" style={{ paddingTop: '0.375rem', paddingBottom: '0.375rem' }} role="alert">
                  <i>Note: {wo.holding_note}</i>
                </div>
              )}

              {
                wo.stage == 7 && wo.holding_note &&
                <div className="alert alert-dark mb-0 mt-2" style={{ paddingTop: '0.375rem', paddingBottom: '0.375rem' }} role="alert">
                  <i>Note: xcfvbxcxcv</i>
                </div>
              }

            </div>

            <div className="col-2 d-flex gap-1 justify-content-end px-3 py-4">
              <a target='_blank' href={`${window.location.protocol}//${window.location.host}/pdf/work/order/view/${wo.id}`} className="btn" style={{ backgroundColor: '#AFE1AF', height: 'max-content' }} id="woViewButton">
                <i className="fa fa-eye" aria-hidden="true" />
              </a>
              <BackStatus id={wo.id} onSuccessMessage={handleSuccessMessage} status={wo.status} is_ftech={wo.ftech_id} is_cancelled={wo.stage == 7} />
              <NextStatus id={wo.id} onSuccessMessage={handleSuccessMessage} onErrorMessage={handleErrorMessage} status={wo.status} is_ftech={wo.ftech_id} is_cancelled={wo.stage == 7} />
            </div>

            {
              wo.status == 4 &&
              <Reschedule id={latestAtRiskScheduleId} is_ftech={wo.ftech_id} scheduleData={wo.schedules.find(schedule => schedule.id === latestAtRiskScheduleId)} onSuccessMessage={handleSuccessMessage} onErrorMessage={handleErrorMessage} is_cancelled={wo.stage == 7} />
            }



            <div className='col-12 px-3 py-4'>
              <WorkOrderTab id={wo.id} details={wo} onSuccessMessage={handleSuccessMessage} onErrorMessage={handleErrorMessage} />
            </div>
          </div>
        </div>
      </MainLayout>

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


  );
}