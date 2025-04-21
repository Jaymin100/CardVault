    <?php include '../navbar.php'; ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Create Set</title>
    <link rel="stylesheet" href="Create.css">
    </head>
    <body>

    <div class="create-bar-container">
        <div class="top-text">
            <input type="radio" id="public" name="pri_pub" value="PUBLIC">
            <label for="public">Public</label>
            <input type="radio" id="private" name="pri_pub" value="PRIVATE">
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
       <button class="bottom-button">Submit</button>

    </div>

    <div class="create-box-container">
        <form id="question-form" action="your_upload_handler.php" method="POST">
        <div style="text-align: center; margin-bottom: 20px;">
    <label for="set_name">Set Name</label><br>
    <input class="top-input" type="text" id="set_name" name="set_name" placeholder="Top text box" style="width: 80%;" />
    </div>
            <div class="grid-inputs">
                <script>
                    const gridContainer = document.querySelector('.grid-inputs');
                    for (let i = 1; i <= 50; i++) {
                        const Index = document.createElement('p');
                        Index.textContent = i;

                        const input1 = document.createElement('textarea');
                        input1.name = `question_${i}`;  // Dynamically generate unique names for questions
                        input1.placeholder = 'Question';
                        input1.classList.add('large-input');

                        const input2 = document.createElement('textarea');
                        input2.name = `answer_${i}`;  // Dynamically generate unique names for answers
                        input2.placeholder = 'Answer';
                        input2.classList.add('large-input');

                        gridContainer.appendChild(Index);
                        gridContainer.appendChild(input1);
                        gridContainer.appendChild(input2);
                    }
                </script>
            </div>
            
            <!-- Add Submit Button to upload data -->
        </form>
    </div>

    </body>
    </html>
