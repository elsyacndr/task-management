<?php
require_once 'config/database.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);

$userData = $user->getUserById(1); // Assuming user_id 1
echo "<pre>";
print_r($userData);
echo "</pre>";

if (isset($userData['photo'])) {
    echo "Photo path in DB: " . $userData['photo'] . "\n";
    echo "Full URL: http://localhost/task-management/assets/uploads/profiles/" . $userData['photo'] . "\n";
} else {
    echo "No photo in DB\n";
}
?>

