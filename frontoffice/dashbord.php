<?php
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
            <h1 class="text-2xl font-bold">Project Dashboard</h1>
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
</body>
</html>
