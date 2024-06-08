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

$id = $_GET["id"];

$sql_select_single = "SELECT * FROM quiz WHERE id = '$id'";
$result_select_single = $conn->query($sql_select_single);
$row = $result_select_single->fetch_assoc();

// Assuming $id is the quiz ID fetched safely as shown previously
$sql_questions = "SELECT * FROM question WHERE id_quiz = ?";
$stmt_questions = $conn->prepare($sql_questions);
$stmt_questions->bind_param("i", $id);
$stmt_questions->execute();
$result_questions = $stmt_questions->get_result();

$questions = [];
while ($question = $result_questions->fetch_assoc()) {
    $questions[$question['id']] = $question;
    $questions[$question['id']]['answers'] = [];

    // Fetch answers for the question
    $sql_answers = "SELECT * FROM reponse WHERE id_quest = ?";
    $stmt_answers = $conn->prepare($sql_answers);
    $stmt_answers->bind_param("i", $question['id']);
    $stmt_answers->execute();
    $result_answers = $stmt_answers->get_result();
    
    while ($answer = $result_answers->fetch_assoc()) {
        $questions[$question['id']]['answers'][] = $answer;
    }
}

if (!empty($questions)) {
  // Loop through questions
} else {
  echo "<p>No questions found for this quiz.</p>";
}

// Traitement de la modification de l'enseignant
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $quizName = $_POST['nom'];
    //$idEns = $_POST['id_ens'];
    $date_d = $_POST['date_d']; 
    $date_f = $_POST['date_f'];
    // Requête SQL pour mettre à jour les données de l'enseignant
    $sql_update = "UPDATE quiz SET nom='$quizName', date_d='$date_d', date_f='$date_f' WHERE id='$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo '<script>alert("Quiz modifié avec succès : '.$quizName.'"); window.location.href = "quiz.php";</script>';
    } else {
        echo "Erreur lors de la modification du quiz : " . $conn->error;
    }
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

	
    <title>ISSATSO/Modifier_Quiz</title>

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
          <img src="../img/people.png" />
        </a>
      </nav>
       <!-- NAVBAR -->

      <!-- MAIN -->
      <main>
       
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(../images/bg-01.jpg);">
					<span class="login100-form-title-1">
						Modifier un quiz
					</span>
				</div>
    
        <form class="login100-form validate-form" method="post" >
					
          <div class="wrap-input100 validate-input m-b-26" data-validate="Name is required">
						<span class="label-input100">Nom</span>
						<input class="input100" type="text" name="nom" value="<?php echo $row["nom"]; ?>" placeholder="Saisir Nom">
						<span class="focus-input100"></span>
					</div>

          <div class="wrap-input100 validate-input m-b-26" data-validate="Date is required">
						<span class="label-input100">Date debut</span>
						<input class="input100" type="text" name="date_d" value="<?php echo $row["date_d"]; ?>" placeholder="Saisir Date de debut">
						<span class="focus-input100"></span>
					</div>

          <div class="wrap-input100 validate-input m-b-26" data-validate="Date is required">
						<span class="label-input100">Date fin</span>
						<input class="input100" type="text" name="date_f" value="<?php echo $row["date_f"]; ?>" placeholder="Saisir Date de fin">
						<span class="focus-input100"></span>
					</div>
          <div id="questionContainer">
            <?php foreach ($questions as $q_id => $q): ?>
            <div class="questionBlock wrap-input100 validate-input m-b-18">
              <span class="label-input100">Question <?= $q_id ?></span>
              <input class="input100" type="text" name="question[<?= $q_id ?>]" value="<?= htmlspecialchars($q['content']) ?>" placeholder="Enter question" required>
              <span class="focus-input100"></span>
              <div class="answerContainer">
              <?php foreach ($q['answers'] as $a_id => $a): 
                $checked = ($a['valeur'] == 1) ? 'checked' : '';?>
              <div class="answerBlock wrap-input100 validate-input m-b-18">
                <input style="margin-right: 7px;" type="radio" id="radio<?= $q_id ?>_<?= $a_id ?>" name="correct_answer[<?= $q_id ?>]" value="<?= $a_id ?> " <?= $checked ?>>
                <?php  //echo '<input style="margin-right: 7px;" type="radio" id="radio' . $q_id . '_' . $a_id . '" name="correct_answer[' . $q_id . ']" value="' . $a_id . '" ' . $checked . '>'; ?>
                <label style="display: inline;" for="radio<?= $q_id ?>_<?= $a_id ?>" >Reponse Correcte</label>
                <br>
                <input type="text" id="answers<?= $q_id ?>_<?= $a_id ?>" name="answers[<?= $q_id ?>][<?= $a_id ?>]" value="<?= htmlspecialchars($a['content']) ?>" placeholder="Enter answer" required>
              </div>
              <?php endforeach; ?>
              
            </div>
    </div>
    <?php endforeach; ?>
</div>

					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							Modifier
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

  </body>
</html>

<?php
// Fermeture de la connexion à la base de données
$conn->close();
?>
