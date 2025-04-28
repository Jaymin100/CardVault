<?php
session_start();

// Connect to database
$db_server = "localhost";
$db_user = "root";
$db_pass = "mysql";
$db_name = "CardVault";
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
    $stmt->bind_param("ss", $uname_in, $hashed_password);
    $result = $stmt->execute();

    if (!$result) die("Fatal Error");
}

function is_in_database($uname_in): bool {
    global $conn;
    $uname_in = $conn->real_escape_string($uname_in);
    $query = "SELECT 1 FROM user WHERE username = '$uname_in' LIMIT 1";
    $result = $conn->query($query);

    if (!$result) die("Fatal error");

    if ($result->num_rows > 0) {
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
