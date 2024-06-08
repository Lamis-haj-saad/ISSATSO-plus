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

// Récupérer l'ID de l'enseignant à modifier depuis l'URL
$id = $_GET["id"];

// Récupérer les données de l'enseignant à modifier depuis la base de données
$sql_select_single = "SELECT * FROM users WHERE id = '$id'";
$result_select_single = $conn->query($sql_select_single);
$row = $result_select_single->fetch_assoc();

// Traitement de la modification de l'enseignant
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $cin = $_POST["cin"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $login = $_POST["login"];
    $password = $_POST["pass"];

    // Requête SQL pour mettre à jour les données de l'enseignant
    $sql_update = "UPDATE users SET cin='$cin', nom='$nom', prenom='$prenom', login='$login', password='$password' WHERE id='$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo '<script>alert("Enseignant modifié avec succès : '.$nom.'"); window.location.href = "enseignant.php";</script>';
    } else {
        echo "Erreur lors de la modification de l'enseignant : " . $conn->error;
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
    <link rel="stylesheet" href="style.css" />
  
    <!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->

	
    <title>ISSATSO/Ajouter_Enseignant</title>

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
        <li class="active">
        <a href="enseignant.php">
            <i class="bx bxs-group"></i>
            <span class="text">Enseignants</span>
          </a>
        </li>
        <li>
          <a href="#">
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
						Modifier un enseiagnant
					</span>
				</div>
    


    
                <form class="login100-form validate-form" method="post" >
					<div class="wrap-input100 validate-input m-b-26" data-validate="Cin is required">
						<span class="label-input100">CIN</span>
						<input class="input100" type="text" name="cin" value="<?php echo $row["cin"]; ?>" placeholder="Saisir Cin" >
						<span class="focus-input100"></span>
					</div>

                    <div class="wrap-input100 validate-input m-b-26" data-validate="Name is required">
						<span class="label-input100">Nom</span>
						<input class="input100" type="text" name="nom" value="<?php echo $row["nom"]; ?>" placeholder="Saisir Nom">
						<span class="focus-input100"></span>
					</div>

                    <div class="wrap-input100 validate-input m-b-26" data-validate="Last Name is required">
						<span class="label-input100">Prenom</span>
						<input class="input100" type="text" name="prenom" value="<?php echo $row["prenom"]; ?>" placeholder="Saisir Prenom">
						<span class="focus-input100"></span>
					</div>

                    <div class="wrap-input100 validate-input m-b-26" data-validate="Login is required">
						<span class="label-input100">Login</span>
						<input class="input100" type="text" name="login" value="<?php echo $row["login"]; ?>" placeholder="Saisir Login">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
						<span class="label-input100">Mot de passe</span>
						<input class="input100" type="password" name="pass" value="<?php echo $row["password"]; ?>" placeholder="Saisir Mot de passe">
						<span class="focus-input100"></span>
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
  
    <script src="script.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script src="js/main.js"></script>

  </body>
</html>

<?php
// Fermeture de la connexion à la base de données
$conn->close();
?>
