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
}
?>
