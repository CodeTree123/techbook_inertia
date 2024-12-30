import { useForm } from '@inertiajs/react';
import React from 'react'

const Deliverable = ({ id, details, onSuccessMessage }) => {
    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({

    });

    const deleteTask = (e, taskId, url) => {
        e.preventDefault();

        post(route('user.wo.deleteFilePhoto', [taskId, url]), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('File Deleted Successfully');
            }
        });
    };

    const groupedTasks = details?.tasks?.reduce((acc, task) => {
        if (task.description) {
            const normalizedDescription = task.description.toUpperCase();
            if (!acc[normalizedDescription]) {
                acc[normalizedDescription] = [];
            }
            acc[normalizedDescription].push(task);
        }
        return acc;
    }, {});

    const renderFilePreview = (file, index, taskId) => {
        const fileExtension = file.path.split('.').pop().toLowerCase();
        const fileUrl = `${window.location.protocol}//${window.location.host}/${file.path}`;

        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
            return (
                <div key={index} className="file-preview position-relative">
                    <div className="file-content">
                        <div>
                            <img src={fileUrl} alt={file.name} className="file-preview-image w-100" />
                            <a href={fileUrl} target="_blank" rel="noopener noreferrer">
                                <p className="file-name">{file.name}</p>
                            </a>
                        </div>
                    </div>
                    <p className='text-danger position-absolute' style={{cursor: 'pointer', right: '5px', top: '0px'}} onClick={(e)=>deleteTask(e, taskId, file.uploaded_at)}>X</p>
                </div>

            );
        } else if (fileExtension === 'pdf') {
            return (
                <div key={index} className="file-preview position-relative">
                    <div className="file-content">
                        <div>
                            <i className="fa fa-file-pdf preview-pdf text-danger" aria-hidden="true" style={{ fontSize: '100px' }}></i>
                            <a href={fileUrl} target="_blank">
                                <p className="file-name">{file.name}</p>
                            </a>
                        </div>
                    </div>
                    <p className='text-danger position-absolute' style={{cursor: 'pointer', right: '5px', top: '0px'}} onClick={(e)=>deleteTask(e, taskId, file.uploaded_at)}>X</p>
                </div>

            );
        } else {
            return (
                <div key={index} className="file-preview position-relative">
                    <div className="file-content">
                        <div>
                            <i className="fa fa-file preview-pdf" aria-hidden="true" style={{ fontSize: '100px' }}></i>
                            <a href={fileUrl} target="_blank">
                                <p className="file-name" style={{cursor: 'pointer', right: '5px', top: '0px'}} onClick={(e)=>deleteTask(e, taskId, file.path)}>{file.uploaded_at}</p>
                            </a>
                        </div>
                    </div>
                    <p className='text-danger position-absolute'>X</p>
                </div>
            );
        }
    };

    const addFilePhoto = async (e, description) => {
        // Check if a file was selected
        if (e.target.files && e.target.files.length > 0) {
            const file = e.target.files[0];

            // Create a FormData object
            const formData = new FormData();
            formData.append('file', file); // Append the selected file to FormData

            try {
                // Send the request using Fetch API or Axios
                const response = await fetch(route('user.wo.uploadMoreFilePhoto', description), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, // Include CSRF token
                    },
                    body: formData, // Use FormData as the request body
                });

                if (response.ok) {
                    const result = await response.json();
                    console.log('File Uploaded Successfully', result);
                    onSuccessMessage('File Uploaded Successfully');
                } else {
                    console.error('File Upload Failed:', response.statusText);
                    onSuccessMessage('File upload failed.');
                }
            } catch (error) {
                console.error('Upload Error:', error);
                onSuccessMessage('File upload failed.');
            }
        } else {
            console.log('No file selected.');
        }
    };

    return (
        <div className="card bg-white shadow-lg border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Deliverables </h3>
            </div>
            <div className="card-body bg-white">
                {/* Pre Photo Section */}
                {Object.keys(groupedTasks).length > 0 ? (
                    Object.keys(groupedTasks).map((description) => (
                        <div key={description}>
                            <h6>{description}</h6>
                            <div id="preDeliverCont" className="file-preview-container" />
                            <div className="w-100 pb-2 mb-2 border-bottom">
                                <div className="d-flex gap-2 flex-wrap">
                                    {groupedTasks[description].map((task) => {
                                        if (task.file != null) {
                                            let files = [];
                                            try {

                                                // Check if task.file is a JSON string and parse it if necessary
                                                if (typeof task.file === 'string') {
                                                    files = Array.isArray(JSON.parse(task.file)) ? JSON.parse(task.file) : [];
                                                } else if (Array.isArray(task.file)) {
                                                    files = task.file; // If task.file is already an array, use it directly
                                                }
                                            } catch (error) {
                                                console.error('Error parsing task file:', error);
                                            }

                                            return (
                                                <>
                                                    {files.map((file, index) => (
                                                        file && file.path ? renderFilePreview(file, index, task.id ) : null
                                                    ))}
                                                </>
                                            );
                                        }
                                        return null;
                                    })}
                                </div>

                                <label htmlFor={`morefile-${description}`} className='py-2' style={{ cursor: 'pointer' }}>+ Add File</label>
                                <input id={`morefile-${description}`} type="file" className='invisible' onChange={(e) => addFilePhoto(e, description)} />
                            </div>
                        </div>
                    ))
                ) : null}

            </div>
        </div>

    )
}

export default Deliverable