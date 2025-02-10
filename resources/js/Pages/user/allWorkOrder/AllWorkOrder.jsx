import { Head, Link, useForm } from '@inertiajs/react'
import { DateTime } from 'luxon';
import React, { useEffect, useState } from 'react'
import MainLayout from '../layout/MainLayout';

const AllWorkOrder = ({ w_orders }) => {

  const [activeFilter, setActiveFilter] = useState(0)
  const [searchQuery, setSearchQuery] = useState('');
  const [workOrders, setWorkOrders] = useState([]);
  const [loading, setLoading] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');

  // Fetch work orders from the backend
  const fetchWorkOrders = async (search = '', stage = 0, page = 1) => {
    setLoading(true);
    try {
      const response = await fetch(`/api/all-wo-lists?search=${search}&stage=${stage}&page=${page}`);
      const data = await response.json();
      setWorkOrders(data.w_orders.data);
      setCurrentPage(data.w_orders.current_page);
      setTotalPages(data.w_orders.last_page);
    } catch (error) {
      console.error('Error fetching work orders:', error);
    } finally {
      setLoading(false);
    }
  };

  // Fetch work orders on initial load and whenever the search query changes
  useEffect(() => {
    fetchWorkOrders(searchQuery, activeFilter, currentPage);
  }, [searchQuery, activeFilter, currentPage]);

  // Handle the search query change
  const handleSearchChange = (e) => {
    const query = e.target.value;
    setSearchQuery(query); // Update the search query state
  };

  const handlePageChange = (page) => {
    if (page > 0 && page <= totalPages) {
      setCurrentPage(page); // Update current page
    }
  };

  const handleCallback = (start, end) => {
    setStartDate(start.format('YYYY-MM-DD'));
    setEndDate(end.format('YYYY-MM-DD'));
  };

  const { data, setData, post, errors, processing, recentlySuccessful } = useForm({

  });

  const getStatus = (wo) => {
    if (wo.is_hold === 1) {
      return <span className="text-secondary">On Hold</span>;
    } else {
      const stageMap = {
        1: <span className="text-info-emphasis">New</span>,
        2: <span className="text-warning-emphasis">Need Dispatch</span>,
        3: <span className="text-success">Dispatched</span>,
        4: <span className="text-secondary">Closed</span>,
        5: <span className="text-primary">Billing</span>,
        7: <span className="text-danger">Cancelled</span>,
      };
      return stageMap[wo.stage] || '';
    }

  };


  return (
    <>
      <MainLayout>
        <Head title={'All WorkOrder | Techbook'} />
        <div className="container-fluid total-bg">
          <div className='bg-white border rounded py-3 px-1 row justify-content-between align-items-center mt-3 mb-3'>
            <h2 class="fs-4 mb-0 col-md-2">All Work Orders</h2>

            <div className='d-flex gap-2 col-md-10 justify-content-end'>
              <div className='p-1 rounded d-flex align-items-center gap-3' style={{ backgroundColor: '#F0F0F0' }}>
                <div className={`${activeFilter == 0 && 'bg-white'} ${activeFilter == 0 && 'shadow'} ${activeFilter == 0 && 'rounded'} h-100 px-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(0)}>All</div>
                <div className={`${activeFilter == 1 && 'bg-white'} ${activeFilter == 1 && 'shadow'} ${activeFilter == 1 && 'rounded'} h-100 px-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(1)}>New</div>
                <div className={`${activeFilter == 2 && 'bg-white'} ${activeFilter == 2 && 'shadow'} ${activeFilter == 2 && 'rounded'} h-100 px-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(2)}>Need Dispatch</div>
                <div className={`${activeFilter == 3 && 'bg-white'} ${activeFilter == 3 && 'shadow'} ${activeFilter == 3 && 'rounded'} h-100 px-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(3)}>Dispatched</div>
                <div className={`${activeFilter == 4 && 'bg-white'} ${activeFilter == 4 && 'shadow'} ${activeFilter == 4 && 'rounded'} h-100 px-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(4)}>Closed</div>
                <div className={`${activeFilter == 5 && 'bg-white'} ${activeFilter == 5 && 'shadow'} ${activeFilter == 5 && 'rounded'} h-100 px-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(5)}>Billing</div>
              </div>
              <input
                type="text"
                placeholder="Search Here..."
                className="border p-2"
                style={{ width: '200px' }}
                value={searchQuery}
                onChange={handleSearchChange}
              />
            </div>

          </div>

          <div className='bg-white border rounded py-3 px-3 row mb-3'>
            <table className='table table-striped table-hover'>
              <thead className='border-0'>
                <tr>
                  <th className='text-start border-0'>ID</th>
                  <th className='text-start border-0'>Schedule</th>
                  <th className='text-start border-0'>Customer</th>
                  <th className='text-start border-0'>Technician</th>
                  <th className='text-start border-0'>Stage</th>
                  <th className='text-start border-0'>Status</th>
                  <th className='text-start border-0'>Created At</th>
                </tr>
              </thead>
              <tbody className='border-0'>
                {loading ? (
                  <div>Loading...</div> // Show loading text while fetching
                ) : (

                  workOrders?.map((wo) => (
                    <tr className='rounded-3'>
                      <td className='border-0 fw-bold' style={{ borderRadius: '10px 0 0 10px' }}><Link href={`/user/work/order/view/layout/user/dashboard/inertia/${wo.id}`}>{wo.order_id}</Link></td>
                      <td className='border-0'>
                        {
                          wo?.schedules[0]?.on_site_by ?
                            DateTime.fromISO(wo?.schedules[0]?.on_site_by).toFormat('MM-dd-yy') : 'N/A'
                        }
                      </td>
                      <td className='border-0 fw-bold'>{wo?.customer?.company_name ?? <i class="fa-regular fa-clock text-success"></i>}</td>
                      <td className='border-0 fw-bold'>
                        {wo?.technician ? (
                          <>
                            {wo.technician.company_name} (ID: {wo.technician.technician_id})
                          </>
                        ) : (
                          <i className="fa-regular fa-clock text-success"></i>
                        )}
                      </td>

                      <td className='border-0 fw-bold'>
                        {getStatus(wo)}
                      </td>
                      <td className='border-0 fw-bold'>{wo.status == 1 ? <span className='text-info-emphasis'>Pending</span> : wo.status == 2 ? <span className='text-warning-emphasis'>Contacted</span> : wo.status == 3 ? <span className='text-success'>Confirm</span> : wo.status == 4 ? <span className='text-danger'>At Risk</span> : wo.status == 5 ? <span className='text-primary'>Delayed</span> : wo.status == 6 ? <span className='text-primary'>On hold</span> : wo.status == 7 ? <span className='text-primary'>En route</span> : wo.status == 8 ? <span className='text-primary'>Checked in</span> : wo.status == 9 ? <span className='text-primary'>Checked out</span> : wo.status == 10 ? <span className='text-primary'>Needs Approval</span> : wo.status == 11 ? <span className='text-warning'>Needs Review</span> : wo.status == 12 ? <span className='text-primary'>Approved</span> : wo.status == 13 ? <span className='text-primary'>Invoiced</span> : wo.status == 14 ? <span className='text-primary'>Past due</span> : wo.status == 15 ? <span className='text-primary'>Paid</span> : 'N/A'}</td>
                      <td className='border-0 fw-bold'>{DateTime.fromISO(wo.created_at).toRelative()}</td>

                    </tr>
                  ))

                )}

              </tbody>
            </table>
            <div className="pagination justify-content-end align-items-center">
              <button className='btn btn-outline-primary' onClick={() => handlePageChange(currentPage - 1)} disabled={currentPage === 1}>
                Previous
              </button>
              <span className='mx-2'>
                Page {currentPage} of {totalPages}
              </span>
              <button className='btn btn-outline-primary' onClick={() => handlePageChange(currentPage + 1)} disabled={currentPage === totalPages}>
                Next
              </button>
            </div>
          </div>
        </div>
      </MainLayout>

    </>
  )
}

export default AllWorkOrder