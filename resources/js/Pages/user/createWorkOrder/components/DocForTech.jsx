import React from 'react'

const DocForTech = ({ data, setData, docTechRef, techDocFiles, setTechDocFiles }) => {

    const handleUpload = (e) => {
        const uploadedFiles = Array.from(e.target.files);
        setTechDocFiles((prevFiles) => [...prevFiles, ...uploadedFiles]);
    };

    const deleteDoc = (e, index) => {
        e.preventDefault();

        setData((prevData) => ({
            ...prevData,
            techDocs: prevData.techDocs.filter((_, i) => i !== index)
        }));
    };
    return (
        <div ref={docTechRef} className="card action-cards bg-white border mb-4">
            <div className="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 style={{ fontSize: 20, fontWeight: 600 }}>Tech Yeah Documents For Technicians
                </h3>
            </div>
            <div className="card-body bg-white">
                <div id="technicianDocCont" className="d-flex gap-2">
                    {
                        data?.techDocs?.map((doc, index) => (
                            <div className="file-preview">
                                <div className="file-content">
                                    {(() => {
                                        const fileExtension = doc?.name?.split('.').pop().toLowerCase(); // Get file extension in lowercase

                                        // Check file extension and apply different logic for images, PDF, etc.
                                        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                                            // If it's an image
                                            return (
                                                <div>
                                                    <img src={URL.createObjectURL(doc)} alt={doc.name} className="file-preview-image w-100" />
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
                                <form onSubmit={(e) => deleteDoc(e, index)}>
                                    <button type="submit" className="delete-btn">×</button>
                                </form>
                            </div>
                        ))
                    }

                </div>
                <div className="w-100 py-3">
                    <form encType="multipart/form-data">
                        <label htmlFor="technicianDoc" className="btn btn-outline-dark">Add File</label>

                        <input id='technicianDoc' accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .xlsx, .txt" onChange={(e) => {
                            setData({
                                ...data,
                                techDocs: [...(data.techDocs || []), e.target.files[0]], // Add the new file while preserving existing files
                            });
                        }}
                            name="file" className="invisible" type="file" />
                    </form>
                </div>
            </div>
        </div>
    )
}

export default DocForTech