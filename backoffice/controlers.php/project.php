<?php
class Project {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllProjects() {
        $query = "SELECT p.*, u.name as creator_name 
                 FROM projects p 
                 LEFT JOIN users u ON p.created_by = u.user_id 
                 ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTasks() {
        $query = "SELECT t.*, p.name as project_name, u.name as assigned_to_name 
                 FROM tasks t 
                 LEFT JOIN projects p ON t.project_id = p.project_id
                 LEFT JOIN users u ON t.assigned_to = u.user_id 
                 ORDER BY t.due_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createProject($name, $description, $is_public, $created_by) {
        $query = "INSERT INTO projects (name, description, is_public, created_by) 
                 VALUES (:name, :description, :is_public, :created_by)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':is_public', $is_public);
        $stmt->bindParam(':created_by', $created_by);
        return $stmt->execute();
    }

    public function createTask($title, $project_id, $description, $assigned_to, $due_date, $status) {
        $query = "INSERT INTO tasks (title, project_id, description, assigned_to, due_date, status) 
                 VALUES (:title, :project_id, :description, :assigned_to, :due_date, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':assigned_to', $assigned_to);
        $stmt->bindParam(':due_date', $due_date);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function deleteProject($project_id) {
        $query = "DELETE FROM projects WHERE project_id = :project_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $project_id);
        return $stmt->execute();
    }

    public function getProjectById($project_id) {
        $query = "SELECT * FROM projects WHERE project_id = :project_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProject($project_id, $name, $description, $is_public) {
        $query = "UPDATE projects 
                 SET name = :name, 
                     description = :description, 
                     is_public = :is_public 
                 WHERE project_id = :project_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':is_public', $is_public);
        return $stmt->execute();
    }

    public function getProjectsForUser($user_id) {
        $query = "SELECT p.*, u.name as creator_name 
                 FROM projects p 
                 LEFT JOIN users u ON p.created_by = u.user_id 
                 LEFT JOIN project_members pm ON p.project_id = pm.project_id 
                 WHERE pm.user_id = :user_id OR p.created_by = :user_id 
                 ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTasksForUser($user_id) {
        $query = "SELECT t.*, p.name as project_name 
                 FROM tasks t 
                 LEFT JOIN projects p ON t.project_id = p.project_id 
                 WHERE t.assigned_to = :user_id 
                 ORDER BY t.due_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateTaskStatus($task_id, $new_status) {
        $query = "UPDATE tasks SET status = :status WHERE task_id = :task_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $new_status);
        $stmt->bindParam(':task_id', $task_id);
        return $stmt->execute();
    }
}

require_once __DIR__ . '/../config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new Database();
$conn = $db->getConnection();
$projectManager = new Project($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($projectManager)) {
        die('Error: $projectManager is not initialized.');
    }

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_project':
                if (isset($_POST['name'], $_POST['description'])) {
                    $is_public = isset($_POST['is_public']) ? 1 : 0;
                    if ($projectManager->createProject(
                        $_POST['name'],
                        $_POST['description'],
                        $is_public,
                        $_SESSION['user_id']
                    )) {
                        header('Location: ../../frontoffice/dashbord.php?success=project_created');
                    } else {
                        header('Location: ../../frontoffice/dashbord.php?error=project_creation_failed');
                    }
                }
                break;

                case 'delete_project':
                    if (isset($_POST['project_id'])) {
                        if ($projectManager->deleteProject($_POST['project_id'])) {
                            header('Location: ../../frontoffice/dashbord.php?success=project_deleted');
                        } else {
                            header('Location: ../../frontoffice/dashbord.php?error=project_deletion_failed');
                        }
                    }
                    break;                   

            case 'create_task':
                if (isset($_POST['title'], $_POST['project_id'], $_POST['description'], $_POST['assigned_to'], $_POST['due_date'], $_POST['status'])) {
                    if ($projectManager->createTask(
                        $_POST['title'],
                        $_POST['project_id'],
                        $_POST['description'],
                        $_POST['assigned_to'],
                        $_POST['due_date'],
                        $_POST['status']
                    )) {
                        header('Location: ../../frontoffice/dashbord.php?success=task_created');
                    } else {
                        header('Location: ../../frontoffice/dashbord.php?error=task_creation_failed');
                    }
                } else {
                    header('Location: ../../frontoffice/dashbord.php?error=missing_task_data');
                }
                break;

                case 'update_project':
                    if (isset($_POST['project_id'], $_POST['name'], $_POST['description'], $_POST['is_public'])) {
                        $is_public = $_POST['is_public'] == "1" ? 1 : 0; 
                        if ($projectManager->updateProject(
                            $_POST['project_id'],
                            $_POST['name'],
                            $_POST['description'],
                            $is_public
                        )) {
                            header('Location: ../../frontoffice/dashbord.php?success=project_updated');
                        } else {
                            header('Location: ../../frontoffice/dashbord.php?error=project_update_failed');
                        }
                    } else {
                        header('Location: ../../frontoffice/dashbord.php?error=missing_project_data');
                    }
                    break;                

            case 'update_task_status':
                if (isset($_POST['task_id'], $_POST['new_status'])) {
                    if ($projectManager->updateTaskStatus($_POST['task_id'], $_POST['new_status'])) {
                        header('Location: ../../frontoffice/home.php?success=task_updated');
                    } else {
                        header('Location: ../../frontoffice/home.php?error=task_update_failed');
                    }
                }
                break;
        }
    }
}
