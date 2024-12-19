import { useForm } from '@inertiajs/react';
import React from 'react'

const Deliverable = ({ id, details, onSuccessMessage }) => {
    const { data, setData, post, delete: deleteItem, errors, processing, recentlySuccessful } = useForm({
            
        });
    
        const deleteTask = (e, taskId) => {
            e.preventDefault();
    
            deleteItem(route('user.wo.deleteTask', taskId), {
                preserveScroll: true,
                onSuccess: () => {
                    onSuccessMessage('Task Deleted Successfully');
                }
            });
        };
    return (
        <div className="card bg-white shadow-lg border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Deliverables </h3>
            </div>
            <div className="card-body bg-white">
                {/* Pre Photo Section */}
                <h6>Pre Photo</h6>
                <div id="preDeliverCont" className="file-preview-container" />
                <div className="w-100 px-3 pb-2 mb-2 border-bottom">
                    <div className="d-flex gap-2">
                        {
                            details?.tasks?.map((task) => (
                                task.type === 'upload_file' || task.type === 'upload_photo' ? (
                                    <div className="file-preview">
                                        <div className="file-content">
                                            {(() => {
                                                const fileExtension = task?.file?.split('.').pop().toLowerCase();

                                                // Check file extension and apply different logic for images, PDF, etc.
                                                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                                                    // If it's an image
                                                    return (
                                                        <div>
                                                            <img src={`${window.location.protocol}//${window.location.host}/${task.file}`} alt={task.file} className="file-preview-image w-100" />
                                                            <p className="file-name">{task.file}</p>
                                                        </div>
                                                    );
                                                } else if (fileExtension === 'pdf') {
                                                    // If it's a PDF
                                                    return (
                                                        <div>
                                                            <i className="fa fa-file-pdf preview-pdf text-danger" style={{ fontSize: '90px !important' }} aria-hidden="true" />
                                                            <a href={`${window.location.protocol}//${window.location.host}/${task.file}`} download={task.file}>
                                                                <p className="file-name">{task.file}</p>
                                                            </a>
                                                        </div>
                                                    );
                                                } else {
                                                    // For other file types
                                                    return (
                                                        <div>
                                                            <i className="fa fa-file preview-pdf" aria-hidden="true" style={{ fontSize: '90px !important' }} />
                                                            <a href={`${window.location.protocol}//${window.location.host}/${task.file}`} download={task.file}>
                                                                <p className="file-name">{task.file}</p>
                                                            </a>
                                                        </div>
                                                    );
                                                }
                                            })()}
                                            
                                        </div>
                                        <form onSubmit={(e) => deleteTask(e, task.id)}>
                                            <button type="submit" className="delete-btn">×</button>
                                        </form>
                                    </div>
                                ) : null
                            ))
                        }

                    </div>
                    <label htmlFor="preTechnicianDoc" className="btn">+ Add File</label>
                    <input id="preTechnicianDoc" className="invisible file-input" type="file" multiple />
                </div>
                {/* Post Photo Section */}
                <h6>Post Photo</h6>
                <div id="postDeliverCont" className="file-preview-container" />
                <div className="w-100 px-3 pb-2 mb-2 border-bottom">
                    <label htmlFor="postTechnicianDoc" className="btn">+ Add File</label>
                    <input id="postTechnicianDoc" className="invisible file-input" type="file" multiple />
                </div>
                {/* Misc Photo Section */}
                <h6>Misc Photo</h6>
                <div id="miscDeliverCont" className="file-preview-container" />
                <div className="w-100 px-3 pb-2 mb-2 border-bottom">
                    <label htmlFor="miscTechnicianDoc" className="btn">+ Add File</label>
                    <input id="miscTechnicianDoc" className="invisible file-input" type="file" multiple />
                </div>
            </div>
        </div>

    )
}

export default Deliverable