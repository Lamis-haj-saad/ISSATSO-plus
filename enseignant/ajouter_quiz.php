<?php


// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cursus";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupérer les données du formulaire
  
  $nom = $_POST["nom"];
  $date_d = $_POST["date_d"];
  $date_f = $_POST["date_f"];

  // Vérifier si le quiz existe déjà
  $sql_check_quiz = "SELECT * FROM quiz WHERE nom='$nom'";
  $result_check_quiz = $conn->query($sql_check_quiz);

  if ($result_check_quiz->num_rows > 0) {
      // Le quiz existe déjà, afficher une alerte
      //echo "<script>alert('Le quiz existe déjà !');</script>";
      die('Le quiz existe déjà !');
      header("Location: ajouter_quiz.php?ajoute_success=0");
      exit();
  } else {
      // Le quiz n'existe pas, insérer les données dans la base de données
      $stmt = $conn->prepare("INSERT INTO quiz (nom, date_d, date_f, nbr_r) VALUES (?, ?, ?, 0)");
      $stmt->bind_param("sss", $nom, $date_d, $date_f);
      $stmt->execute();
      $quizId = $conn->insert_id;
      if ($stmt->errno) {  // If there's an error code, then an error occurred
        echo "Erreur: " . $stmt->error . "<br>";
      }/* else {
        header("Location: quiz.php?ajoute_success=1");
        exit();  // Don't forget to call exit after header redirection
      }*/

  
  foreach ($_POST['question'] as $questionNumber => $content) {
      if (!empty($content)) {
          // Insert question into database
           $sqlQuestion = "INSERT INTO question (id_quiz, content) VALUES (?, ?)";
           $stmt = $conn->prepare($sqlQuestion);
           $stmt->bind_param("is", $quizId, $content);
           $stmt->execute();
           $questionId = $stmt->insert_id; // Get the ID of the inserted question

          // Process each response for this question
          //if (!empty($_POST['answers'][$questionNumber])) {
              foreach ($_POST['answers'][$questionNumber] as $answerNum => $answerContent) {
                  if (!empty($answerContent)) {
                    $isCorrect = ($_POST['correct_answer'][$questionNumber] == $answerNum) ? 1 : 0; // Check if this answer is marked as correct
                    $sqlResponse = "INSERT INTO reponse (id_quest, content, valeur) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sqlResponse);
                    $stmt->bind_param("isi", $questionId, $answerContent, $isCorrect);
                    $stmt->execute();                     
                  }
              }
          }
      //}
  }
}
exit();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Boxicons -->
    <link
      href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css"
      rel="stylesheet"
    />
    <!-- My CSS -->
    <link rel="stylesheet" href="../style.css" />

    <!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../css/util.css">
	<link rel="stylesheet" type="text/css" href="../css/main.css">
