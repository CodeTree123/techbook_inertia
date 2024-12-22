import React, { useEffect, useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import "../../../../css/wo.css"
import AddHold from './components/AddHold';
import MakeCancel from './components/MakeCancel';
import NextStatus from './components/NextStatus';
import BackStatus from './components/BackStatus';
import WorkOrderTab from './components/WorkOrderTab';
import Reschedule from './components/Reschedule';
export default function WoView({ wo }) {
  console.log(wo);


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

  const upcomingSchedule = wo.schedules.find(schedule => {
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

  const [atRisk, setAtRisk] = useState(true);
  const [latestAtRiskScheduleId, setLatestAtRiskScheduleId] = useState(null);

  const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
  });

  useEffect(() => {
    if (atRisk) {
      post(route('user.wo.goAtRisk', wo.id), {
        onSuccess: () => {
        }
      });
    }
  }, [wo.id]);

  useEffect(() => {
    let latestRiskId = null; // To track the latest at-risk schedule ID

    const isValidSchedule = wo?.schedules?.every((schedule) => {
      const scheduleDate = new Date(schedule.on_site_by);
      const scheduleTime = new Date(`${schedule.on_site_by}T${schedule.scheduled_time}`);
      const now = new Date();

      const hasCheckInOutBeforeTime = wo?.check_in_out?.some((checkInOut) => {
        const checkInDate = new Date(checkInOut.date);
        const checkInTime = new Date(`${checkInOut.date}T${checkInOut.check_in}`);
        return (
          checkInDate.toDateString() === scheduleDate.toDateString() &&
          checkInTime <= scheduleTime
        );
      });

      const isPastSchedule = scheduleTime <= now;

      const isValid = hasCheckInOutBeforeTime || !isPastSchedule;

      if (!isValid) {
        latestRiskId = schedule.id; // Update with the latest at-risk schedule ID
      }

      return isValid;
    });

    setAtRisk(isValidSchedule);
    setLatestAtRiskScheduleId(latestRiskId); // Update the state with the latest at-risk schedule ID
  }, [wo?.schedules, wo?.check_in_out]);


  return (

    <>
      <Head title={wo.order_id + ' | Techbook'} />
      <div className="container-fluid total-bg">
        <div className="row">

          <div className="col-2 border-end border-bottom px-3 py-2" >
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
          </div>

          <div className="col-4 border-end border-bottom px-3 py-2">
            <h2 className="fw-bold text-center" style={{ fontSize: 24 }}>{wo?.customer?.company_name}</h2>
            <p style={{ color: '#808080' }} className="mb-0">Purchase Order : #{wo.p_o}</p>
            <p style={{ color: '#808080' }} className="mb-0">Problem Code : #595</p>
            <p style={{ color: '#808080' }} className="mb-0">Resolution Code : #59552</p>
          </div>

          <div className="col-3 border-end border-bottom px-3 py-2">
            <h2 className="fw-bold text-center" style={{ fontSize: 24 }}>WO Manager</h2>
            <p className="fw-bold text-center">{wo?.employee?.name}</p>
          </div>

          <div className="col-3 border-end border-bottom px-3 py-2">
            <div className="d-flex justify-content-start align-items-center gap-2">
              <i className="fa-solid fa-circle-user" style={{ fontSize: 25 }} aria-hidden="true" />
              <h2 className="fw-bold" style={{ fontSize: 24 }}>Field Tech</h2>
            </div>
            <p style={{ color: '#808080' }}>{wo?.technician?.company_name}; ID :
              {wo?.technician?.technician_id}</p>
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

          <div className="col-2 border-end border-bottom px-3 py-2">
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
            <div className="d-flex justify-content-start align-items-center gap-2">
              <i className="fa-solid fa-location-dot" style={{ fontSize: 16, color: '#00BABA' }} aria-hidden="true" />
              <h2 className="fw-bold mb-0" style={{ fontSize: 16 }}>Location : {wo?.site?.location}</h2>
            </div>
            <p style={{ color: '#808080' }}>Site: {wo?.site?.address_1}; {wo?.site?.city},
              {wo?.site?.state} {wo?.site?.zipcode}</p>
          </div>

          {
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
                  <p>{upcomingSchedule.on_site_by} at {scheduledTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })}</p>
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
            <AddHold id={wo?.id} onSuccessMessage={handleSuccessMessage} stage={wo.is_hold} />
            <MakeCancel id={wo?.id} onSuccessMessage={handleSuccessMessage} is_cancelled={wo.stage} />
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
              wo.stage == 7 &&
              <div className="alert alert-danger mb-0" style={{ paddingTop: '0.375rem', paddingBottom: '0.375rem' }} role="alert">
                <i>Note: {wo?.cancelling_note}</i>
              </div>
            }

            {
              wo.stage == 7 && wo.holding_note &&
              <div className="alert alert-dark mb-0 mt-2" style={{ paddingTop: '0.375rem', paddingBottom: '0.375rem' }} role="alert">
                <i>Note: xcfvbxcxcv</i>
              </div>
            }

          </div>

          <div className="col-2 d-flex gap-1 justify-content-end px-3 py-4">
            <a href={`${window.location.protocol}//${window.location.host}/pdf/work/order/view/24`} className="btn" style={{ backgroundColor: '#AFE1AF', height: 'max-content' }} id="woViewButton">
              <i className="fa fa-eye" aria-hidden="true" />
            </a>
            <BackStatus id={wo.id} onSuccessMessage={handleSuccessMessage} status={wo.status} is_ftech={wo.ftech_id} />
            <NextStatus id={wo.id} onSuccessMessage={handleSuccessMessage} onErrorMessage={handleErrorMessage} status={wo.status} is_ftech={wo.ftech_id} />
          </div>

          {
            !atRisk &&
            <Reschedule id={latestAtRiskScheduleId} scheduleData={wo.schedules.find(schedule => schedule.id === latestAtRiskScheduleId)} onSuccessMessage={handleSuccessMessage} />
          }



          <div className='col-12 px-3 py-4'>
            <WorkOrderTab id={wo.id} details={wo} onSuccessMessage={handleSuccessMessage} onErrorMessage={handleErrorMessage} />
          </div>
        </div>
      </div>
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