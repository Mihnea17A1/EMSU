<?php

@include 'config.php';
session_start();

$error_message = '';
$dotareID = isset($_GET['updateid']) ? $_GET['updateid'] : null;
$salaID = isset($_GET['sala_id']) ? $_GET['sala_id'] : null;

$query_dotari = "SELECT dotare_ID, nume FROM dotari";
$result_dotari = mysqli_query($conn, $query_dotari);

if ($dotareID !== null) {
    $query_nume_dotare = "SELECT nume FROM dotari WHERE dotare_ID = '$dotareID'";
    $result_nume_dotare = mysqli_query($conn, $query_nume_dotare);
    if ($row_nume_dotare = mysqli_fetch_assoc($result_nume_dotare)) {
        $nume_dotare = $row_nume_dotare['nume'];
    } else {
        $nume_dotare = "Nume dotare indisponibil"; // sau alt mesaj de eroare
    }
} else {
    $nume_dotare = "ID dotare nu este setat"; // sau alt mesaj de eroare
}

if (isset($_GET['sala_id'])) {
    $salaID = mysqli_real_escape_string($conn, $_GET['sala_id']);
    $query_sala = "SELECT nume FROM sali WHERE sala_ID = '$salaID'";
    $result_sala = mysqli_query($conn, $query_sala);
    if ($row_sala = mysqli_fetch_assoc($result_sala)) {
        $nume_sala = $row_sala['nume'];
    } else {
        $nume_sala = "Nume sala indisponibil"; // sau alt mesaj de eroare
    }
} else {
    $nume_sala = "ID sala nu este setat"; // sau alt mesaj de eroare
}

if (isset($_GET['updateid'])) {
    $dotareSelectataID = mysqli_real_escape_string($conn, $_GET['updateid']);
    $query_dotareSelectata = "SELECT * FROM dotari_sali WHERE dotare_ID = '$dotareSelectataID' AND sala_ID = '$salaID'";
    $result_dotareSelectata = mysqli_query($conn, $query_dotareSelectata);
    $fetch = mysqli_fetch_assoc($result_dotareSelectata);
}

if (isset($_POST['update_dotare']) && !empty($_POST['sala_id'])) {
    $salaID = mysqli_real_escape_string($conn, $_POST['sala_id']);
    $cantitate = mysqli_real_escape_string($conn, $_POST['update_cantitate']);
    $stare = mysqli_real_escape_string($conn, $_POST['update_descriere_dotare']);

    if ($cantitate <= 0) {
        $error_message = 'Cantitatea trebuie să fie mai mare decât zero.';
    } else {
        // Actualizarea dotării existente în sala
        $query = "UPDATE dotari_sali SET cantitate = '$cantitate', stare = '$stare' WHERE dotare_ID = '$dotareID' AND sala_ID = '$salaID'";
        mysqli_query($conn, $query);

        header('Location: dotare.php?dotareid=' . $salaID);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

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
            <form class="form-detail" action="" method="post">
                <input type="hidden" name="sala_id" value="<?php echo $salaID; ?>">

                <div class="form-left">
                    <h2 style="text-align: center;">Modificare Dotare Existenta în <?php echo $nume_sala; ?></h2>
                    <h3 style="text-align: center;">Poti modifica pentru <?php echo $nume_dotare; ?></h3>

                    <div class="form-row form-row-1">
                        <label for="update_cantitate">Cantitate:</label>
                        <input type="number" name="update_cantitate" value="<?php echo isset($fetch['cantitate']) ? $fetch['cantitate'] : ''; ?>">
                    </div>

                    <div class="form-row form-row-1">
                        <label for="update_descriere_dotare">Stare:</label>
                        <textarea id="update_descriere_dotare" name="update_descriere_dotare" placeholder="Descrierea dotarii curente" style="height:100px; width:1000px; border:2px solid #000; padding:10px;"><?php echo isset($fetch['stare']) ? $fetch['stare'] : ''; ?></textarea>

                    </div>

                    <?php if (!empty($error_message)) { ?>
                        <p style="color: red; text-align:center;"><?php echo $error_message; ?></p>
                    <?php } ?>

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
                    window.location.href = 'dotare.php?dotareid=<?php echo $salaID; ?>'; // Redirect to the list
                }
            });
        });
    </script>

</body>

</html>