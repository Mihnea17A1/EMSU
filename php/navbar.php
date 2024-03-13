<?php

@include 'config.php'; //conectarea la baza de date

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" type="text/css" href="../css/montserrat-font.css">
</head>

<body>

    <header class="header">
        <a href="home_page.php" class="logo">
            <img src="../images/logo.png" alt="logo" style="width: 25%">
        </a>
        <nav class="navbar">
        <p style="color: white; text-align: center;">Conectat ca <b><?php echo $_SESSION['home_page_name'] ?></b></p>

            <?php
            if ($_SESSION['angajat_ID'] == 0) { //butonul de Statistic vizibil doar administratorului
            ?>
            <a href="statistici.php">Statistici</a>
            <?php
            }
            ?>
                <div class="dropdown">
                <a type="text" href="#">Profil</a>
                <div class="dropdown-content">
                    <a href="update_profile.php" >Modifica profil</a>
                    <a href="#" id="logout">Log out</a>
                </div>
            </div>
            <a href="home_page.php">Home</a>
            <a href="sali_page.php">Salile Universitatii</a>
            <a href="lista_dotari.php">Lista Dotari</a>

        </nav>
    </header>
    <script src="../javascript/navbar.js"></script>
</body>

</html>