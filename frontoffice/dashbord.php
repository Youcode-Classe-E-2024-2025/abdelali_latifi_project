<?php
require_once '../backoffice/config/connexion.php';
require_once '../backoffice/controlers.php/user.php';


$db = new Database();
$conn = $db->getConnection();
$userManager = new User($conn);

if (isset($_GET['delete_id'])) {
    $userManager->deleteUser($_GET['delete_id']);
    header("Location: ../frontoffice/dashbord.php"); 
    exit;
}

$users = $userManager->getAllUsers();
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
                                <a href="edit_user.php?id=<?= urlencode($user['user_id']) ?>" class="bg-blue-600 text-white px-2 py-1 rounded">Edit</a>
                                <a href="?delete_id=<?= urlencode($user['user_id']) ?>" onclick="return confirm('Are you sure?');" class="bg-red-600 text-white px-2 py-1 rounded">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
