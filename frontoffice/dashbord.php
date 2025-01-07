<?php
session_start();
require_once '../backoffice/config/connexion.php';
require_once '../backoffice/controlers.php/user.php';
require_once '../backoffice/controlers.php/project.php';

!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' ? header(header: 'Location: index.php') :'';

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
    <script src="javascript/dashboard.js" defer></script>
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-violet-600 text-white p-4">
        <div class="container mx-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Project Dashboard</h1>
                <div class="flex space-x-4">
                    <button id="addProjectButton" class="bg-white text-violet-600 px-4 py-2 rounded-lg hover:bg-violet-100 transition-colors duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Project
                        </span>
                    </button>
                    <button id="addTaskButton" class="bg-white text-violet-600 px-4 py-2 rounded-lg hover:bg-violet-100 transition-colors duration-200">
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
                            <th class="py-2 px-4">Name</th>
                            <th class="py-2 px-4">Description</th>
                            <th class="py-2 px-4">Created By</th>
                            <th class="py-2 px-4">Public</th>
                            <th class="py-2 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="py-2 px-4"><?php echo htmlspecialchars($project['name'] ?? ''); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($project['description'] ?? ''); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($project['creator_name'] ?? 'N/A'); ?></td>
                            <td class="py-2 px-4"><?php echo $project['is_public'] ? 'Yes' : 'No'; ?></td>
                            <td class="py-2 px-4">
                                <div class="flex space-x-2">
                                    <button type="button"
                                            onclick="openEditProjectModal(<?php 
                                                $projectData = array(
                                                    'project_id' => $project['project_id'],
                                                    'name' => $project['name'],
                                                    'description' => $project['description'],
                                                    'is_public' => $project['is_public']
                                                );
                                                echo htmlspecialchars(json_encode($projectData), ENT_QUOTES, 'UTF-8');
                                            ?>)"
                                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        Edit
                                    </button>
                                    <form action="../backoffice/controlers.php/project.php" method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this project?');">
                                        <input type="hidden" name="action" value="delete_project">
                                        <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
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
                <button id="closeAddProjectModal" class="text-gray-500 hover:text-gray-700">
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
                    <button type="button" id="cancelAddProject" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">Cancel</button>
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
                <button id="closeAddTaskModal" class="text-gray-500 hover:text-gray-700">
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
                            <option value="<?= $project['project_id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Assigned To</label>
                    <select name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        <option value="">Select User</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['user_id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
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
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" id="cancelAddTask" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 rounded-md">Create Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div id="editProjectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-[90%] md:w-[500px] shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Edit Project</h3>
                <button id="closeEditProjectModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="../backoffice/controlers.php/project.php" method="POST">
                <input type="hidden" name="action" value="update_project">
                <input type="hidden" name="project_id" id="edit_project_id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_project_name">
                        Project Name
                    </label>
                    <input type="text" id="edit_project_name" name="name" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_project_description">
                        Description
                    </label>
                    <textarea id="edit_project_description" name="description" rows="3"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="edit_project_is_public" name="is_public" class="mr-2">
                        <span class="text-gray-700 text-sm font-bold">Public Project</span>
                    </label>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="cancelEditProject"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-violet-600 text-white px-4 py-2 rounded hover:bg-violet-700 transition-colors duration-200">
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
