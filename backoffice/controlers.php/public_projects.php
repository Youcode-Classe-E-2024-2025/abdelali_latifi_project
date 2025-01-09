<?php
session_start();
require_once '../backoffice/config/connexion.php';
require_once '../backoffice/controlers.php/user.php';
require_once '../backoffice/controlers.php/project.php';

!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' ? header('Location: index.php') : '';

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
