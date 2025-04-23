<?php 
include '../navbar.php'; 
include '../classes/Set.php';
include '../classes/Card.php';

// Example: Create a Set object and populate it with cards (replace this with actual database logic)
$set = new Set();
$set->setSetName("Example Set");
$set->setSetCards([
    new Card("What is the capital of France?", "Paris"),
    new Card("What is 5 + 7?", "12"),
    new Card("What color do you get when you mix red and blue?", "Purple")
]);

// Convert the cards to a JavaScript-friendly format
$cards = [];
foreach ($set->getSetCards() as $card) {
    $cards[] = [
        'front' => $card->getQuestion(),
        'back' => $card->getAnswer()
    ];
}
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
  const cards = <?php echo json_encode($cards); ?>;

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



