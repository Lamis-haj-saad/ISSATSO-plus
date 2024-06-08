<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quizTitle = $_POST['quizTitle'];
    $questions = $_POST['question'];
    $answers = $_POST['answers'];

    echo "<h1>Quiz Submitted</h1>";
    echo "<h2>Title: $quizTitle</h2>";
    foreach ($questions as $key => $question) {
        echo "<h3>Question: $question</h3>";
        foreach ($answers[$key] as $answer) {
            echo "<p>Answer: $answer</p>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Quiz</title>
<link rel="stylesheet" href="style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<h1>Create a New Quiz</h1>
<form id="quizForm" method="POST" action="submit_quiz.php">
    <label for="quizTitle">Quiz Title:</label>
    <input type="text" id="quizTitle" name="quizTitle" required><br><br>

    <div id="questionContainer">
        <!-- Question entries will go here -->
    </div>

    <button type="button" id="addQuestion">Add Question</button>
    <br><br>
    <input type="submit" value="Submit Quiz">
</form>

<script>
$(document).ready(function() {
    let questionNumber = 0;

    $('#addQuestion').click(function() {
        questionNumber++;
        $('#questionContainer').append(
            `<div class="questionBlock">
                <h3>Question ` + questionNumber + `:</h3>
                <input type="text" name="question[` + questionNumber + `]" placeholder="Enter question" required>
                <div class="answerContainer" id="answerContainer` + questionNumber + `"></div>
                <button type="button" onclick="addAnswer(` + questionNumber + `)">Add Answer</button>
            </div><br>`
        );
    });
});

function addAnswer(questionNumber) {
    let answerID = `answerContainer${questionNumber}`;
    let answerNum = $(`#${answerID} div`).length + 1;
    $(`#${answerID}`).append(
        `<div>
            <label for="answers` + questionNumber + `[]">Answer ` + answerNum + `:</label>
            <input type="text" name="answers[` + questionNumber + `][]" required>
        </div>`
    );
}
</script>
</body>
</html>
