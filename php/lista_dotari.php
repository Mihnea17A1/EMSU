<?php
@include 'config.php'; //conectarea la baza de date

session_start();

$query = "SELECT * FROM dotari";
$result = mysqli_query($conn, $query);

if (isset($_GET['deleteid'])) {
    $dotareID = mysqli_real_escape_string($conn, $_GET['deleteid']);

    $query = "DELETE FROM dotari WHERE dotare_ID = '$dotareID'";
    mysqli_query($conn, $query);

    // O opțiune este să redirecționezi utilizatorul pentru a evita re-ștergerea la reîncărcarea paginii
    header("Location: lista_dotari.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista tuturor dotarilor</title>

    <link rel="stylesheet" type="text/css" href="../css/montserrat-font.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/sali_page.css">

</head>

<body>


    <?php include "navbar.php" ?>


    <div class="table-wrapper">
        <a href='add_new_dotare.php' class="button-30">Adaugă Dotare Nouă</a>
        <table class="fl-table">
            <!-- afisarea informatiilor din tabela DOTARI sub forma de tabel -->
            <thead>
                <tr>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;Nume&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;Descriere&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;Data de achizitonare&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;Pret&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;Modifica dotarea&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;Sterge dotarea&nbsp;&nbsp;&nbsp;&nbsp;</th>
                </tr>
            </thead>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $row['nume']; ?></td>
                    <td><?php echo $row['descriere']; ?></td>
                    <td><?php echo $row['data_achizitionare']; ?></td>
                    <td><?php echo $row['pret']; ?></td>
                    <td>
                        <a href='update_dotare_lista.php?updateid=<?php echo $row['dotare_ID']; ?>'>
                            <p class="button-30">Edit</p>
                        </a>
                    </td>
                    <td>
                        <button onclick="confirmDelete(<?php echo $row['dotare_ID']; ?>)" class="button-30">Delete</button>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>

    <script>
function confirmDelete(dotareId) {
    if (confirm('Ești sigur că vrei să ștergi această dotare? Acțiunea este ireversibilă!')) {
        window.location.href = '?deleteid=' + dotareId;
    }
}
</script>
</body>

</html>