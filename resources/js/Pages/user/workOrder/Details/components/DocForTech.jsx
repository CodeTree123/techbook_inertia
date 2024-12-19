import { useForm } from '@inertiajs/react';
import React from 'react'

const DocForTech = ({ id, details, onSuccessMessage }) => {
    const { data, setData, delete: deleteItem, post, errors, processing, recentlySuccessful } = useForm({
        'file': [],
    });

    const submit = (e) => {
        if (e) e.preventDefault();

        post(route('user.wo.uploadDocForTech', id), {
            preserveScroll: true,
            onSuccess: () => {
                onSuccessMessage('Documents Uploaded Successfully');
            },
            onError: (e) => {
                onSuccessMessage(e);
            }
        });
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

    const handleUpload = (e) => {
        e.preventDefault();

        setData('file', [...e.target.files]);
        submit();
    };


    return (
        <div className="card action-cards bg-white shadow-lg border-0 mb-4">
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
                                                    <i className="fa fa-file-pdf preview-pdf text-danger" style={{fontSize: '90px !important'}} aria-hidden="true" />
                                                    <a href={`${window.location.protocol}//${window.location.host}/docs/technician/${doc.name}`} download={doc.name}>
                                                        <p className="file-name">{doc.name}</p>
                                                    </a>
                                                </div>
                                            );
                                        } else {
                                            // For other file types
                                            return (
                                                <div>
                                                    <i className="fa fa-file preview-pdf" aria-hidden="true" style={{fontSize: '90px !important'}} />
                                                    <a href={`${window.location.protocol}//${window.location.host}/docs/technician/${doc.name}`} download={doc.name}>
                                                        <p className="file-name">{doc.name}</p>
                                                    </a>
                                                </div>
                                            );
                                        }
                                    })()}
                                </div>
                                <form onSubmit={(e) => deleteDoc(e, doc.id)}>
                                    <button type="submit" className="delete-btn">×</button>
                                </form>
                            </div>
                        ))
                    }

                </div>
                {errors.file && <p className='text-danger'>{errors.file}</p>}
                <div className="w-100 py-3">
                    <form encType="multipart/form-data">
                        <label htmlFor="technicianDoc" className="btn btn-outline-dark">Add File</label>
                        <input id='technicianDoc' accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .xlsx, .txt" onChange={(e) => handleUpload(e)} name="file[]" className="invisible" type="file" multiple />
                    </form>
                </div>
            </div>
        </div>

    )
}

export default DocForTech