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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $sql = "UPDATE quiz SET nbr_r = nbr_r + 1 WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id); // 'i' denotes that the parameter is an integer
  $stmt->execute();
  $stmt->close();
  $user_answers = $_POST['correct_answer'];
  //$isCorrect = ($_POST['correct_answer'] == $answerNum) ? 1 : 0;
  $totalCorrect = 0;

  foreach ($user_answers as $question_id => $answer_id) {
      $stmt = $conn->prepare("SELECT valeur FROM reponse WHERE id = ?");
      $stmt->bind_param("i", $answer_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $answer = $result->fetch_assoc();
      
      if ($answer && $answer['valeur'] == 1) {
          $totalCorrect++;
      }
      $stmt->close();
  }

  // Fetch the existing grade
  $stmt = $conn->prepare("SELECT degre FROM quiz WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $existingGrade = $data ? (int) $data['degre'] : 0;
  $stmt->close();

  // Update the grade if the new grade is higher
  if ($totalCorrect > $existingGrade) {
      $stmt = $conn->prepare("UPDATE quiz SET degre = ? WHERE id = ?");
      $stmt->bind_param("ii", $totalCorrect, $id);
      $stmt->execute();
      $stmt->close();
      
  }
  echo '<script>window.location.href = "quiz.php";</script>';
}
/*

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  // Fetch the user answers from POST data
  $user_answers = $_POST['correct_answer']; // This assumes each question's answer is an array of question_id => answer_id
  $totalCorrect = 0;

  foreach ($user_answers as $q_id => $a_id) {
      // Prepare a statement to fetch the correct value of the selected answer
      $stmt = $conn->prepare("SELECT valeur FROM reponse WHERE id = ?");
      $stmt->bind_param("i", $a_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $answer = $result->fetch_assoc();

      if ($answer && $answer['valeur'] == 1) {
          $totalCorrect++; // Increment the score if the answer is correct
      }
      $stmt->close();
  }

  // Compare and possibly update the quiz grade
  $stmt = $conn->prepare("SELECT degre FROM quiz WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $existingGrade = $data ? (int) $data['degre'] : 0;
  $stmt->close();

  // Update the grade if the new score is higher
  if ($totalCorrect > $existingGrade) {
      $stmt = $conn->prepare("UPDATE quiz SET degre = ? WHERE id = ?");
      $stmt->bind_param("ii", $totalCorrect, $id);
      $stmt->execute();
      $stmt->close();
  }

  echo '<script>window.location.href = "quiz.php";</script>'; // Redirect after processing
}
*/

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

	
    <title>ISSATSO/Answer_Quiz</title>

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
            <span class="text">Emploi</span>
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
            <span class="text">Fiche de reclamation</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="bx bxs-message-dots"></i>
            <span class="text">Reclamation</span>
          </a>
        </li>
        <li class='active'>
          <a href="#">
            <i class="bx bxs-group"></i>
            <span class="text">Quiz</span>
          </a>
        </li>
        <li >
          <a href="dashboard.html">
            <i class="bx bxs-dashboard"></i>
            <span class="text">Notes</span>
          </a>
        </li>
        <li >
          <a href="dashboard.html">
            <i class="bx bxs-dashboard"></i>
            <span class="text">Support de cours</span>
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
						<?php echo $row["nom"]; ?> 
					</span>
				</div>
    
        <form class="login100-form validate-form" method="post" >
          <!-- Inside the form, iterate over questions and answers -->

          <div id="questionContainer">
            <?php foreach ($questions as $q_id => $q): ?>
            <div class="questionBlock  validate-input">
              <span class="label-input100"><?= htmlspecialchars($q['content']) ?> </span>
              <span class="focus-input100"></span>
              <div class="answerContainer">
              <?php foreach ($q['answers'] as $a_id => $a): ?>
                <div class="answerBlock validate-input m-b-18">
                <input style="margin-right: 7px;" type="radio" id="radio<?= $q_id ?>_<?= $a_id ?>" name="correct_answer[<?= $q_id ?>]" value="<?= $a_id ?>">
                <label style="display: inline;" for="radio<?= $q_id ?>_<?= $a_id ?>"><?= htmlspecialchars($a['content']) ?></label>
                <br>
                </div>
              <?php endforeach; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							Submit
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
