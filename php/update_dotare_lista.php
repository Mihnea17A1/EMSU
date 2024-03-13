<?php
@include 'config.php';
session_start();

if (isset($_GET['updateid'])) {
    $update_id = mysqli_real_escape_string($conn, $_GET['updateid']);
    $query = "SELECT * FROM dotari WHERE dotare_ID = '$update_id'";
    $result = mysqli_query($conn, $query);
    $fetch = mysqli_fetch_assoc($result);
}

if (isset($_POST['update_dotare'])) {

    $nume_dotare = mysqli_real_escape_string($conn, $_POST['update_nume_dotare']);
    $pret_dotare = mysqli_real_escape_string($conn, $_POST['update_pret_dotare']);
    $data_achizitionare_dotare = mysqli_real_escape_string($conn, $_POST['update_data_achizitionare_dotare']);
    $descriere_dotare = mysqli_real_escape_string($conn, $_POST['update_descriere_dotare']);
    // ... Preluarea datelor din formular ...
    if (!empty($nume_dotare) && !empty($pret_dotare) && !empty($data_achizitionare_dotare) && !empty($descriere_dotare)) {
        // Actualizarea dotării în baza de date
        $query = "UPDATE dotari SET nume='$nume_dotare', descriere='$descriere_dotare', data_achizitionare='$data_achizitionare_dotare', pret='$pret_dotare' WHERE dotare_ID = '$update_id'";
        
        if (mysqli_query($conn, $query)) {
            // Interogarea de actualizare a avut succes
            header('Location: lista_dotari.php');
            exit;
        } else {
            // Afisează o eroare în cazul în care interogarea de actualizare a eșuat
            echo "Eroare la actualizarea dotării: " . mysqli_error($conn);
        }
    } else {
        // Gestionează eroarea pentru câmpuri goale
        echo "Toate câmpurile sunt necesare.";
    }
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
                <input type="hidden" name="update_id" value="<?php echo $update_id; ?>">
                <div class="form-left">
                    <h2 style="text-align: center;">Modifica Dotarea Curenta</h2>

                    <div class="form-group">
                        <div class="form-row form-row-1">
                            <input type="text" name="update_nume_dotare" class="input-text" placeholder="Nume" value="<?php echo $fetch['nume']; ?>">
                        </div>
                        <div class="form-row form-row-2">
                            <input type="text" name="update_pret_dotare" class="input-text" placeholder="Pret" value="<?php echo $fetch['pret']; ?>">
                        </div>
                    </div>

                    <div class="form-row form-row-1">
                        <label for="update_achizitionare_dotare" style="color: grey; padding-left: 17px;">Data de achizitionare:</label>
                        <input type="date" id="update_data_achizitionare_dotare" name="update_data_achizitionare_dotare" value="<?php echo $fetch['data_achizitionare']; ?>">
                    </div>

                    <div class="form-row form-row-1">
                        <label for="update_descriere_dotare" style="color: grey; padding-left: 17px;">Descriere:</label>
                        <textarea id="update_descriere_dotare" name="update_descriere_dotare" placeholder="Descrierea dotarii curente" style="height:100px; width:1000px; border:2px solid #000; padding:10px;"><?php echo $fetch['descriere']; ?></textarea>

                    </div>

                    <div style="text-align: center;">
                        <button id="back_to_dot_list" class="button-30">Inapoi</button>
                        <button type="submit" name="update_dotare" class="button-30">Modifica dotare</button>
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