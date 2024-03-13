<?php
@include 'config.php';
session_start();

// Get the sala_ID from the URL
$salaID = isset($_GET['cladireid']) ? mysqli_real_escape_string($conn, $_GET['cladireid']) : null;

$cladireInfo = null;

if ($salaID) {
    // Fetch only 'nume' if 'descriere' does not exist
    $query = "SELECT c.nume FROM sali s 
              JOIN cladiri c ON s.cladire_ID = c.cladire_ID 
              WHERE s.sala_ID = '$salaID'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $cladireInfo = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cladire</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/confirm_delletion.css">
</head>
<body>
    <?php include "navbar.php"; ?>

    <div class="container">
        <?php if ($cladireInfo): ?>
            <div class="cladire-box">
                <h2 style="text-align: center;">Cladire:</h2> 
                <h2><?php echo htmlspecialchars($cladireInfo['nume']); ?></h2>
            </div>
        <?php else: ?>
            <p>Cladirea nu a fost gasita pentru sala selectata.</p>
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
