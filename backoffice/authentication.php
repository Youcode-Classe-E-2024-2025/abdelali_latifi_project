<?php
require '../backoffice/config/connexion.php';

class Register extends Database {

    public function registration($name, $email, $password, $confermpassword) {
        $conn = $this->getConnection(); 

        if ($password !== $confermpassword) {
            return 100; 
        }

        $stmt = $conn->prepare("SELECT * FROM Users WHERE name = :name OR email = :email");
        $stmt->execute(['name' => $name, 'email' => $email]);
        
        if ($stmt->rowCount() > 0) {
            return 10; 
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO Users (name, email, password_hash) VALUES (:name, :email, :password)");
        if ($stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashedPassword])) {
            return 1; 
        } else {
            return 500; 
        }
    }
}

class Login extends Database {
    public function login($name, $password) {
        $conn = $this->getConnection(); 

        $stmt = $conn->prepare("SELECT * FROM Users WHERE name = :name");
        $stmt->execute(['name' => $name]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if (password_verify($password, $row["password_hash"])) {
                session_start();
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];
                
                
                header('Location: ../frontoffice/home.php');
                exit;
            } else {
                return 10; 
            }
        } else {
            return 100; 
        }
    }
}
?>
