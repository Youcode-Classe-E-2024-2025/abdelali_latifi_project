// Modal Management
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Project modal management
function openEditProjectModal(projectData) {
    console.log('Opening edit modal with project:', projectData);
    try {
        document.getElementById('edit_project_id').value = projectData.project_id;
        document.getElementById('edit_project_name').value = projectData.name || '';
        document.getElementById('edit_project_description').value = projectData.description || '';
        document.getElementById('edit_project_is_public').checked = projectData.is_public === "1" || projectData.is_public === 1;
        
        openModal('editProjectModal');
    } catch (error) {
        console.error('Error in openEditProjectModal:', error);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Project modal buttons
    document.getElementById('addProjectButton').addEventListener('click', () => openModal('addProjectModal'));
    document.getElementById('closeAddProjectModal').addEventListener('click', () => closeModal('addProjectModal'));
    document.getElementById('cancelAddProject').addEventListener('click', () => closeModal('addProjectModal'));

    // Task modal buttons
    document.getElementById('addTaskButton').addEventListener('click', () => openModal('addTaskModal'));
    document.getElementById('closeAddTaskModal').addEventListener('click', () => closeModal('addTaskModal'));
    document.getElementById('cancelAddTask').addEventListener('click', () => closeModal('addTaskModal'));

    // Edit buttons
    document.getElementById('closeEditProjectModal').addEventListener('click', () => closeModal('editProjectModal'));
    document.getElementById('cancelEditProject').addEventListener('click', () => closeModal('editProjectModal'));

    // Close modals
    window.addEventListener('click', function(event) {
        const modals = [
            document.getElementById('addProjectModal'), 
            document.getElementById('addTaskModal'),
            document.getElementById('editProjectModal')
        ];
        modals.forEach(modal => {
            if (event.target === modal) {
                closeModal(modal.id);
            }
        });
    });

    // Check for messages
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const error = urlParams.get('error');
    
    if (success) {
        switch(success) {
            case 'project_created':
                alert('Project created successfully!');
                break;
            case 'project_updated':
                alert('Project updated successfully!');
                break;
            case 'project_deleted':
                alert('Project deleted successfully!');
                break;
            case 'task_created':
                alert('Task created successfully!');
                break;
        }
    } else if (error) {
        switch(error) {
            case 'project_creation_failed':
                alert('Failed to create project. Please try again.');
                break;
            case 'project_update_failed':
                alert('Failed to update project. Please try again.');
                break;
            case 'project_deletion_failed':
                alert('Failed to delete project. Please try again.');
                break;
            case 'task_creation_failed':
                alert('Failed to create task. Please try again.');
                break;
        }
    }

    // Handle form submissions
    const projectForm = document.querySelector('#addProjectModal form');
    if (projectForm) {
        projectForm.addEventListener('submit', function(event) {
            if (!validateProjectForm()) {
                event.preventDefault();
            }
        });
    }
});

function updateTaskStatus(taskId, newStatus) {
    const statusCell = document.querySelector(`#task-${taskId} .status-cell`);
    if (statusCell) {
        statusCell.textContent = newStatus;
        
        statusCell.className = 'status-cell px-4 py-2';
        switch(newStatus) {
            case 'todo':
                statusCell.classList.add('text-yellow-600', 'bg-yellow-100');
                break;
            case 'in_progress':
                statusCell.classList.add('text-blue-600', 'bg-blue-100');
                break;
            case 'completed':
                statusCell.classList.add('text-green-600', 'bg-green-100');
                break;
        }
    }
}

// Form validation
function validateProjectForm() {
    const nameInput = document.querySelector('input[name="name"]');
    const nameError = document.getElementById('nameError');
    
    if (!nameInput.value.trim()) {
        nameError.textContent = 'Project name is required';
        nameError.classList.remove('hidden');
        return false;
    }
    
    nameError.classList.add('hidden');
    return true;
}
