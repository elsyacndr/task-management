<?php
class Task {
    private ?PDO $conn;
    private string $table = 'tasks';

    public function __construct(?PDO $db) {
        $this->conn = $db;
    }


    public function createTask(int $user_id, ?int $project_id, string $title, string $description, string $category, string $status = 'pending', ?string $due_date = null) {


        $query = "INSERT INTO " . $this->table . " (user_id, project_id, title, description, category, status, due_date) VALUES (:user_id, :project_id, :title, :description, :category, :status, :due_date)";

        $stmt = $this->conn->prepare($query);

        $title = htmlspecialchars(strip_tags($title));
        $description = htmlspecialchars(strip_tags($description));
        $status = htmlspecialchars(strip_tags($status));


        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':due_date', $due_date);


        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getTasks(int $user_id, string $status = '') {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        if(!empty($status)) {
            $query .= " AND status = :status";
        }
        $query .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);

        if(!empty($status)) {
            $stmt->bindParam(':status', $status);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTaskById(int $id, int $user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateTask(int $id, int $user_id, string $title, string $description, string $status) {
        $query = "UPDATE " . $this->table . " SET title = :title, description = :description, status = :status WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $title = htmlspecialchars(strip_tags($title));
        $description = htmlspecialchars(strip_tags($description));
        $status = htmlspecialchars(strip_tags($status));

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteTask(int $id, int $user_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateStatus(int $id, int $user_id, string $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

public function getTaskStats(int $user_id) {
        $stats = array('total' => 0, 'completed' => 0, 'pending' => 0, 'overdue' => 0);

        $queryTotal = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE user_id = :user_id";
        $stmtTotal = $this->conn->prepare($queryTotal);
        $stmtTotal->bindParam(':user_id', $user_id);
        $stmtTotal->execute();
        $stats['total'] = $stmtTotal->fetch()['total'];

        $queryCompleted = "SELECT COUNT(*) as completed FROM " . $this->table . " WHERE user_id = :user_id AND status = 'completed'";
        $stmtCompleted = $this->conn->prepare($queryCompleted);
        $stmtCompleted->bindParam(':user_id', $user_id);
        $stmtCompleted->execute();
        $stats['completed'] = $stmtCompleted->fetch()['completed'];

        $queryPending = "SELECT COUNT(*) as pending FROM " . $this->table . " WHERE user_id = :user_id AND status = 'pending'";
        $stmtPending = $this->conn->prepare($queryPending);
        $stmtPending->bindParam(':user_id', $user_id);
        $stmtPending->execute();
        $stats['pending'] = $stmtPending->fetch()['pending'];

        $queryOverdue = "SELECT COUNT(*) as overdue FROM " . $this->table . " WHERE user_id = :user_id AND status = 'pending' AND due_date < NOW()";
        $stmtOverdue = $this->conn->prepare($queryOverdue);
        $stmtOverdue->bindParam(':user_id', $user_id);
        $stmtOverdue->execute();
        $stats['overdue'] = $stmtOverdue->fetch()['overdue'];

        return $stats;
    }

    public function getOverdueTasks(int $user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id AND status = 'pending' AND due_date < NOW() ORDER BY due_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function isTaskOverdue(int $task_id, int $user_id): bool {
        $query = "SELECT due_date FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $task_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $task = $stmt->fetch();
        if ($task && $task['due_date'] && strtotime($task['due_date']) < time()) {
            return true;
        }
        return false;
    }
}
?>


