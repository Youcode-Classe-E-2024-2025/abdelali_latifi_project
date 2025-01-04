<?php
session_start();
require_once '../backoffice/config/connexion.php';
require_once '../backoffice/controlers.php/project.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$projectManager = new Project($conn);

$userProjects = $projectManager->getProjectsForUser($_SESSION['user_id']);
$userTasks = $projectManager->getTasksForUser($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_task_status') {
    if (isset($_POST['task_id'], $_POST['new_status'])) {
        $projectManager->updateTaskStatus($_POST['task_id'], $_POST['new_status']);
        header('Location: home.php?success=task_updated');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-violet-600 text-white p-4">
        <div class="container mx-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">My Dashboard</h1>
                <div class="space-x-4">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></span>
                    <a href="logout.php" class="bg-white text-violet-600 px-4 py-2 rounded-lg hover:bg-violet-100">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-6">
 <!-- Mes Tâches -->
        <section>
            <h2 class="text-xl font-semibold mb-4">My Tasks</h2>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($userTasks as $task): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($task['title']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($task['project_name']); ?></td>
                            <td class="px-6 py-4"><?php echo date('Y-m-d', strtotime($task['due_date'])); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-sm rounded-full 
                                    <?php 
                                    echo match($task['status']) {
                                        'todo' => 'bg-gray-100 text-gray-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form action="../backoffice/controlers.php/project.php" method="POST" class="inline">
                                    <input type="hidden" name="action" value="update_task_status">
                                    <input type="hidden" name="task_id" value="<?php echo $task['task_id']; ?>">
                                    <select name="new_status" onchange="this.form.submit()" 
                                            class="rounded border-gray-300 text-sm focus:ring-violet-500 focus:border-violet-500">
                                        <option value="todo" <?php echo $task['status'] === 'todo' ? 'selected' : ''; ?>>To Do</option>
                                        <option value="in_progress" <?php echo $task['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="completed" <?php echo $task['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        // Vérifier les messages de succès/erreur
        <?php if (isset($_GET['success']) && $_GET['success'] === 'task_updated'): ?>
        alert('Task status updated successfully!');
        <?php endif; ?>
    </script>
</body>
</html>