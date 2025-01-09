<?php 
session_start();
require_once '../backoffice/config/connexion.php';
require_once '../backoffice/controlers.php/project.php';

!isset($_SESSION['role']) || $_SESSION['role'] !== 'team_member' ? header('Location: index.php') : '';

$db = new Database();
$conn = $db->getConnection();
$projectManager = new Project($conn);

$projects = $projectManager->getAllProjects();
$tasks = $projectManager->getAllTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-violet-600 text-white p-4">
        <div class="container mx-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Guest Dashboard</h1>
                <div class="flex space-x-4">
                <a href="../backoffice/logout.php" class="bg-white text-violet-600 px-4 py-2 rounded-lg hover:bg-violet-100">Logout</a>                    
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-6">
        <!-- Projects Section -->
        <section id="projects" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Public Projects</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="py-2 px-4">Name</th>
                            <th class="py-2 px-4">Description</th>
                            <th class="py-2 px-4">Created By</th>
                            <th class="py-2 px-4">Public</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                        <?php if ($project['is_public'] == 1): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="py-2 px-4"><?php echo htmlspecialchars($project['name'] ?? ''); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($project['description'] ?? ''); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($project['creator_name'] ?? 'N/A'); ?></td>
                            <td class="py-2 px-4"><?php echo $project['is_public'] ? 'Yes' : 'No'; ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

</body>
</html>
