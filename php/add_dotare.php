<?php
@include 'config.php';

session_start();

$error_message = '';
$dotareID = isset($_GET['deleteid']) ? $_GET['deleteid'] : null;
$salaID = isset($_GET['sala_id']) ? $_GET['sala_id'] : null;

$query_dotari = "SELECT dotare_ID, nume FROM dotari";
$result_dotari = mysqli_query($conn, $query_dotari);

if (isset($_GET['sala_id'])) {
    $salaID = mysqli_real_escape_string($conn, $_GET['sala_id']);
    $query_sala = "SELECT nume FROM sali WHERE sala_ID = '$salaID'";
    $result_sala = mysqli_query($conn, $query_sala);
    $row_sala = mysqli_fetch_assoc($result_sala);
    $nume_sala = $row_sala['nume'];
}

if (isset($_POST['adauga_in_sala']) && !empty($_POST['sala_id'])) {
    $dotareID = mysqli_real_escape_string($conn, $_POST['dotare_existent']);
    $salaID = mysqli_real_escape_string($conn, $_POST['sala_id']);
    $cantitate = mysqli_real_escape_string($conn, $_POST['cantitate']);
    $stare = mysqli_real_escape_string($conn, $_POST['stare']);

    if ($cantitate <= 0) {
        $error_message = 'Cantitatea trebuie să fie mai mare decât zero.';
    } else {

        // Adăugarea înregistrării în dotari_sali
        $query = "INSERT INTO dotari_sali (dotare_ID, sala_ID, cantitate, stare) VALUES ('$dotareID', '$salaID', '$cantitate', '$stare')";
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

            <form class="form-detail" action="#" method="post">
                <input type="hidden" name="sala_id" value="<?php echo $_GET['sala_id']; ?>">
                <div class="form-left">
                    <h2 style="text-align: center;">Adăugare Dotare Nouă în <?php echo $nume_sala; ?></h2>

                    <div class="form-row">
                        <select name="dotare_existent">
                            <option class="option" value="title">Selectează o dotare</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($result_dotari)) {
                                echo "<option value='" . $row['dotare_ID'] . "'>" . $row['nume'] . "</option>";
                            }
                            ?>
                        </select>
                        <span class="select-btn">
                            <i class="zmdi zmdi-chevron-down"></i>
                        </span>
                    </div>

                    <div class="form-row form-row-1">
                        <label for="cantitate" style="color: grey; padding-left: 17px;">Cantitate:</label>
                        <input type="number" name="cantitate" required>
                    </div>

                    <div class="form-row form-row-1">
                        <label for="stare" style="color: grey; padding-left: 17px;">Stare:</label>
                        <textarea id="stare" name="stare" placeholder="Descrierea dotarii curente" style="height:100px; width:1000px; border:2px solid #000; padding:10px;" required></textarea>

                    </div>

                    <?php if (!empty($error_message)) { ?>
                        <p style="color: red; text-align:center; font-size:larger"><?php echo $error_message; ?></p>
                    <?php } ?>

                    <div style="text-align: center;">
                        <button id="back_to_dot_list" class="button-30">Inapoi</button>
                        <button type="submit" name="adauga_in_sala" class="button-30">Aduaga dotare</button>
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