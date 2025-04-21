<?php
include '../classes/Set.php';
include '../classes/Card.php';
// Get form data from the POST request

$_POST['pri_pub'] = isset($_POST['pri_pub']) && $_POST['pri_pub'] === 'true';
$private = $_POST['pri_pub']; // $private is now a boolean

$filter1 = $_POST['Filter1'] ?? '';
$filter2 = $_POST['Filter2'] ?? '';
$filter3 = $_POST['Filter3'] ?? '';
$set_name = $_POST['set_name'] ?? '';
$set_card_count = $_POST['SetCard'] ?? 0;

// Handle dynamic questions and answers
$questions = [];
$answers = [];
for ($i = 1; $i <= $set_card_count; $i++) {
    $question = $_POST["question_$i"] ?? '';
    $answer = $_POST["answer_$i"] ?? '';
    $questions[] = $question;
    $answers[] = $answer;

    // Create a Card object for each question and answer
    $card = new Card($question, $answer);
    $cards[] = $card;
}
$set = new Set();
$set->setSetCards($cards);
$set->setSetName($set_name);
$set->setSetCardCount($set_card_count);
$set->setSetPrivate($private);
$set->setSetFilter1($filter1);
$set->setSetFilter2($filter2);
$set->setSetFilter3($filter3);
$set->setSetUsername("test_user"); // Replace with actual username
$set->setSetID(1);
$set->setSetUserID(1); // Replace with actual user ID


// You can now process this data, save it to a database, or do anything with it.
// For example, you can print the data to check it:
echo "<h1>Form Submitted</h1>";
echo "<p><strong>Set Name:</strong> " . $set->getSetName() . "</p>";
echo "<p><strong>Privacy:</strong> " . $set->getSetPrivate() . "</p>";
echo "<p><strong>Filter1:</strong> ". $set->getSetFilter1() . "</p>";
echo "<p><strong>Filter2:</strong> ".$set->getSetFilter2() ."</p>";
echo "<p><strong>Filter3:</strong> ".$set->getSetFilter3(). "</p>";
echo "<p><strong>Set Card Count:</strong>" .$set->getSetCardCount()."</p>";
// Display the cards in the set

echo "<h2>Questions and Answers:</h2>";
foreach ($set->getSetCards() as $index => $card) {
    echo "<p><strong>Question " . ($index + 1) . ":</strong> " . htmlspecialchars($card->getQuestion()) . "</p>";
    echo "<p><strong>Answer " . ($index + 1) . ":</strong> " . htmlspecialchars($card->getAnswer()) . "</p>";
}








?>
