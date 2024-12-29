import { useForm } from '@inertiajs/react';
import React, { useState } from 'react'
import NoteList from './components/NoteList';

const Note = ({ id, details, timezone, onSuccessMessage, onErrorMessage }) => {

    const [activeFilter, setActiveFilter] = useState(0);

    const filterByNoteType = (note) => {
        switch (activeFilter) {
            case 0: // All notes
                return true;
            case 1: // General Notes
                return note.note_type === 'general_notes';
            case 2: // Dispatch Notes
                return note.note_type === 'dispatch_notes';
            case 3: // Billing Notes
                return note.note_type === 'billing_notes';
            case 4: // Tech Support Notes
                return note.note_type === 'tech_support_notes';
            case 5: // Closeout Notes
                return note.note_type === 'close_out_notes';
            default:
                return false; // Handle unexpected filter values
        }
    };

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        note_type: 'general_notes',
        note: '',
    });

    const storeNote = (e) => {
        e.preventDefault();
        if(data.note != ''){
            post(route('user.note.store', id), {
                preserveScroll: true,
                onSuccess: () => {
                    onSuccessMessage('Note Added');
                    setData(null)
                }
            });
        }else{
            onErrorMessage('Note is required')
        }

    }

    const [addNote, setAddNote] = useState(null)

    return (
        <div>
            <div className='p-1 rounded d-flex justify-content-end align-items-center gap-3 mb-3 ms-auto' style={{ backgroundColor: '#F0F0F0', width: 'max-content' }}>
                <div className={`${activeFilter == 0 && 'bg-white'} ${activeFilter == 0 && 'shadow'} ${activeFilter == 0 && 'rounded'} h-100 p-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(0)}>All</div>
                <div className={`${activeFilter == 1 && 'bg-white'} ${activeFilter == 1 && 'shadow'} ${activeFilter == 1 && 'rounded'} h-100 p-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(1)}>General Notes</div>
                <div className={`${activeFilter == 2 && 'bg-white'} ${activeFilter == 2 && 'shadow'} ${activeFilter == 2 && 'rounded'} h-100 p-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(2)}>Dispatch Notes</div>
                <div className={`${activeFilter == 3 && 'bg-white'} ${activeFilter == 3 && 'shadow'} ${activeFilter == 3 && 'rounded'} h-100 p-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(3)}>Billing Notes</div>
                <div className={`${activeFilter == 4 && 'bg-white'} ${activeFilter == 4 && 'shadow'} ${activeFilter == 4 && 'rounded'} h-100 p-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(4)}>Tech Support Notes</div>
                <div className={`${activeFilter == 5 && 'bg-white'} ${activeFilter == 5 && 'shadow'} ${activeFilter == 5 && 'rounded'} h-100 p-2 d-flex align-items-center justify-content-center fw-semibold`} style={{ minWidth: '70px', cursor: 'pointer', transition: '0.3s' }} onClick={() => setActiveFilter(5)}>Closeout Notes</div>
            </div>

            <h2 class="fs-4">New Note</h2>
            <textarea name="" className='w-100 shadow rounded-3 p-3' rows={5} placeholder='Add New Note' onChange={(e) => setData({ ...data, note: e.target.value })}></textarea>
            <div className='d-flex justify-content-between align-items-center mt-3'>
                <div className='d-flex justify-content-start align-items-center gap-2'>
                    <h2 class="fs-5 mb-0" style={{ whiteSpace: 'nowrap' }}>Note Type:</h2>
                    <select name="" className='form-select border' id="" onChange={(e) => setData({ ...data, note_type: e.target.value })}>
                        <option value="general_notes">General Notes</option>
                        <option value="dispatch_notes">Dispatch Notes</option>
                        <option value="billing_notes">Billing Notes</option>
                        <option value="tech_support_notes">Tech Support Notes</option>
                        <option value="close_out_notes">Closeout Notes</option>
                    </select>
                </div>
                <button className='btn px-5 fw-bold text-white' style={{ backgroundColor: '#9BCFF5' }} onClick={(e) => storeNote(e)}>POST</button>
            </div>

            {
                details.map((note) => (

                    filterByNoteType(note) && note.related_note == null && (
                        <NoteList id={id} details={note} timezone={timezone} onSuccessMessage={onSuccessMessage} onErrorMessage={onErrorMessage} addNote={addNote} setAddNote={setAddNote} />
                    )
                ))
            }

        </div>
    )
}

export default Note