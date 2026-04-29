<?php
class Notification {
    private ?PDO $conn;
    private string $table = 'notifications'; // Assume table exists

    public function __construct(?PDO $db) {
        $this->conn = $db;
    }

    public function getNotifications(int $user_id): array {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getNotificationCount(int $user_id): int {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function markAsRead(int $user_id): bool {
        $query = "UPDATE " . $this->table . " SET is_read = 1 WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}
?>

