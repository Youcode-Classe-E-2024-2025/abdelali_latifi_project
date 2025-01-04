<?php
session_start();
require_once '../backoffice/config/connexion.php';
require_once '../backoffice/controlers.php/user.php';
require_once '../backoffice/controlers.php/project.php';

$db = new Database();
$conn = $db->getConnection();
$userManager = new User($conn);
$projectManager = new Project($conn);

if (isset($_GET['delete_id'])) {
    $userManager->deleteUser($_GET['delete_id']);
    header("Location: ../frontoffice/dashbord.php"); 
    exit;
}

$users = $userManager->getAllUsers();
$projects = $projectManager->getAllProjects();
$tasks = $projectManager->getAllTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-violet-600 text-white p-4">
        <div class="container mx-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Project Dashboard</h1>
                <div class="flex space-x-4">
                    <button onclick="openModal('addProjectModal')" class="bg-white text-violet-600 px-4 py-2 rounded-lg hover:bg-violet-100 transition-colors duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Project
                        </span>
                    </button>
                    <button onclick="openModal('addTaskModal')" class="bg-white text-violet-600 px-4 py-2 rounded-lg hover:bg-violet-100 transition-colors duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Task
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-6">
        <section id="users" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Users</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= $user['name']?></td>
                            <td class="px-4 py-2"><?= $user['email'] ?></td>
                            <td class="px-4 py-2"><?= $user['role']?></td>
                            <td class="px-4 py-2 flex space-x-2">
                                <a href="?delete_id=<?= urlencode($user['user_id']) ?>" onclick="return confirm('Are you sure?');" class="bg-red-600 text-white px-2 py-1 rounded">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Projects</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Created By</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($project['name'] ?? '') ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($project['description'] ?? '') ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($project['creator_name'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-sm <?= $project['is_public'] ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' ?>">
                                    <?= $project['is_public'] ? 'Public' : 'Private' ?>
                                </span>
                            </td>
                            <td class="px-4 py-2"><?= date('Y-m-d', strtotime($project['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Tasks Section -->
        <section id="tasks" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Tasks</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Project</th>
                            <th class="px-4 py-2">Assigned To</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($task['title'] ?? '') ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($task['project_name'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($task['assigned_to_name'] ?? 'Unassigned') ?></td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-sm 
                                    <?php
                                    switch($task['status']) {
                                        case 'todo':
                                            echo 'bg-gray-200 text-gray-800';
                                            break;
                                        case 'in_progress':
                                            echo 'bg-blue-200 text-blue-800';
                                            break;
                                        case 'completed':
                                            echo 'bg-green-200 text-green-800';
                                            break;
                                        default:
                                            echo 'bg-gray-200 text-gray-800';
                                    }
                                    ?>">
                                    <?= ucfirst(str_replace('_', ' ', $task['status'] ?? 'todo')) ?>
                                </span>
                            </td>
                            <td class="px-4 py-2"><?= $task['due_date'] ? date('Y-m-d', strtotime($task['due_date'])) : 'No due date' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Add Project Modal -->
    <div id="addProjectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-[90%] md:w-[500px] shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Add New Project</h3>
                <button onclick="closeModal('addProjectModal')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="addProjectForm" action="../backoffice/controlers.php/project.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create_project">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Project Name</label>
                    <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Visibility</label>
                    <select name="is_public" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        <option value="0">Private</option>
                        <option value="1">Public</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeModal('addProjectModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 rounded-md">Create Project</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="addTaskModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-[90%] md:w-[500px] shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Add New Task</h3>
                <button onclick="closeModal('addTaskModal')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="../backoffice/controlers.php/project.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create_task">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Task Title</label>
                    <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Project</label>
                    <select name="project_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= $project['project_id'] ?>"><?= htmlspecialchars($project['name'] ?? '') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Assigned To</label>
                    <select name="assigned_to" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['user_id'] ?>"><?= htmlspecialchars($user['name'] ?? '') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="date" name="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('addTaskModal')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function submitProjectForm(event) {
        event.preventDefault();
        
        if (!validateProjectForm()) {
            return false;
        }

        const form = document.getElementById('addProjectForm');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes('success')) {
                alert('Project created successfully!');
                window.location.reload();
            } else {
                alert('Failed to create project. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });

        return false;
    }

    function validateProjectForm() {
        const nameInput = document.querySelector('input[name="name"]');
        const nameError = document.getElementById('nameError');
        
        if (nameInput.value.trim() === '') {
            nameError.textContent = 'Project name is required';
            nameError.classList.remove('hidden');
            return false;
        }
        
        if (nameInput.value.length < 3) {
            nameError.textContent = 'Project name must be at least 3 characters long';
            nameError.classList.remove('hidden');
            return false;
        }
        
        return true;
    }

    // Afficher les messages de succès ou d'erreur
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        const error = urlParams.get('error');
        
        if (success === 'project_created') {
            alert('Project created successfully!');
        } else if (error === 'project_creation_failed') {
            alert('Failed to create project. Please try again.');
        }
    });
    </script>
    <script src="../frontoffice/javascript/dashbord.js"></script>
</body>
</html>
