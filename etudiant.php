<?php
// Vérifier si le paramètre GET pour le succès est défini
if (isset($_GET['ajoute_success']) && $_GET['ajoute_success'] == 1) {
    echo "<script>alert('Etudiant ajouté avec succès !');</script>";
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
    <!--<link rel="stylesheet" href="bootstrap/css/bootstrap.css" />-->
    <title>ISSATSO/Etudiant</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
          <a href='enseignant.php'>
            <i class="bx bxs-group"></i>
            <span class="text">Enseignants</span>
          </a>
        </li>
        <li class="active">
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
        <div class="head-title">
          <div class="left">
            <h1>Gestion des Etudiants</h1>
          </div>
          <a href="ajouter_etudiant.php"  class="btn-download" >
            <i class="bx bx-plus"></i>
            <span class="text" >Ajouter</span>
          </a>
        </div>
        <div class="table-data">
          <div class="order">
            <div class="head">
              <h3>Liste des Etudiants</h3>
              <i class="bx bx-search"></i>
              <i class="bx bx-filter"></i>
            </div>
            <div id="table-container">
              <?php include 'etudiants_content.php'; ?>
          </div>
          </div>
        </div>
      </main>
      <!-- MAIN -->
      


    </section>
    <!-- CONTENT -->
  
    <script src="script.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    
  </body>
</html>
