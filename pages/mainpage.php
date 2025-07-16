<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'session_security.php';
include 'csrf.php';
include 'dbConnection.php';
require_once '../classes/User.php'; // include your class

// Rate limiting setup
$max_attempts = 5;
$lockout_time = 15 * 60; // 15 minutes

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

if (
    $_SESSION['login_attempts'] >= $max_attempts &&
    (time() - $_SESSION['last_attempt_time']) < $lockout_time
) {
    die("Too many failed login attempts. Please try again later.");
}

verify_csrf_token();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['uname'];
    $password = $_POST['pword'];

    $stmt = $connection->prepare("SELECT * FROM user WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        if (password_verify($password, $row['user_password'])) {
            session_regenerate_id(true); // Regenerate session ID after successful login
            // Create and store user object
            $user = new User();
            $user->setUser($row['username']);
            $user->setAccountID($row['account_ID']); // if applicable

            $_SESSION['user'] = serialize($user); // Store serialized object
            $_SESSION['login_attempts'] = 0; // Reset on success
            header("Location: View.php");
            exit();
        } else {
            $_SESSION['login_attempts'] += 1;
            $_SESSION['last_attempt_time'] = time();
            echo "Invalid password.";
        }
    } else {
        $_SESSION['login_attempts'] += 1;
        $_SESSION['last_attempt_time'] = time();
        echo "User not found.";
    }
}
?>
