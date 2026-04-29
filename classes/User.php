<?php
class User {
    private ?PDO $conn;
    private string $table = 'users';

    public function __construct(?PDO $db) {
        $this->conn = $db;
    }

    public function register(string $name, string $email, string $password): bool {
        $query = "INSERT INTO " . $this->table . " (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        return $stmt->execute();
    }

    public function login(string $email, string $password): array|false {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch();
        if($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function emailExists(string $email): bool {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function getUserById(int $id): array|false {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function updateProfile(int $id, string $name, string $email, ?string $photo = null): bool {
        // Check if email is taken by another user
        $checkQuery = "SELECT id FROM " . $this->table . " WHERE email = :email AND id != :id LIMIT 1";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->bindParam(':id', $id);
        $checkStmt->execute();
        if ($checkStmt->rowCount() > 0) {
            return false;
        }

        $setPhoto = $photo ? ', photo = :photo' : '';
        $query = "UPDATE " . $this->table . " SET name = :name, email = :email" . $setPhoto . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        if ($photo) {
            $stmt->bindParam(':photo', $photo);
        }

        return $stmt->execute();
    }
}
?>
