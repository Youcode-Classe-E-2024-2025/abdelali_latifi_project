<?php
require '../backoffice/config/connexion.php';

class Register extends Database {

    public function registration($name, $email, $password, $confirmPassword) {
        $conn = $this->getConnection();

        if ($password !== $confirmPassword) {
            return "Les mots de passe ne correspondent pas.";
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE name = :name OR email = :email");
        $stmt->execute(['name' => $name, 'email' => $email]);

        if ($stmt->rowCount() > 0) {
            return "Le nom d'utilisateur ou l'email existe déjà.";
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)");
        if ($stmt->execute(['name' => $name, 'email' => $email, 'password_hash' => $hashedPassword])) {
            return "Enregistrement réussi.";
        } else {
            return "Une erreur est survenue lors de l'enregistrement.";
        }
    }
}

class Login extends Database {
    private $id;

    public function login($name, $password) {
        $conn = $this->getConnection();

        $stmt = $conn->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->execute(['name' => $name]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if (password_verify($password, $row["password_hash"])) {
                $this->id = $row["user_id"];
                return "Connexion réussie.";
            } else {
                return "Mot de passe incorrect.";
            }
        } else {
            return "Nom d'utilisateur non trouvé.";
        }
    }

    public function idUser() {
        return $this->id;
    }
}
?>
