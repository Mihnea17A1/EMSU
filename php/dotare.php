<?php
@include 'config.php'; // Conectarea la baza de date
session_start();

$id = mysqli_real_escape_string($conn, $_GET['dotareid']);

$query = "SELECT 
    d.dotare_ID, 
    d.nume, 
    d.descriere, 
    d.data_achizitionare, 
    d.pret,
    ds.cantitate, 
    ds.stare
FROM 
    dotari_sali ds
JOIN 
    dotari d ON ds.dotare_ID = d.dotare_ID
WHERE 
    ds.sala_ID = '$id';";


$result = mysqli_query($conn, $query);

// Nu este necesară rularea acestei interogări de două ori
$query_sala = "SELECT * FROM sali WHERE sala_ID = '$id'";
$result_sala = mysqli_query($conn, $query_sala);
$row_sala = mysqli_fetch_assoc($result_sala);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/sali_page.css">

</head>

<body>

    <?php include "navbar.php" ?>

    <p style="text-align: center; color:black; font-size:large; ">Dotarile pentru <?php echo $row_sala['nume'] ?></p>

    <div class="table-wrapper">
            <!-- buton de adaugare a unui nou review -->
            <a href="sali_page.php"><b>
                        <p class="button-30"></i> Inapoi</p>
            </b></a>
            <?php echo "<a href='add_dotare.php?sala_id=" . $row_sala['sala_ID'] . "'><b><p class='button-30'>Adaugă dotare</p></b></a>" ?>

        <table class="fl-table">
            <!-- afisarea informatiilor din tabela DOTARI sub forma de tabel -->
            <thead>
            <tr>
                <th>&nbsp;&nbsp;&nbsp;&nbsp;Nume&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>&nbsp;&nbsp;&nbsp;&nbsp;Data de achizitonare&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>&nbsp;&nbsp;&nbsp;&nbsp;Pret&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>&nbsp;&nbsp;&nbsp;&nbsp;Descriere&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>&nbsp;&nbsp;&nbsp;&nbsp;Cantitate&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                <th>&nbsp;&nbsp;&nbsp;&nbsp;Stare&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>&nbsp;&nbsp;&nbsp;&nbsp;Modifica dotarea&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>&nbsp;&nbsp;&nbsp;&nbsp;Sterge dotarea&nbsp;&nbsp;&nbsp;&nbsp;</th>
            </tr>
</thead>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $row['nume']; ?></td>
                    <td><?php echo $row['data_achizitionare']; ?></td>
                    <td><?php echo $row['pret']; ?></td>
                    <td><?php echo $row['descriere']; ?></td>
                    <td><?php echo $row['cantitate']; ?></td>
                    <td><?php echo $row['stare']; ?></td>
                    <td>
                        <a href='update_dotare_sala.php?updateid=<?php echo $row['dotare_ID']; ?>&sala_id=<?php echo $id; ?>'>
                            <p class="button-30">Edit</p>
                        </a>
                    </td>
                    <td>
                        <a href='delete_dotare.php?deleteid=<?php echo $row['dotare_ID']; ?>&sala_id=<?php echo $id; ?>'>
                            <p class="button-30">Delete</p>
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>

</html>