// Modal 
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
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

    // Close modals 
    window.addEventListener('click', function(event) {
        const modals = [document.getElementById('addProjectModal'), document.getElementById('addTaskModal')];
        modals.forEach(modal => {
            if (event.target === modal) {
                closeModal(modal.id);
            }
        });
    });

    // Handle form submissions
    const projectForm = document.querySelector('#addProjectModal form');
    if (projectForm) {
        projectForm.addEventListener('submit', function(event) {
            if (!validateProjectForm()) {
                event.preventDefault();
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const error = urlParams.get('error');
    
    if (success === 'project_created') {
        alert('Project created successfully!');
    } else if (error === 'project_creation_failed') {
        alert('Failed to create project. Please try again.');
    } else if (success === 'task_created') {
        alert('Task created successfully!');
    } else if (error === 'task_creation_failed') {
        alert('Failed to create task. Please try again.');
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
