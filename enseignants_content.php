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

// Traitement de l'ajout d'un nouvel enseignant
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $cin = $_POST["cin"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $login = $_POST["login"];
    $password = $_POST["pass"];

    // Vérifier si le CIN existe déjà
    $sql_check_cin = "SELECT * FROM users WHERE cin='$cin'";
    $result_check_cin = $conn->query($sql_check_cin);

    if ($result_check_cin->num_rows > 0) {
        // Le CIN existe déjà, afficher une alerte
        echo "<script>alert('Le numéro CIN existe déjà !');</script>";
        header("Location: ajouter_enseignant.php?ajoute_success=1");
    } else {
        // Le CIN n'existe pas, insérer les données dans la base de données
        $sql_insert = "INSERT INTO users (cin, nom, prenom, login, password) VALUES ('$cin', '$nom', '$prenom', '$login', '$password')";

        if ($conn->query($sql_insert) === TRUE) {
            header("Location: enseignant.php?ajoute_success=1");
            exit();
        } else {
            echo "Erreur: " . $sql_insert . "<br>" . $conn->error;
        }
    }
}

// Traitement de la suppression d'un enseignant
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
  $id = $_GET['id'];

  // Requête SQL pour supprimer l'enseignant
  $sql_delete = "DELETE FROM users WHERE id='$id'";
  if ($conn->query($sql_delete) === TRUE) {
      // Récupérer le nom de l'enseignant supprimé
      $sql_select_name = "SELECT nom FROM users WHERE id='$id'";
      $result = $conn->query($sql_select_name);
      if ($result && $result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $enseignant_nom = $row['nom'];

          // Redirection vers la page précédente avec un paramètre indiquant la suppression réussie
          header("Location: enseignant.php?suppression_success=1&nom=" . urlencode($enseignant_nom));
          exit();
      } else {
          // En cas d'erreur ou si aucun résultat n'est trouvé, rediriger sans nom
          header("Location: enseignant.php?suppression_success=1");
          exit();
      }
  } else {
      echo "Erreur lors de la suppression: " . $conn->error;
  }
}


// Requête SQL pour récupérer les données des enseignants
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    // Affichage des données dans une table HTML
    echo "<table class='table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cin</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Login</th>
                    <th>Mot de passe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["id"]."</td>
                <td>".$row["cin"]."</td>
                <td>".$row["nom"]."</td>
                <td>".$row["prenom"]."</td>
                <td>".$row["login"]."</td>
                <td>".$row["password"]."</td>
                <td>
                <a href='modifier_enseignant.php?id=".$row["id"]."'><i class='bx bx-edit'></i></a>
                <a href='enseignant.php?id=".$row["id"]."' onclick='return confirm(\"Voulez-vous vraiment supprimer cet enseignant ?\")'><i class='bx bx-trash'></i></a>
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

