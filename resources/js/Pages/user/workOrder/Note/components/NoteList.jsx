import { useForm } from '@inertiajs/react';
import { DateTime } from 'luxon'
import React, { useState } from 'react'

const NoteList = ({ id, details, timezone, addNote, setAddNote, onSuccessMessage, onErrorMessage }) => {
    const timezoneMap = {
        'PT': 'America/Los_Angeles',
        'MT': 'America/Denver',
        'CT': 'America/Chicago',
        'ET': 'America/New_York',
        'AKT': 'America/Anchorage',
        'HST': 'Pacific/Honolulu',
    };

    const selectedTimezone = timezoneMap[timezone];

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        note: '',
    });

    const storeSubNote = (e, noteId) => {
        e.preventDefault();
        setAddNote(null);
        if (data.note) {
            post(route('user.subnote.store', noteId), {
                preserveScroll: true,
                onSuccess: () => {
                    onSuccessMessage('Note Added');
                    setData(null)
                }
            });
        }

    }

    return (
        <div className='mt-4 d-flex gap-4'>
            <div>
                <div className='rounded-5 bg-primary d-flex justify-content-center align-items-center' style={{ width: '50px', height: '50px' }}>
                    <h2 className='mb-0 text-white fw-bold' style={{ fontSize: '35px' }}>{details?.user?.firstname.charAt(0)}</h2>
                </div>
                <a className='text-primary' style={{ fontSize: '12px', whiteSpace: 'nowrap', cursor: 'pointer' }} onClick={() => setAddNote(addNote ? null : details.id)}>+ Add Note</a>
            </div>
            <div className='w-100'>
                <div className='d-flex justify-content-between align-items-center mb-1'>
                    <h5 className='mb-0'>{details?.user?.firstname} {details?.user?.lastname}</h5>
                    <span className='mb-0 d-flex align-items-center gap-2' style={{ fontSize: '16px' }}>
                        <i class="fa-regular fa-clock"></i>
                        {DateTime.fromISO(details.created_at, { zone: selectedTimezone }).toFormat('cccc')}, {DateTime.fromISO(details.created_at, { zone: selectedTimezone }).toFormat('MMMM d, yyyy')} at {DateTime.fromISO(details.created_at, { zone: selectedTimezone }).toFormat('h:mm a')} ({timezone})
                    </span>
                </div>

                <div className='rounded-2'>
                    <div className='bg-white rounded-top-2 shadow p-2'>
                        <div className='rounded-3 border p-2' style={{ fontSize: '20px' }}>
                            {details.note}
                        </div>
                    </div>
                    <div className='px-2 py-1' style={{ backgroundColor: '#D9D9D9', fontSize: '12px' }}>
                        Shared with Developer team
                    </div>

                    {
                        details?.sub_notes?.map((note) => (
                            <div className='p-2' style={{ backgroundColor: '#E1E1E1' }}>
                                <div className='bg-white rounded-2 p-2'>
                                    <div className='rounded-3 border p-2' style={{ fontSize: '20px' }}>
                                        {note.note}
                                    </div>
                                </div>
                            </div>
                        ))
                    }

                    {
                        addNote == details.id && <div className='p-2 rounded-bottom-2' style={{ backgroundColor: '#E1E1E1' }}>
                            <textarea name="" className='w-100 p-2 rounded-2' placeholder='Add Note' rows={2} id="" autoFocus onChange={(e) => setData({ ...data, note: e.target.value })}></textarea>
                            <div className='d-flex justify-content-end gap-2'>
                                <button className='btn px-5 fw-bold text-black' style={{ backgroundColor: '#9BCFF5' }} onClick={(e) => storeSubNote(e, details.id)}>POST</button>
                                <button className='btn px-5 fw-bold text-black' style={{ backgroundColor: 'rgba(226,31,109,0.5)' }} onClick={() => setAddNote(null)}>Cancel</button>
                            </div>
                        </div>
                    }
                </div>

            </div>
        </div>
    )
}

export default NoteList