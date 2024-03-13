<?php
@include 'config.php';
session_start();

if (isset($_POST['add_dot_in_list'])) {
    $nume_dotare = mysqli_real_escape_string($conn, $_POST['nume_dotare']);
    $pret_dotare = mysqli_real_escape_string($conn, $_POST['pret_dotare']);
    $data_achizitionare_dotare = mysqli_real_escape_string($conn, $_POST['data_achizitionare_dotare']);
    $descriere_dotare = mysqli_real_escape_string($conn, $_POST['descriere_dotare']);

    // Inserarea noii dotări în baza de date
    $query = "INSERT INTO dotari (nume, descriere, data_achizitionare, pret) VALUES 
    ('$nume_dotare', '$descriere_dotare', '$data_achizitionare_dotare', '$pret_dotare')";
    mysqli_query($conn, $query);

    // Redirecționarea către lista dotărilor
    header('Location: lista_dotari.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Form-v10 by Colorlib</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" type="text/css" href="../css/montserrat-font.css">

    <link rel="stylesheet" href="../css/add_new_dotare.css" />
    <meta name="robots" content="noindex, follow">
</head>

<body>

    <?php include "navbar.php" ?>

    <div class="page-content">
        <div class="form-v10-content">
            <form class="form-detail" action="#" method="post" id="dotari">
                <div class="form-left">
                    <h2 style="text-align: center;">Adugare Dotare Noua</h2>

                    <div class="form-group">
                        <div class="form-row form-row-1">
                            <input type="text" name="nume_dotare" id="nume_dotare" class="input-text" placeholder="Nume" required>
                    </div>

                        <div class="form-row form-row-2">
                            <input type="text" name="pret_dotare" id="pret_dotare" class="input-text" placeholder="Pret" required>
                        </div>
                    </div>

                    <div class="form-row form-row-1">
                        <label for="data_achizitionare_dotare" style="color: grey; padding-left: 17px;">Data de achizitionare:</label>
                        <input type="date" id="data_achizitionare" name="data_achizitionare" required>
                    </div>

                    <div class="form-row form-row-1">
                        <label for="descriere_dotare" style="color: grey; padding-left: 17px;">Descriere:</label>
                        <textarea id="descriere_dotare" name="descriere_dotare" placeholder="Descrierea dotarii curente" style="height:100px; width:1000px; border:2px solid #000; padding:10px;" required></textarea>

                    </div>

                    <div style="text-align: center;">
                        <button id="back_to_dot_list" class="button-30">Inapoi</button>
                        <button type="submit" name="add_dot_in_list" class="button-30">Aduaga dotare</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('back_to_dot_list').addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Esti sigur ca vrei sa te intorci?')) {
                    window.location.href = 'lista_dotari.php'; // Redirect to the list
                }
            });
        });
    </script>

</body>

</html>