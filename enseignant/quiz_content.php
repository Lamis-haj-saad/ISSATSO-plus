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
/*
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
        echo "<script>alert('Le quiz existe déjà !');</script>";
        header("Location: ajouter_quiz.php?ajoute_success=1");
    } else {
        // Le quiz n'existe pas, insérer les données dans la base de données
        $sql_insert = "INSERT INTO quiz ( nom, date_d, date_f, nbr_r) VALUES ('$nom', '$date_d', '$date_f', 0)";

        if ($conn->query($sql_insert) === TRUE) {
            header("Location: quiz.php?ajoute_success=1");
            exit();
        } else {
            echo "Erreur: " . $sql_insert . "<br>" . $conn->error;
        }
    }
    foreach ($_POST['question'] as $questionNumber => $content) {
        if (!empty($content)) {
            // Insert question into database
            $sqlQuestion = "INSERT INTO question (id_quiz, content) VALUES (?, ?)";
            $stmt = $conn->prepare($sqlQuestion);
            $stmt->bind_param("is", $quizId, $content);
            $stmt->execute();
            $questionId = $stmt->insert_id; // Get the ID of the inserted question

            // Process each response for this question
            if (!empty($_POST['answers'][$questionNumber])) {
                foreach ($_POST['answers'][$questionNumber] as $answerNum => $answerContent) {
                    if (!empty($answerContent)) {
                        $sqlResponse = "INSERT INTO reponse (id_quest, content) VALUES (?, ?)";
                        $stmt = $conn->prepare($sqlResponse);
                        $stmt->bind_param("is", $questionId, $answerContent);
                        $stmt->execute();
                    }
                }
            }
        }
    }
}*/
/*
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
  $id = $_GET['id'];

  $sql_delete = "DELETE FROM quiz WHERE id='$id'";
  if ($conn->query($sql_delete) === TRUE) {
      $sql_select_name = "SELECT nom FROM quiz WHERE id='$id'";
      $result = $conn->query($sql_select_name);
      if ($result && $result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $quiz_nom = $row['nom'];

          // Redirection vers la page précédente avec un paramètre indiquant la suppression réussie
          header("Location: quiz.php?suppression_success=1&nom=" . urlencode($quiz_nom));
          exit();
      } else {
          // En cas d'erreur ou si aucun résultat n'est trouvé, rediriger sans nom
          header("Location: quiz.php?suppression_success=1");
          exit();
      }
  } else {
      echo "Erreur lors de la suppression: " . $conn->error;
  }
}*/

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Ensure the ID is an integer to prevent SQL injection

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete responses associated with questions of this quiz
        $sql_delete_responses = "DELETE FROM reponse WHERE id_quest IN (SELECT id FROM question WHERE id_quiz=?)";
        $stmt = $conn->prepare($sql_delete_responses);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Delete questions associated with this quiz
        $sql_delete_questions = "DELETE FROM question WHERE id_quiz=?";
        $stmt = $conn->prepare($sql_delete_questions);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Finally, delete the quiz itself
        $sql_delete_quiz = "DELETE FROM quiz WHERE id=?";
        $stmt = $conn->prepare($sql_delete_quiz);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        header("Location: quiz.php?suppression_success=1");
        exit();
    } catch (Exception $e) {
        // An error occurred, rollback transaction
        $conn->rollback();
        echo "Erreur lors de la suppression: " . $conn->error;
    }
}



$sql = "SELECT * FROM quiz";
$result = $conn->query($sql);

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    // Affichage des données dans une table HTML
    echo "<table class='table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Date debut</th>
                    <th>Date fin</th>
                    <th>nombre de reponses</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["id"]."</td>
                <td>".$row["nom"]."</td>
                <td>".$row["date_d"]."</td>
                <td>".$row["date_f"]."</td>
                <td>".$row["nbr_r"]."</td>
                <td>
                <a href='modifier_quiz.php?id=".$row["id"]."'><i class='bx bx-edit'></i></a>
                <a href='quiz.php?id=".$row["id"]."' onclick='return confirm(\"Voulez-vous vraiment supprimer ce quiz ?\")'><i class='bx bx-trash'></i></a>
                </td>
            </tr>";
    }
    echo "</tbody>
        </table>";
} else {
    echo "0 results";
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

