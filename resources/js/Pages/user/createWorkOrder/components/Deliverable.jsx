import React from 'react'

const Deliverable = ({ data, setData, delivarableRef }) => {

  const groupedTasks = data?.tasks?.reduce((acc, task) => {
    if (task.desc) {
      const normalizedDescription = task.desc.toUpperCase();
      if (!acc[normalizedDescription]) {
        acc[normalizedDescription] = [];
      }
      acc[normalizedDescription].push(task);
    }
    return acc;
  }, {});

  const renderFilePreview = (file, index, taskId) => {
    const fileExtension = file?.name?.split('.').pop().toLowerCase();
    const fileUrl = URL.createObjectURL(file);

    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
      return (
        <div key={index} className="file-preview position-relative">
          <div className="file-content">
            <div>
              <img src={fileUrl} alt={file?.name} className="file-preview-image w-100" />
              <a href={fileUrl} target="_blank" rel="noopener noreferrer">
                <p className="file-name">{file?.name}</p>
              </a>
            </div>
          </div>
          <p className='text-danger position-absolute' style={{ cursor: 'pointer', right: '5px', top: '0px' }} onClick={(e) => deleteTask(e, taskId, file.uploaded_at)}>X</p>
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
          <p className='text-danger position-absolute' style={{ cursor: 'pointer', right: '5px', top: '0px' }} onClick={(e) => deleteTask(e, taskId, file.uploaded_at)}>X</p>
        </div>

      );
    } else {
      return (
        <div key={index} className="file-preview position-relative">
          <div className="file-content">
            <div>
              <i className="fa fa-file preview-pdf" aria-hidden="true" style={{ fontSize: '100px' }}></i>
              <a href={fileUrl} target="_blank">
                <p className="file-name" style={{ cursor: 'pointer', right: '5px', top: '0px' }} onClick={(e) => deleteTask(e, taskId, file.path)}>{file.uploaded_at}</p>
              </a>
            </div>
          </div>
          <p className='text-danger position-absolute'>X</p>
        </div>
      );
    }
  };

  const addFilePhoto = async (e, description) => {
    const file = e.target.files[0];

    if (file) {
      // Prepare the new task file object
      const newTaskFile = {
        description: description || '',
        file: file || '',
      };

      console.log(description);

      const taskIndex = data.tasks.findIndex(task => task.desc.toUpperCase() === description);
      console.log(taskIndex);

      if (taskIndex !== -1) {
        const updatedTasks = [...data.tasks];
        // Initialize taskFiles if it doesn't exist
        updatedTasks[taskIndex].file = updatedTasks[taskIndex].file || []; // Create an empty array if taskFiles is not present

        updatedTasks[taskIndex].file.push(newTaskFile); // Add the new file to the taskFiles array

        setData({
          ...data,
          tasks: updatedTasks,
        });
      } else {
        // Handle the case where no matching task is found (e.g., log, create a new task)
        console.log('No matching task found for this description.');
      }
    }
  };
console.log(groupedTasks);


  return (
    <div ref={delivarableRef} className="card bg-white border mb-4">
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
                  {groupedTasks[description].map((task, taskIndex) => {
                    if (task.file && Array.isArray(task.file)) {
                      // If the task has files and file is an array, we directly use it
                      return task.file.map((file, fileIndex) => {
                        if (file && file.path) {
                          return renderFilePreview(file, taskIndex, fileIndex); // Render the file preview
                        }
                        return null;
                      });
                    } else if (task.file && typeof task.file === 'string') {
                      // If the task file is a stringified JSON, parse it
                      let files = [];
                      try {
                        files = Array.isArray(JSON.parse(task.file)) ? JSON.parse(task.file) : [];
                      } catch (error) {
                        console.error('Error parsing task file:', error);
                      }

                      return files.map((file, fileIndex) => {
                        if (file && file.path) {
                          return renderFilePreview(file, taskIndex, fileIndex); // Render the file preview
                        }
                        return null;
                      });
                    }
                    return null; // If task.file is null or invalid, return nothing
                  })}
                </div>

                <label htmlFor={`morefile-${description}`} className='py-2' style={{ cursor: 'pointer' }}>
                  + Add File
                </label>
                <input
                  id={`morefile-${description}`}
                  type="file"
                  className='invisible'
                  onChange={(e) => addFilePhoto(e, description)}
                />
              </div>
            </div>
          ))
        ) : null}


      </div>
    </div>
  )
}

export default Deliverable