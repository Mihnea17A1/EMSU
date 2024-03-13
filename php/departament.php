<?php
@include 'config.php';
session_start();

// Get the sala_ID from the URL
$salaID = isset($_GET['departamentid']) ? mysqli_real_escape_string($conn, $_GET['departamentid']) : null;

$departamentInfo = null;

if ($salaID) {
    // Assuming 'departament_ID' is a column in 'sali' that references 'departamente'
    $query = "SELECT d.nume, d.descriere FROM sali s 
              JOIN departamente d ON s.departament_ID = d.departament_ID 
              WHERE s.sala_ID = '$salaID'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $departamentInfo = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Departament</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete dotare</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/confirm_delletion.css">

</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container">
        <?php if ($departamentInfo) : ?>
            <div class="departament-box">
                <h2 style="text-align: center;">Departament:</h2> 
                <h2><?php echo htmlspecialchars($departamentInfo['nume']); ?></h2>
            </div>
        <?php else : ?>
            <p>Departamentul nu a fost gasit pentru sala selectata.</p>
        <?php endif; ?>

        <div style="text-align: center;">
            <button id="back_to_dot_list" class="button-30">Inapoi</button>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('back_to_dot_list').addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Esti sigur ca vrei sa te intorci?')) {
                    window.location.href = 'sali_page.php'; // Redirect to the list
                }
            });
        });
    </script>
</body>

</html>