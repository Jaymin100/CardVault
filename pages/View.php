<?php
include '../navbar.php';
include '../classes/Set.php';
include '../classes/Card.php';
include 'dbConnection.php';

// Placeholder for login check (replace with actual logic)
$user = unserialize($_SESSION['user']);
$user_id = $user->getAccountID(); // Assuming you have a method to get the user ID
//$user_id = '1234';
if (!$user_id) {
    echo "<div style='color: red;'>You must be logged in to view your sets.</div>";
    exit();
}

$set_ID = $_GET['set_id'] ?? null;

if ($set_ID) {
    // View a specific flashcard set
    $stmt = $connection->prepare("SELECT * FROM all_sets WHERE set_ID = ? AND account_ID = ?");
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
    $set->setSetID($set_data['set_ID']);
    $set->setSetUserID($set_data['account_ID']);

    $card_stmt = $connection->prepare("SELECT * FROM cards WHERE set_ID = ?");
    $card_stmt->bind_param("i", $set_ID);
    $card_stmt->execute();
    $card_result = $card_stmt->get_result();

    $cards = [];
    while ($card_data = $card_result->fetch_assoc()) {
        $cards[] = [
            'front' => $card_data['question'],
            'back' => $card_data['answer']
        ];
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= htmlspecialchars($set->getSetName()) ?></title>
        <link rel="stylesheet" href="Search.css">
    </head>
    <body>

    <div class="card-wrapper">
        <div class="set-name"><?= htmlspecialchars($set->getSetName()) ?></div>
        <div class="flashcard-navigation">
            <div class="arrow" onclick="prevCard()">&#8592;</div>

            <div class="flashcard" id="flashcard" onclick="flipCard()">
                <div class="front" id="cardFront">Loading...</div>
                <div class="back" id="cardBack">Loading...</div>
            </div>

            <div class="arrow" onclick="nextCard()">&#8594;</div>
        </div>

        <div class="card-counter" id="cardCounter">Card 1 of <?= count($cards) ?></div>
    </div>

    <script>
        const cards = <?= json_encode($cards) ?>;
        let currentIndex = 0;
        let showingFront = true;

        function updateCard() {
            const card = cards[currentIndex];
            document.getElementById('cardFront').textContent = card.front;
            document.getElementById('cardBack').textContent = card.back;
            document.getElementById('cardCounter').textContent = `Card ${currentIndex + 1} of ${cards.length}`;
            
            // Reset the flip to front first, then apply flip (delayed to ensure content is loaded)
            setTimeout(() => {
                document.getElementById('flashcard').classList.remove('flipped');
                showingFront = true;
            }, 0);
        }

        function flipCard() {
            const cardEl = document.getElementById('flashcard');
            cardEl.classList.toggle('flipped');
            showingFront = !showingFront;
        }

        function prevCard() {
            // First flip the current card to front if it's showing the back
            if (!showingFront) {
                const cardEl = document.getElementById('flashcard');
                cardEl.classList.toggle('flipped');
                showingFront = true;
                
                // Wait for the flip to complete before going to the previous card
                setTimeout(() => {
                    // Update the card index after the flip is complete
                    currentIndex = (currentIndex - 1 + cards.length) % cards.length;
                    updateCard();
                }, 100);  // Adjust delay based on your flip animation duration
            } else {
                // If the card is already showing the front, just go to the previous card
                currentIndex = (currentIndex - 1 + cards.length) % cards.length;
                updateCard();
            }
        }

        function nextCard() {
            // First flip the current card to front
            if (!showingFront) {
                const cardEl = document.getElementById('flashcard');
                cardEl.classList.toggle('flipped');
                showingFront = true;
                
                // Wait for the flip to complete before going to the next card
                setTimeout(() => {
                    // Update the card index after the flip is complete
                    currentIndex = (currentIndex + 1) % cards.length;
                    updateCard();
                }, 100);  // Adjust delay based on your flip animation duration
            } else {
                // If the card is already showing the front, just go to the next card
                currentIndex = (currentIndex + 1) % cards.length;
                updateCard();
            }
        }

        updateCard();
    </script>
    </body>
    </html>
    <?php
} else {
    // Show all sets for the user
    $stmt = $connection->prepare("SELECT * FROM all_sets WHERE account_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sets = [];
    while ($row = $result->fetch_assoc()) {
        $sets[] = $row;
    }

    if (empty($sets)) {
        // Display no sets message with link to create new set
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Your Flashcard Sets</title>
            <link rel="stylesheet" href="Search.css">
        </head>
        <body>
            <h1 style="text-align:center;">No Flashcard Sets Found</h1>
            <p style="text-align:center;">You haven't created any sets yet.</p>
            <div style="text-align:center; margin-top:20px;">
                <a href="Create.php" style="text-decoration:none; background-color:#457776; color:white; padding:10px 20px; border-radius:5px;">Create One Now</a>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Your Flashcard Sets</title>
        <link rel="stylesheet" href="Search.css">
    </head>
    <body>
        <h1 style="text-align:center;">Your Flashcard Sets</h1>
        <div class="notecard-wrapper">
            <?php foreach ($sets as $set): ?>
                <div class="notecard">
                    <a href="View.php?set_id=<?= $set['set_ID'] ?>" class="notecard-link">
                        <div class="notecard-header">
                            <h3 class="notecard-title"><?= htmlspecialchars($set['set_name']) ?></h3>
                            <p class="notecard-author">by <?= htmlspecialchars($set['username']) ?></p>
                        </div>
                        <div class="notecard-body">
                            <p class="notecard-tags">
                                Tags: <?= htmlspecialchars(implode(', ', array_filter([$set['filter_1'], $set['filter_2'], $set['filter_3']]))) ?>
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
