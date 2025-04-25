<?php
include 'dbConnection.php';
include '../classes/Set.php';
include '../classes/Card.php';

// Get and sanitize basic form data
$private   = isset($_POST['pri_pub']) && $_POST['pri_pub'] === 'true';
$filter1   = $_POST['Filter1'] ?? '';
$filter2   = $_POST['Filter2'] ?? '';
$filter3   = $_POST['Filter3'] ?? '';
$set_name  = $_POST['set_name'] ?? '';
$username  = "1234";  // Replace with session username
$user_id   = 1234;    // Replace with session user ID

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
$stmt->bind_param("sssissi", $filter1, $filter2, $filter3, $user_id, $set_name, $username, $private);
if (!$stmt->execute()) {
    die("Failed to insert set: " . $connection->error);
}
$set_id = $stmt->insert_id;

// Insert cards
$insert_card_sql = "INSERT INTO cards (set_id, question, answer) VALUES (?, ?, ?)";
$stmt_card = $connection->prepare($insert_card_sql);

foreach ($cards as $card) {
    $question = $card->getQuestion();
    $answer   = $card->getAnswer();
    $stmt_card->bind_param("iss", $set_id, $question, $answer);
    if (!$stmt_card->execute()) {
        echo "<p>Error inserting card: " . $connection->error . "</p>";
    }
}

// Redirect to success page to avoid resubmission
header("Location: success.php?set_id=$set_id");
exit();
