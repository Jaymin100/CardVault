<?php
session_start(); // Ensure you start the session to access the user info
include '../navbar.php'; 
include '../classes/Set.php';
include '../classes/Card.php';
include 'dbConnection.php';


// Check if the user is logged in
//$user_id = $_SESSION['user_id'] ?? null;
$user_id = '1234'; // Placeholder for user ID, replace with actual session logic
if (!$user_id) {
    echo "<div style='color: red;'>You must be logged in to view your sets.</div>";
    exit();
}

// Check if a specific set_id is passed in the URL
$set_ID = $_GET['set_id'] ?? null;

if ($set_ID) {
    // Fetch the specific set from the database if set_id is provided
    $set_query = "SELECT * FROM all_sets WHERE set_ID = ? AND account_ID = ?";
    $stmt = $connection->prepare($set_query);
    $stmt->bind_param("ii", $set_ID, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<div style='color: red;'>Set not found or not created by you.</div>";
        exit();
    }

    $set_data = $result->fetch_assoc();
    $set = new Set();
    $set->setSetName($set_data['set_name']);
    $set->setSetPrivate($set_data['priv']);
    $set->setSetFilter1($set_data['filter_1']);
    $set->setSetFilter2($set_data['filter_2']);
    $set->setSetFilter3($set_data['filter_3']);
    $set->setSetUsername($set_data['username']);
    $set->setSetID($set_data['set_ID']);
    $set->setSetUserID($set_data['account_ID']);

    // Fetch cards for this specific set
    $card_query = "SELECT * FROM cards WHERE set_ID = ?";
    $card_stmt = $connection->prepare($card_query);
    $card_stmt->bind_param("i", $set_ID);
    $card_stmt->execute();
    $card_result = $card_stmt->get_result();

    $cards = [];
    while ($card_data = $card_result->fetch_assoc()) {
        $card = new Card($card_data['question'], $card_data['answer']);
        $cards[] = $card;
    }
    $set->setSetCards($cards);

    // Convert the cards to a JavaScript-friendly format
    $cards_js = [];
    foreach ($set->getSetCards() as $card) {
        $cards_js[] = [
            'front' => $card->getQuestion(),
            'back' => $card->getAnswer()
        ];
    }

    // Display the set's flashcards
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <link rel="stylesheet" href="Styles.css">
    <head>
        <meta charset="UTF-8">
        <title>Flashcard Viewer</title>
        <style>
            .card-wrapper {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                margin-top: 0px;
            }

            .flashcard-navigation {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .set-name {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 10px;
                text-align: center;
            }
        </style>
    </head>
    <body>

    <div class="card-wrapper">
        <div class="set-name" id="setName"><?php echo htmlspecialchars($set->getSetName()); ?></div>
        <div class="flashcard-navigation">
            <div class="arrow arrow-left" onclick="prevCard()">&#8592;</div>

            <div class="flashcard-container" onclick="flipCard()">
                <!-- Add a div for the set name -->
                <div class="card-text" id="cardText">Loading...</div>
            </div>

            <div class="arrow arrow-right" onclick="nextCard()">&#8594;</div>
        </div>

        <div class="card-counter" id="cardCounter">Card 1 of 3</div>
    </div>

    <script>
        const cards = <?php echo json_encode($cards_js); ?>;

        let currentIndex = 0;
        let showingFront = true;

        function updateCard() {
            const card = cards[currentIndex];
            document.getElementById('cardText').textContent = showingFront ? card.front : card.back;

            // Update the card counter
            document.getElementById('cardCounter').textContent = `Card ${currentIndex + 1} of ${cards.length}`;
        }

        function flipCard() {
            showingFront = !showingFront;
            updateCard();
        }

        function prevCard() {
            currentIndex = (currentIndex - 1 + cards.length) % cards.length;
            showingFront = true;
            updateCard();
        }

        function nextCard() {
            currentIndex = (currentIndex + 1) % cards.length;
            showingFront = true;
            updateCard();
        }

        // Initialize
        updateCard();
    </script>
    </body>
    </html>
    <?php
} else {
    // Fetch all sets created by the logged-in user if no set_id is provided
    $set_query = "SELECT * FROM all_sets WHERE account_ID = ?";
    $stmt = $connection->prepare($set_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sets = [];
    while ($set_data = $result->fetch_assoc()) {
        $set = new Set();
        $set->setSetName($set_data['set_name']);
        $set->setSetPrivate($set_data['priv']);
        $set->setSetFilter1($set_data['filter_1']);
        $set->setSetFilter2($set_data['filter_2']);
        $set->setSetFilter3($set_data['filter_3']);
        $set->setSetUsername($set_data['username']);
        $set->setSetID($set_data['set_ID']);
        $set->setSetUserID($set_data['account_ID']);

        $sets[] = $set;
    }

    if (count($sets) === 0) {
        echo "<div>No sets found for your account.</div>";
        exit();
    }

    // Display the sets as notecards
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <link rel="stylesheet" href="Search.css">
    <head>
        <meta charset="UTF-8">
        <title>Your Flashcard Sets</title>
    </head>
    <body>

    <h1>Your Flashcard Sets</h1>

    <!-- Display Sets as Notecards -->
    <div class="notecard-wrapper">
            <?php foreach ($sets as $set): ?>
                <div class="notecard">
                    <a href="View.php?set_id=<?= $set->getSetID() ?>" class="notecard-link">
                        <div class="notecard-header">
                            <h3 class="notecard-title"><?= htmlspecialchars($set->getSetName(), ENT_QUOTES) ?></h3>
                            <p class="notecard-author">by <?= htmlspecialchars($set->getSetUsername(), ENT_QUOTES) ?></p>
                        </div>
                        <div class="notecard-body">
                            <p class="notecard-tags">
                                Tags: <?= htmlspecialchars(implode(', ', array_filter([$set->getSetFilter1(), $set->getSetFilter2(), $set->getSetFilter3()])), ENT_QUOTES) ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    </body>
    </html>
    <?php
}
?>

