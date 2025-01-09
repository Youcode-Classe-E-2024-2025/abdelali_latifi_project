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
   <h1>okaay</h1>

</body>
</html>
