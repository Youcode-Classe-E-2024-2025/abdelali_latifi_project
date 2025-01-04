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
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/connexion.php';
    session_start();
    
    $db = new Database();
    $conn = $db->getConnection();
    $projectManager = new Project($conn);

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_project':
                $name = $_POST['name'];
                $description = $_POST['description'] ?? '';
                $is_public = isset($_POST['is_public']) ? 1 : 0;
                $created_by = $_SESSION['user_id'] ?? 1;

                if ($projectManager->createProject($name, $description, $is_public, $created_by)) {
                    header('Location: ../../frontoffice/dashbord.php?success=project_created');
                } else {
                    header('Location: ../../frontoffice/dashbord.php?error=project_creation_failed');
                }
                exit;
                break;

            case 'create_task':
                $title = $_POST['title'];
                $project_id = $_POST['project_id'];
                $description = $_POST['description'] ?? '';
                $assigned_to = $_POST['assigned_to'] ?? null;
                $due_date = $_POST['due_date'] ?? null;
                $status = $_POST['status'] ?? 'todo';

                if ($projectManager->createTask($title, $project_id, $description, $assigned_to, $due_date, $status)) {
                    header('Location: ../../frontoffice/dashbord.php?success=task_created');
                } else {
                    header('Location: ../../frontoffice/dashbord.php?error=task_creation_failed');
                }
                exit;
                break;
        }
    }
}
