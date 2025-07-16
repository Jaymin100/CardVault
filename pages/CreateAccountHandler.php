<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error/app_errors.log');
error_reporting(E_ALL);

session_start();
include 'dbConnection.php';
include 'csrf.php';
verify_csrf_token();

$db_server = $_ENV['db_server'];
$db_user = $_ENV["db_user"];
$db_pass = $_ENV["db_pass"];
$db_name = $_ENV["db_name"];



$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (isset($_POST['uname']) && isset($_POST['pword']) && isset($_POST['pword_re'])) {
    $prop_uname = $_POST['uname'];
    $prop_pword = $_POST['pword'];
    $prop_pword_re = $_POST['pword_re'];

    $is_valid_uname = is_valid_uname($prop_uname);
    $is_valid_pword = is_valid_pword($prop_pword, $prop_pword_re);
    $is_in_database = is_in_database($prop_uname);

    if ($is_valid_uname && $is_valid_pword && !$is_in_database) {
        insert_info($prop_uname, $prop_pword);

        $_SESSION['success_message'] = "Account created successfully!";
        header("Location: Login.php"); // Redirect
        exit();
    } else {
        $_SESSION['error_message'] = "Registration failed. Try again.";
        header("Location: CreateAccount.php"); // Redirect back if something fails
        exit();
    }
}

function insert_info($uname_in, $pword_in) {
    global $conn;
    $hashed_password = password_hash($pword_in, PASSWORD_DEFAULT);
    $query = "INSERT INTO user (username, user_password) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed in CreateAccountHandler.php: " . $conn->error);
        echo "Sorry, something went wrong. Please try again later.";
        exit();
    }
    $stmt->bind_param("ss", $uname_in, $hashed_password);
    $result = $stmt->execute();

    if (!$result) {
        error_log("Failed to insert user in CreateAccountHandler.php: " . $conn->error);
        echo "Sorry, something went wrong. Please try again later.";
        exit();
    }
}

function is_in_database($uname_in): bool {
    global $conn;
    $query = "SELECT 1 FROM user WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    if(!$stmt) {
        error_log("Prepare failed in is_in_database: " . $conn->error);
        echo "Sorry, something went wrong. Please try again later.";
        exit();
    }
    $stmt->bind_param("s", $uname_in);
    $stmt->execute();
    $result = $stmt->get_result();

    if(!$result) {
        error_log("Query failed in is_in_database: " . $conn->error);
        echo "Sorry, something went wrong. Please try again later.";
        exit();
    }

    if($result->num_rows > 0) {
        $_SESSION['error_message'] = "Username already taken.";
    }
    return $result->num_rows > 0;   
}

function is_valid_uname($uname_in): bool {
    if (strlen($uname_in) < 8) {
        $_SESSION['error_message'] = "Username must be at least 8 characters.";
        return false;
    } elseif (strlen($uname_in) > 16) {
        $_SESSION['error_message'] = "Username cannot be more than 16 characters.";
        return false;
    }
    return true;
}

function is_valid_pword($pword_in, $pword_re_in): bool {
    if (strlen($pword_in) < 8) {
        $_SESSION['error_message'] = "Password must be at least 8 characters.";
        return false;
    } elseif (strlen($pword_in) > 32) {
        $_SESSION['error_message'] = "Password cannot be more than 32 characters.";
        return false;
    } elseif ($pword_in != $pword_re_in) {
        $_SESSION['error_message'] = "Passwords do not match.";
        return false;
    }
    return true;
}
?>
