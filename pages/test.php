<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error/app_errors.log');
error_reporting(E_ALL);

$require_login = true;
include 'session_security.php';
include 'csrf.php';
// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

verify_csrf_token();
include 'dbConnection.php';
include '../classes/Set.php';
include '../classes/Card.php';
include '../navbar.php';
// Get and sanitize basic form data
$private   = isset($_POST['pri_pub']) && $_POST['pri_pub'] === 'true';
$filter1   = $_POST['Filter1'] ?? '';
$filter2   = $_POST['Filter2'] ?? '';
$filter3   = $_POST['Filter3'] ?? '';
$set_name  = $_POST['set_name'] ?? '';


$user = unserialize($_SESSION['user']);
$user_id = $user->getAccountID(); // Assuming you have a method to get the user ID
$username = $user->getUser(); // Assuming you have a method to get the user ID

$cards = [];

// Loop through questions/answers
foreach ($_POST as $key => $value) {
    if (preg_match('/^question_(\d+)$/', $key, $matches)) {
        $index = $matches[1];
        $question = trim($_POST["question_$index"] ?? '');
        $answer   = trim($_POST["answer_$index"] ?? '');
        if (!empty($question) && !empty($answer)) {
            $cards[] = new Card($question, $answer);
        }
    }
}

// Insert set
$insert_set_sql = "INSERT INTO all_sets (filter_1, filter_2, filter_3, account_ID, set_name, username, priv)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $connection->prepare($insert_set_sql);
if (!$stmt) {
    error_log("Prepare failed in test.php: " . $connection->error);
    echo "Sorry, something went wrong. Please try again later.";
    exit();
}
$stmt->bind_param("sssissi", $filter1, $filter2, $filter3, $user_id, $set_name, $username, $private);
if (!$stmt->execute()) {
    error_log("Failed to insert set in test.php: " . $connection->error);
    echo "Sorry, something went wrong. Please try again later.";
    exit();
}
$set_id = $stmt->insert_id;

// Insert cards
$insert_card_sql = "INSERT INTO cards (set_id, question, answer) VALUES (?, ?, ?)";
$stmt_card = $connection->prepare($insert_card_sql);
if (!$stmt_card) {
    error_log("Prepare failed for card insert in test.php: " . $connection->error);
    echo "Sorry, something went wrong. Please try again later.";
    exit();
}

foreach ($cards as $card) {
    $question = $card->getQuestion();
    $answer   = $card->getAnswer();
    $stmt_card->bind_param("iss", $set_id, $question, $answer);
    if (!$stmt_card->execute()) {
        error_log("Error inserting card in test.php: " . $connection->error);
        echo "Sorry, something went wrong. Please try again later.";
        exit();
    }
}

// Redirect to success page to avoid resubmission
header("Location: success.php?set_id=$set_id");
exit();
