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
                    <th>Plus haute note</th>
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
                <td>".$row["degre"]."</td>
                <td>
                <a href='answer_quiz.php?id=".$row['id']."class='btn-download' ><i class='bx'></i><span class='text' >Repondre</span></a></td>
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

