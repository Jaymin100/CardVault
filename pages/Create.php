    <?php include '../navbar.php'; ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
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
            <input type="text" id="Filter1" name="Filter1"><br>
        </div>
        <div class="top-text">
            <label for="Filter2">Filter2:</label><br>
            <input type="text" id="Filter2" name="Filter2"><br>
        </div>
        <div class="top-text">
            <label for="Filter3">Filter3:</label><br>
            <input type="text" id="Filter3" name="Filter3"><br>
        </div>
        <div class="top-text">
        <button class="button" for="SetCard">Set Card Count</button>
        <input type="text" id="SetCard" name="SetCard"  style="width: 50px;"><br>
        <label>There can only be a max of 200 cards in a set and changing count erases all cards!</label><br>

        
        </div>
        



       <button class="button" style="margin-top: auto;">Submit</button>


    <div class="create-box-container">
    <div class="set-name-wrapper">
    <label for="set_name">Set Name</label><br>
    <input class="top-input" type="text" id="set_name" name="set_name" placeholder="Enter Your Set Name" />
</div>
            <div class="grid-inputs">
                <script>
                    const setCardButton = document.querySelector('button[for="SetCard"]');
                    const setCardInput = document.getElementById('SetCard');
                    const gridContainer = document.querySelector('.grid-inputs');

                    setCardButton.addEventListener('click', function(e) {
    e.preventDefault(); // Prevent page refresh if button is inside a form

    const cardCount = parseInt(setCardInput.value);
    let ValidCount = false;
    if (isNaN(cardCount) || cardCount <= 0 || cardCount > 200) {
        alert("Please enter a valid number between 1 and 200.");
        ValidCount = false;
        return;
    }else{ValidCount = true}
    if(ValidCount === true){
    gridContainer.innerHTML = ''; // Clear previous inputs
     }
    for (let i = 1; i <= cardCount; i++) {
        const Index = document.createElement('p');
        Index.textContent = i;

        const input1 = document.createElement('textarea');
        input1.name = `question_${i}`;
        input1.placeholder = 'Question';
        input1.classList.add('large-input');

        const input2 = document.createElement('textarea');
        input2.name = `answer_${i}`;
        input2.placeholder = 'Answer';
        input2.classList.add('large-input');

        gridContainer.appendChild(Index);
        gridContainer.appendChild(input1);
        gridContainer.appendChild(input2);
    }
});

                </script>
            </div>
            
            <!-- Add Submit Button to upload data -->
        </form>
    </div>

    </body>
    </html>