<!--===============================================================================================-->

	
    <title>ISSATSO/Ajouter_Quiz</title>

  </head>
  <body>
    <!-- SIDEBAR -->
    <section id="sidebar">
      <a href="#" class="brand">
        <i style="margin-left: 30px"></i>
        <span class="text">ISSATSO</span>
      </a>
      <ul class="side-menu top">
        <li >
          <a href="dashboard.html">
            <i class="bx bxs-dashboard"></i>
            <span class="text">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="bx bxs-group"></i>
            <span class="text">Enseignants</span>
          </a>
        </li>
        <li>
          <a href="etudiant.php">
            <i class="bx bxs-group"></i>
            <span class="text">Etudiants</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="bx bxs-message-dots"></i>
            <span class="text">Message</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="bx bxs-group"></i>
            <span class="text">Reclamations</span>
          </a>
        </li>
      </ul>
      <ul class="side-menu">
        <li>
          <a href="#">
            <i class="bx bxs-cog"></i>
            <span class="text">Settings</span>
          </a>
        </li>
        <li>
          <a href="#" class="logout">
            <i class="bx bxs-log-out-circle"></i>
            <span class="text">Logout</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
      <!-- NAVBAR -->
      <nav>
        <i class="bx bx-menu"></i>
        <a href="#" class="nav-link">Categories</a>
        <form action="#">
          <div class="form-input">
            <input type="search" placeholder="Search..." />
            <button type="submit" class="search-btn">
              <i class="bx bx-search"></i>
            </button>
          </div>
        </form>
        <input type="checkbox" id="switch-mode" hidden />
        <label for="switch-mode" class="switch-mode"></label>
        <a href="#" class="notification">
          <i class="bx bxs-bell"></i>
          <span class="num">8</span>
        </a>
        <a href="#" class="profile">
          <img src="img/people.png" />
        </a>
      </nav>
      <!-- NAVBAR -->

      <!-- MAIN -->
      <main>
       
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/bg-01.jpg);">
					<span class="login100-form-title-1">
						Ajouter un Quiz
					</span>
				</div>

				<form class="login100-form validate-form" method="post"> <!--action="quiz_content.php">-->
					<div class="wrap-input100 validate-input m-b-26" data-validate="Name is required">
						<span class="label-input100">Nom</span>
						<input class="input100" type="text" name="nom" placeholder="Saisir Nom" required>
						<span class="focus-input100"></span>
					</div>

          <div class="wrap-input100 validate-input m-b-26" data-validate="Begin date is required">
						<span class="label-input100">Date debut</span>
						<input class="input100" type="text" name="date_d" placeholder="Saisir date de debut">
						<span class="focus-input100"></span>
					</div>

          <div class="wrap-input100 validate-input m-b-26" data-validate="End date is required">
						<span class="label-input100">Date fin</span>
						<input class="input100" type="text" name="date_f" placeholder="Saisir date de fin">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" style="border-bottom: 1px solid #ffffff;" id="questionContainer">
                        <!-- Question entries will go here -->
					</div>


					<div class="container-login100-form-btn">
                        <button type="button" class="login100-form-btn" style="margin-right: 20px" id="addQuestion">
							+ Question
						</button>	
                        <button type="submit" class="login100-form-btn">
							Ajouter Quiz
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
      </main>
      <!-- MAIN -->
    
    </section>
    <!-- CONTENT -->
  
    <script src="../script.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
    <!--===============================================================================================-->
	<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/bootstrap/js/popper.js"></script>
	<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/daterangepicker/moment.min.js"></script>
	<script src="../vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script src="../js/main.js"></script>

<script>
$(document).ready(function() {
    let questionNumber = 0;

    $('#addQuestion').click(function() {
        questionNumber++;
        $('#questionContainer').append(
          `<div class="questionBlock wrap-input100 validate-input m-b-18">
            <span class="label-input100">Question ${questionNumber}</span>
            <input class="input100" type="text" name="question[${questionNumber}]" placeholder="Enter question" required>
            <span class="focus-input100"></span>
          </div>
          <div class="answerContainer" id="answerContainer${questionNumber}"></div>

          <button type="button" class="login100-form-btn" onclick="addAnswer(${questionNumber})">+ Reponse</button>
          <br>`
        );
    });
});
function addAnswer(questionNumber) {
    let answerID = `answerContainer${questionNumber}`;
    let answerNum = $(`#${answerID} div`).length + 1;
    $(`#${answerID}`).append(
        `<div  class="answerBlock wrap-input100 validate-input m-b-18">
            <input style="margin-right: 7px;" type="radio" id="radio${questionNumber}_${answerNum}" name="correct_answer[${questionNumber}]" value="${answerNum}">
            <label style="display: inline;" for="radio${questionNumber}_${answerNum}">Reponse Correcte</label>            <br>
            <input type="text" id="answers${questionNumber}_${answerNum}" name="answers[${questionNumber}][${answerNum}]" placeholder="Enter answer" required>
        
            
            </div>`
    );
}
</script>
  </body>
</html>
