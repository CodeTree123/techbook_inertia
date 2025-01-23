import { router, useForm } from '@inertiajs/react';
import React, { useState } from 'react'

const DocForTech = ({ id, details, onSuccessMessage, is_cancelled, is_billing }) => {
    const { data, setData, delete: deleteItem, post, errors, processing, recentlySuccessful } = useForm({
    });

    const [file, setFile] = useState([]);

    const handleUpload = async (e) => {
        // Check if a file was selected
        if (e.target.files && e.target.files.length > 0) {
            const file = e.target.files[0];

            // Create a FormData object
            const formData = new FormData();
            formData.append('file', file);

            try {
                // Send the request using Fetch API or Axios
                const response = await fetch(route('user.wo.uploadDocForTech', id), {
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
                    router.reload();
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

    const deleteDoc = (e, docID) => {
        e.preventDefault();

        deleteItem(route('user.wo.deleteDocForTech', docID), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Document Deleted Successfully');
            },
            onError: (e) => {
                onSuccessMessage(e);
            }
        });
    };


    return (
        <div className="card action-cards bg-white shadow border-0 mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Tech Yeah Documents For Technicians
                </h3>
            </div>
            <div className="card-body bg-white">
                <div id="technicianDocCont" className="d-flex gap-2">
                    {
                        details?.docs_for_tech?.map((doc) => (
                            <div className="file-preview">
                                <div className="file-content">
                                    {(() => {
                                        const fileExtension = doc.name.split('.').pop().toLowerCase(); // Get file extension in lowercase

                                        // Check file extension and apply different logic for images, PDF, etc.
                                        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                                            // If it's an image
                                            return (
                                                <div>
                                                    <img src={`${window.location.protocol}//${window.location.host}/${doc.file}`} alt={doc.name} className="file-preview-image w-100" />
                                                    <p className="file-name">{doc.name}</p>
                                                </div>
                                            );
                                        } else if (fileExtension === 'pdf') {
                                            // If it's a PDF
                                            return (
                                                <div>
                                                    <i className="fa fa-file-pdf preview-pdf text-danger" style={{ fontSize: '90px !important' }} aria-hidden="true" />
                                                    <a href={`${window.location.protocol}//${window.location.host}/docs/technician/${doc.name}`} download={doc.name}>
                                                        <p className="file-name">{doc.name}</p>
                                                    </a>
                                                </div>
                                            );
                                        } else {
                                            // For other file types
                                            return (
                                                <div>
                                                    <i className="fa fa-file preview-pdf" aria-hidden="true" style={{ fontSize: '90px !important' }} />
                                                    <a href={`${window.location.protocol}//${window.location.host}/docs/technician/${doc.name}`} download={doc.name}>
                                                        <p className="file-name">{doc.name}</p>
                                                    </a>
                                                </div>
                                            );
                                        }
                                    })()}
                                </div>
                                <form onSubmit={(e) => deleteDoc(e, doc.id)}>
                                    <button type="submit" className="delete-btn" disabled={is_cancelled || is_billing}>×</button>
                                </form>
                            </div>
                        ))
                    }

                </div>
                {errors.file && <p className='text-danger'>{errors.file}</p>}
                <div className="w-100 py-3">
                    <form encType="multipart/form-data">
                        {
                            is_cancelled || is_billing ?
                                <button className="btn btn-outline-dark" disabled>Add File</button> :
                                <label htmlFor="technicianDoc" className="btn btn-outline-dark">Add File</label>
                        }

                        <input id='technicianDoc' accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .xlsx, .txt" onChange={handleUpload} name="file" className="invisible" type="file" />
                    </form>
                </div>
            </div>
        </div>

    )
}

export default DocForTech