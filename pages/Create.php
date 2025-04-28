    <?php include '../navbar.php'; ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <style>
body {
  background-image: url('../create_background.jpg');
  background-size: cover;
  background-position: center;
}
</style>
        
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <title>Create Set</title>
    <link rel="stylesheet" href="Create.css">
    </head>
    <body>
    <form id="question-form" action="test.php" method="POST">
    <div class="create-bar-container">
        <div class="top-text">
            <input type="radio" id="public" name="pri_pub" value="false">
            <label for="public">Public</label>
            <input type="radio" id="private" name="pri_pub" value="true" checked="checked">
            <label for="private">Private</label>
        </div>

        <div class="top-text">
            <label for="Filter1">Filter1:</label><br>
            <input type="text" id="Filter1" name="Filter1" class="filter-input"><br>
        </div>
        <div class="top-text">
            <label for="Filter2">Filter2:</label><br>
            <input type="text" id="Filter2" name="Filter2"class="filter-input"><br>
        </div>
        <div class="top-text">
            <label for="Filter3">Filter3:</label><br>
            <input type="text" id="Filter3" name="Filter3"class="filter-input"><br>
        </div>
 
        <div class="top-text">
        <button class="button" for="SetCard">Set Card Count</button>
        <input type="text" id="SetCard" name="SetCard"  style="width: 50px;"class="filter-input"><br>
        <label>Max of 200 Cards. setting the count erases current cards!</label><br>
        <button class="button" for="AddCard" style="margin-bottom: -10px ;">Add card</button>
    
        
        

        
        </div>
        



       <button class="button" style=" margin-bottom: 10px;">Submit</button>


    <div class="create-box-container">
    <div class="set-name-wrapper">
    <label for="set_name">Set Name</label><br>
    <input class="top-input" type="text" id="set_name" name="set_name" placeholder="Enter Your Set Name" required/>
</div>
            <div class="grid-inputs">
            <script>
    const setCardButton = document.querySelector('button[for="SetCard"]');
    const addCardButton = document.querySelector('button[for="AddCard"]');
    const setCardInput = document.getElementById('SetCard');
    const gridContainer = document.querySelector('.grid-inputs');

    let currentCardCount = 0;

    function reindexCards() {
    const cards = document.querySelectorAll('.card');

    cards.forEach((card, index) => {
        const newIndex = index + 1; // Start at 1

        // Update visible card number
        const cardNumber = card.querySelector('p');
        if (cardNumber) {
            cardNumber.textContent = `Card ${newIndex}`;
        }

        // Update input names
        const questionInput = card.querySelector('textarea[name^="question_"]');
        const answerInput = card.querySelector('textarea[name^="answer_"]');

        if (questionInput) questionInput.name = `question_${newIndex}`;
        if (answerInput) answerInput.name = `answer_${newIndex}`;
    });
}
    function createCard(index) {
    const cardContainer = document.createElement('div');
    cardContainer.classList.add('card'); // Treat as a single grid item

    const cardNumber = document.createElement('p');
    cardNumber.textContent = `Card ${index}`;

    const questionInput = document.createElement('textarea');
    questionInput.name = `question_${index}`;
    questionInput.placeholder = 'Question';
    questionInput.classList.add('large-input');
    questionInput.required = true;

    const answerInput = document.createElement('textarea');
    answerInput.name = `answer_${index}`;
    answerInput.placeholder = 'Answer';
    answerInput.classList.add('large-input');
    answerInput.required = true;

    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Delete';
    deleteButton.type = 'button';
    deleteButton.classList.add('delete-button');
    deleteButton.addEventListener('click', function () {
        cardContainer.remove();
        reindexCards();
        // Optionally reduce count or reindex
    });

    cardContainer.appendChild(cardNumber);
    cardContainer.appendChild(questionInput);
    cardContainer.appendChild(answerInput);
    cardContainer.appendChild(deleteButton);

    gridContainer.appendChild(cardContainer);
}


    setCardButton.addEventListener('click', function (e) {
        e.preventDefault();

        const cardCount = parseInt(setCardInput.value);
        if (isNaN(cardCount) || cardCount <= 0 || cardCount > 200) {
            alert("Please enter a valid number between 1 and 200.");
            return;
        }

        gridContainer.innerHTML = '';
        currentCardCount = 0;

        for (let i = 1; i <= cardCount; i++) {
            createCard(i);
            currentCardCount++;
        }
    });

    addCardButton.addEventListener('click', function (e) {
        e.preventDefault();
        if (currentCardCount >= 200) {
            alert("Maximum of 200 cards reached.");
            return;
        }
        currentCardCount++;
        createCard(currentCardCount);
        reindexCards();
    });
    new Sortable(gridContainer, {
        animation: 150,
        onEnd: function (evt) {
            reindexCards(); // Re-index cards after drag
        }
    });
</script>

            </div>

            <!-- Add Submit Button to upload data -->
        </form>
    </div>

    </body>
    </html>
