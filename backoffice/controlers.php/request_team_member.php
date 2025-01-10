<?php
session_start();

require_once __DIR__ . '/../config/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    if (!empty($message)) {
        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("INSERT INTO membership_requests (user_id, message, status) VALUES (:user_id, :message, 'pending')");
        
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            header('location: Location: ../../frontoffice/guest.php');
        } else {
            echo "Something went wrong. Please try again.";
        }
    }
} else {
    echo "Invalid request method.";
}
?>
