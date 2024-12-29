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

    const groupedTasks = details?.tasks?.reduce((acc, task) => {
        if (task.description) {
            if (!acc[task.description]) {
                acc[task.description] = [];
            }
            acc[task.description].push(task);
        }
        return acc;
    }, {});
    return (
        <div className="card bg-white shadow-lg border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Deliverables </h3>
            </div>
            <div className="card-body bg-white">
                {/* Pre Photo Section */}
                {Object.keys(groupedTasks).length > 0 ? (
                    Object.keys(groupedTasks).map((description) => (
                        <div>
                            <h6>{description}</h6>
                            <div id="preDeliverCont" className="file-preview-container" />
                            <div className="w-100 px-3 pb-2 mb-2 border-bottom">
                                <div className="d-flex gap-2">
                                    {
                                        groupedTasks[description].map((task) => (
                                            task.file != null ? (
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
                                                                        <i className="fa fa-file-pdf preview-pdf text-danger" style={{ fontSize: '100px !important' }} aria-hidden="true" />
                                                                        <a href={`${window.location.protocol}//${window.location.host}/${task.file}`} download={task.file}>
                                                                            <p className="file-name">{task.file}</p>
                                                                        </a>
                                                                    </div>
                                                                );
                                                            } else {
                                                                // For other file types
                                                                return (
                                                                    <div>
                                                                        <i className="fa fa-file preview-pdf" aria-hidden="true" style={{ fontSize: '100px !important' }} />
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
                        </div>
                    ))):''}

            </div>
        </div>

    )
}

export default Deliverable