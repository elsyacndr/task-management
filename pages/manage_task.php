<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/Task.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$database = new Database();
$db = $database->connect();
$task = new Task($db);

if(isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    if($action == 'delete') {
        if($task->deleteTask($id, $user_id)) {
            header("Location: tasks.php?message=Task deleted successfully&type=success");
        } else {
            header("Location: tasks.php?message=Failed to delete task&type=danger");
        }
        exit();
    }

    if($action == 'complete') {
        if($task->updateStatus($id, $user_id, 'completed')) {
            header("Location: tasks.php?message=Task marked as completed&type=success");
        } else {
            header("Location: tasks.php?message=Failed to update status&type=danger");
        }
        exit();
    }

    if($action == 'pending') {
        if($task->updateStatus($id, $user_id, 'pending')) {
            header("Location: tasks.php?message=Task marked as pending&type=success");
        } else {
            header("Location: tasks.php?message=Failed to update status&type=danger");
        }
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id'];

    if($task->updateTask($id, $user_id, $title, $description, $status)) {
        header("Location: tasks.php?message=Task updated successfully&type=success");
    } else {
        header("Location: tasks.php?message=Failed to update task&type=danger");
    }
    exit();
}

header("Location: tasks.php");
exit();
?>

