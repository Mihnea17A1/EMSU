<?php
@include 'config.php';

session_start();

$error_message = '';
$dotareID = isset($_GET['deleteid']) ? $_GET['deleteid'] : null;
$salaID = isset($_GET['sala_id']) ? $_GET['sala_id'] : null;

// Verifică dacă formularul a fost trimis
if (isset($_POST['confirm_delete'])) {
    // Protejează împotriva SQL injection
    $dotareID = mysqli_real_escape_string($conn, $dotareID);
    $salaID = mysqli_real_escape_string($conn, $salaID);

    // Interogare pentru ștergerea dotării din sala specificată
    $query = "DELETE FROM dotari_sali WHERE dotare_ID = '$dotareID' AND sala_ID = '$salaID'";

    if (mysqli_query($conn, $query)) {
        header("Location: dotare.php?dotareid=" . $salaID);
        exit;
    } else {
        $error_message = "Eroare la ștergerea dotării: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete dotare</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/confirm_delletion.css">
</head>

<body>
<?php include "navbar.php" ?>

    <div class="container">
        <?php if ($error_message): ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($dotareID): ?>
            <p>Ești sigur că vrei să ștergi această dotare?</p>
            <form action="" method="post">
                <input type="hidden" name="dotareID" value="<?php echo $dotareID; ?>">
                <?php echo"<a href='dotare.php?dotareid=" . $salaID."' class='button-30'>Inapoi</a>"?> 
                <input type="submit" name="confirm_delete" class="button-30" value="Confirmă Ștergerea">
            </form>
        <?php else: ?>
            <p>ID-ul dotării nu a fost specificat.</p>
        <?php endif; ?>
    </div>
</body>
</html>
