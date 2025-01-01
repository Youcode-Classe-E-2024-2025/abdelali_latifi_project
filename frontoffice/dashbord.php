<?php 
require '../backoffice/config/connexion.php';

$db = new Database();
$conn = $db->getConnection();
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
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = "SELECT * FROM users";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $users = $stmt->fetchAll();

                        if (count($users) > 0) {
                            foreach ($users as $user) {
                                echo "<tr class='border-t'>
                                        <td class='px-4 py-2'>{$user['user_id']}</td>
                                        <td class='px-4 py-2'>{$user['name']}</td>
                                        <td class='px-4 py-2'>{$user['email']}</td>
                                        <td class='px-4 py-2'>{$user['role']}</td>
                                        <td class='px-4 py-2'>{$user['created_at']}</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='px-4 py-2 text-center'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="projects" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Projects</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Public</th>
                            <th class="px-4 py-2">Created By</th>
                            <th class="px-4 py-2">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = "SELECT * FROM projects";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $projects = $stmt->fetchAll();

                        if (count($projects) > 0) {
                            foreach ($projects as $project) {
                                echo "<tr class='border-t'>
                                        <td class='px-4 py-2'>{$project['project_id']}</td>
                                        <td class='px-4 py-2'>{$project['name']}</td>
                                        <td class='px-4 py-2'>{$project['description']}</td>
                                        <td class='px-4 py-2'>" . ($project['is_public'] ? 'Yes' : 'No') . "</td>
                                        <td class='px-4 py-2'>{$project['created_by']}</td>
                                        <td class='px-4 py-2'>{$project['created_at']}</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='px-4 py-2 text-center'>No projects found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="tasks" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Tasks</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Project ID</th>
                            <th class="px-4 py-2">Assigned To</th>
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = "SELECT * FROM tasks";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $tasks = $stmt->fetchAll();

                        if (count($tasks) > 0) {
                            foreach ($tasks as $task) {
                                echo "<tr class='border-t'>
                                        <td class='px-4 py-2'>{$task['task_id']}</td>
                                        <td class='px-4 py-2'>{$task['project_id']}</td>
                                        <td class='px-4 py-2'>{$task['assigned_to']}</td>
                                        <td class='px-4 py-2'>{$task['title']}</td>
                                        <td class='px-4 py-2'>{$task['status']}</td>
                                        <td class='px-4 py-2'>{$task['due_date']}</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='px-4 py-2 text-center'>No tasks found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

</body>
</html>
