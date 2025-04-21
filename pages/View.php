<?php include '../navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="Styles.css">
<head>
  <meta charset="UTF-8">
  <title>Flashcard Viewer</title>
  <style>
 
  </style>
</head>
<body>

<div class="card-wrapper">
  <div class="arrow arrow-left" onclick="prevCard()">&#8592;</div>

  <div class="flashcard-container" onclick="flipCard()">
    <div class="card-text" id="cardText">Loading...</div>
  </div>

  <div class="arrow arrow-right" onclick="nextCard()">&#8594;</div>
</div>
  <script>
    // Example cards – you can pull these from PHP/MySQL later
    const cards = [
      { front: "What is the capital of France?", back: "Paris" },
      { front: "What is 5 + 7?", back: "12" },
      { front: "What color do you get when you mix red and blue?", back: "Purple" }
    ];

    let currentIndex = 0;
    let showingFront = true;

    function updateCard() {
      const card = cards[currentIndex];
      document.getElementById('cardText').textContent = showingFront ? card.front : card.back;
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
