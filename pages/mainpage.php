<?php
session_start();
include 'dbConnection.php';
require_once '../classes/User.php'; // include your class

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['uname'];
    $password = $_POST['pword'];

    $stmt = $connection->prepare("SELECT * FROM user WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        if (password_verify($password, $row['user_password'])) {
            // Create and store user object
            $user = new User();
            $user->setUser($row['username']);
            $user->setAccountID($row['account_ID']); // if applicable

            $_SESSION['user'] = serialize($user); // Store serialized object

            header("Location: View.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>
